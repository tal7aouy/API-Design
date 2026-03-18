from datetime import datetime
from typing import Dict, Optional

from fastapi import APIRouter, Depends, Header, HTTPException, status
from pydantic import BaseModel, Field

from .auth import CurrentUser, get_current_user
from .products import Product, _PRODUCTS

router = APIRouter(tags=["orders"])


class OrderItem(BaseModel):
    product_id: str
    quantity: int = Field(gt=0)


class OrderCreate(BaseModel):
    items: list[OrderItem] = Field(min_items=1)


class Order(BaseModel):
    id: str
    user_id: str
    items: list[OrderItem]
    created_at: datetime


_ORDERS: dict[str, Order] = {}
_ORDER_ID_SEQ = 0
_IDEMPOTENCY_STORE: dict[str, Order] = {}


def _next_order_id() -> str:
    global _ORDER_ID_SEQ
    _ORDER_ID_SEQ += 1
    return f"order_{_ORDER_ID_SEQ}"


@router.get("/orders", response_model=list[Order])
def list_orders(current_user: CurrentUser = Depends(get_current_user)):
    return [o for o in _ORDERS.values() if o.user_id == current_user.user_id]


@router.post(
    "/orders",
    response_model=Order,
    status_code=status.HTTP_201_CREATED,
)
def create_order(
    payload: OrderCreate,
    current_user: CurrentUser = Depends(get_current_user),
    idempotency_key: Optional[str] = Header(default=None, alias="Idempotency-Key"),
):
    if not idempotency_key:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Idempotency-Key header is required for order creation.",
        )

    # Idempotency naive cache: same key returns same response
    if existing := _IDEMPOTENCY_STORE.get(idempotency_key):
        return existing

    # Validate items and deduct inventory
    for item in payload.items:
        product = _PRODUCTS.get(item.product_id)
        if not product:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND,
                detail=f"Product {item.product_id} not found",
            )
        if product.available_quantity < item.quantity:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail=f"Not enough inventory for {product.name}",
            )

    # Deduct inventory
    for item in payload.items:
        product = _PRODUCTS[item.product_id]
        product.available_quantity -= item.quantity

    order = Order(
        id=_next_order_id(),
        user_id=current_user.user_id,
        items=payload.items,
        created_at=datetime.utcnow(),
    )
    _ORDERS[order.id] = order
    _IDEMPOTENCY_STORE[idempotency_key] = order

    return order

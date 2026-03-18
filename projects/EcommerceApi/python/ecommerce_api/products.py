from typing import Optional

from fastapi import APIRouter, HTTPException, Query, status
from pydantic import BaseModel, Field

router = APIRouter(tags=["products"])


class ProductBase(BaseModel):
    name: str = Field(min_length=3)
    description: str = Field(min_length=5)
    price_cents: int = Field(gt=0)
    available_quantity: int = Field(ge=0)


class ProductCreate(ProductBase):
    pass


class Product(ProductBase):
    id: str


_PRODUCTS: dict[str, Product] = {}
_SEQ = 0


def _next_id() -> str:
    global _SEQ
    _SEQ += 1
    return f"product_{_SEQ}"


@router.get("/products", response_model=dict)
def list_products(
    limit: int = Query(20, ge=1, le=100),
    cursor: Optional[str] = None,
):
    products = list(_PRODUCTS.values())
    products = products[:limit]
    return {
        "data": products,
        "pagination": {
            "next_cursor": None,
            "previous_cursor": None,
            "limit": limit,
        },
    }


@router.post(
    "/products",
    response_model=Product,
    status_code=status.HTTP_201_CREATED,
)
def create_product(payload: ProductCreate):
    product = Product(id=_next_id(), **payload.model_dump())
    _PRODUCTS[product.id] = product
    return product


@router.get("/products/{product_id}", response_model=Product)
def get_product(product_id: str):
    product = _PRODUCTS.get(product_id)
    if not product:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Product not found",
        )
    return product

from fastapi import FastAPI

from . import auth, orders, products

app = FastAPI(title="E-commerce API", version="1.0.0")

app.include_router(auth.router, prefix="/api/v1")
app.include_router(products.router, prefix="/api/v1")
app.include_router(orders.router, prefix="/api/v1")

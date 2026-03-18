from fastapi import FastAPI

from . import auth, comments, posts
from .middleware import request_logger

app = FastAPI(title="Blog API", version="1.0.0")
app.middleware('http')(request_logger)

app.include_router(auth.router, prefix="/api/v1")
app.include_router(posts.router, prefix="/api/v1")
app.include_router(comments.router, prefix="/api/v1")

from datetime import datetime
from pydantic import BaseModel, Field


class PostCreate(BaseModel):
    title: str = Field(min_length=3)
    body: str = Field(min_length=10)


class Post(BaseModel):
    id: str
    title: str
    body: str
    author_id: str
    created_at: datetime

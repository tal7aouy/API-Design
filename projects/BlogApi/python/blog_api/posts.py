from typing import Optional

from fastapi import APIRouter, Depends, HTTPException, status
from pydantic import BaseModel, Field

from .auth import CurrentUser, get_current_user

router = APIRouter(tags=["posts"])


class PostBase(BaseModel):
    title: str = Field(min_length=3)
    body: str = Field(min_length=10)


class PostCreate(PostBase):
    pass


class Post(PostBase):
    id: str
    author_id: str


_POSTS: dict[str, Post] = {}
_SEQ = 0


def _next_id() -> str:
    global _SEQ
    _SEQ += 1
    return f"post_{_SEQ}"


@router.get("/posts", response_model=dict)
def list_posts(limit: int = 20, cursor: Optional[str] = None):
    posts = list(_POSTS.values())
    posts = posts[: min(limit, 50)]
    return {
        "data": posts,
        "pagination": {
            "next_cursor": None,
            "previous_cursor": None,
            "limit": limit,
        },
    }


@router.post(
    "/posts",
    response_model=Post,
    status_code=status.HTTP_201_CREATED,
)
def create_post(
    payload: PostCreate,
    current_user: CurrentUser = Depends(get_current_user),
):
    post = Post(
        id=_next_id(),
        title=payload.title,
        body=payload.body,
        author_id=current_user.user_id,
    )
    _POSTS[post.id] = post
    return post

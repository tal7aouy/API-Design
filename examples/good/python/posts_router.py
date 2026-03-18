from typing import Optional

from fastapi import APIRouter, Depends, HTTPException, status

from .dto import Post, PostCreate
from .repositories import InMemoryPostRepository
from .services import PostService

router = APIRouter(prefix="/posts", tags=["posts"])

_repo = InMemoryPostRepository()
_service = PostService(_repo)


def get_post_service() -> PostService:
    return _service


@router.get("", response_model=dict)
def list_posts(
    limit: int = 20,
    cursor: Optional[str] = None,
    service: PostService = Depends(get_post_service),
):
    posts = service.list_posts(limit, cursor)
    return {
        "data": [p.dict() for p in posts],
        "pagination": {
            "next_cursor": None,
            "previous_cursor": None,
            "limit": limit,
        },
    }


@router.post("", response_model=Post, status_code=status.HTTP_201_CREATED)
def create_post(
    payload: PostCreate,
    service: PostService = Depends(get_post_service),
):
    # In a real app, author_id comes from auth dependency.
    author_id = "user_1"
    try:
        return service.create_post(payload, author_id)
    except ValueError as exc:
        raise HTTPException(
            status_code=status.HTTP_422_UNPROCESSABLE_ENTITY,
            detail=str(exc),
        ) from exc

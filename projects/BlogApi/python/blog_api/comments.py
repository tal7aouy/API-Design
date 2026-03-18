from typing import Optional

from fastapi import APIRouter, Depends, HTTPException, status
from pydantic import BaseModel, Field

from .auth import CurrentUser, get_current_user

router = APIRouter(tags=["comments"])


class CommentBase(BaseModel):
    body: str = Field(min_length=3)


class CommentCreate(CommentBase):
    pass


class Comment(CommentBase):
    id: str
    post_id: str
    author_id: str


_COMMENTS: dict[str, Comment] = {}
_SEQ = 0


def _next_id() -> str:
    global _SEQ
    _SEQ += 1
    return f"comment_{_SEQ}"


@router.get("/posts/{post_id}/comments", response_model=dict)
def list_comments(post_id: str, limit: int = 20, cursor: Optional[str] = None):
    comments = [c for c in _COMMENTS.values() if c.post_id == post_id]
    comments = comments[: min(limit, 50)]
    return {
        "data": comments,
        "pagination": {
            "next_cursor": None,
            "previous_cursor": None,
            "limit": limit,
        },
    }


@router.post(
    "/posts/{post_id}/comments",
    response_model=Comment,
    status_code=status.HTTP_201_CREATED,
)
def create_comment(
    post_id: str,
    payload: CommentCreate,
    current_user: CurrentUser = Depends(get_current_user),
):
    comment = Comment(
        id=_next_id(),
        post_id=post_id,
        body=payload.body,
        author_id=current_user.user_id,
    )
    _COMMENTS[comment.id] = comment
    return comment

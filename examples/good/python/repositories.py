from datetime import datetime
from typing import Optional

from .dto import Post


class InMemoryPostRepository:
    def __init__(self) -> None:
        self._posts: dict[str, Post] = {}
        self._seq = 0

    def _next_id(self) -> str:
        self._seq += 1
        return f"post_{self._seq}"

    def list(self, limit: int, cursor: Optional[str]) -> list[Post]:
        posts = sorted(self._posts.values(), key=lambda p: p.created_at, reverse=True)
        return posts[:limit]

    def create(self, title: str, body: str, author_id: str) -> Post:
        post = Post(
            id=self._next_id(),
            title=title,
            body=body,
            author_id=author_id,
            created_at=datetime.utcnow(),
        )
        self._posts[post.id] = post
        return post

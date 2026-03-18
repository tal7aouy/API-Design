from typing import Optional

from .dto import Post, PostCreate
from .repositories import InMemoryPostRepository


class PostService:
    def __init__(self, repo: InMemoryPostRepository) -> None:
        self._repo = repo

    def list_posts(self, limit: int, cursor: Optional[str]) -> list[Post]:
        safe_limit = max(1, min(limit or 20, 50))
        return self._repo.list(safe_limit, cursor)

    def create_post(self, data: PostCreate, author_id: str) -> Post:
        return self._repo.create(data.title, data.body, author_id)

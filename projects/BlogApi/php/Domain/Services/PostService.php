<?php

declare(strict_types=1);

namespace Projects\BlogApi\Php\Domain\Services;

use Projects\BlogApi\Php\Domain\DTO\PostDto;
use Projects\BlogApi\Php\Domain\Repositories\PostRepository;

final class PostService
{
    public function __construct(private readonly PostRepository $repository)
    {
    }

    /**
     * @return PostDto[]
     */
    public function listPosts(int $limit = 20): array
    {
        return $this->repository->list($limit);
    }

    public function getPost(int $id): ?PostDto
    {
        return $this->repository->find($id);
    }

    public function createPost(string $title, string $body, string $authorId): PostDto
    {
        if (trim($title) === '' || strlen($title) < 3) {
            throw new \InvalidArgumentException('Title must be at least 3 characters.');
        }

        if (trim($body) === '' || strlen($body) < 10) {
            throw new \InvalidArgumentException('Body must be at least 10 characters.');
        }

        return $this->repository->create($title, $body, $authorId);
    }
}

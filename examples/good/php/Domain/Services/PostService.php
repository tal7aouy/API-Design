<?php

declare(strict_types=1);

namespace Examples\Good\Php\Domain\Services;

use Examples\Good\Php\Domain\Repositories\PostRepository;
use InvalidArgumentException;

final class PostService
{
    public function __construct(private PostRepository $posts)
    {
    }

    public function listPosts(int $limit, ?string $cursor): array
    {
        $limit = max(1, min($limit, 50));
        return $this->posts->list($limit, $cursor);
    }

    public function createPost(string $title, string $body, string $authorId)
    {
        if (mb_strlen($title) < 3) {
            throw new InvalidArgumentException('Title must be at least 3 characters.');
        }

        if (mb_strlen($body) < 10) {
            throw new InvalidArgumentException('Body must be at least 10 characters.');
        }

        return $this->posts->create($title, $body, $authorId);
    }
}

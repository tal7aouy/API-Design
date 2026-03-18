<?php

declare(strict_types=1);

namespace Projects\BlogApi\Php\Domain\DTO;

final class PostDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $body,
        public readonly string $authorId,
        public readonly string $createdAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'author_id' => $this->authorId,
            'created_at' => $this->createdAt,
        ];
    }
}

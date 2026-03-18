<?php

declare(strict_types=1);

namespace Examples\Good\Php\Domain\DTO;

final class PostDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $body,
        public readonly string $authorId,
        public readonly \DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            id: (string) $row['id'],
            title: (string) $row['title'],
            body: (string) $row['body'],
            authorId: (string) $row['author_id'],
            createdAt: new \DateTimeImmutable($row['created_at']),
        );
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'body'       => $this->body,
            'author_id'  => $this->authorId,
            'created_at' => $this->createdAt->format(DATE_ATOM),
        ];
    }
}

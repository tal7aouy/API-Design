<?php

declare(strict_types=1);

namespace Projects\BlogApi\Php\Domain\Repositories;

use PDO;
use Projects\BlogApi\Php\Domain\DTO\PostDto;

final class PostRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function list(int $limit = 20): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM posts ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $posts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = new PostDto(
                (int) $row['id'],
                (string) $row['title'],
                (string) $row['body'],
                (string) $row['author_id'],
                (string) $row['created_at'],
            );
        }

        return $posts;
    }

    public function find(int $id): ?PostDto
    {
        $stmt = $this->pdo->prepare('SELECT * FROM posts WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $row) {
            return null;
        }

        return new PostDto(
            (int) $row['id'],
            (string) $row['title'],
            (string) $row['body'],
            (string) $row['author_id'],
            (string) $row['created_at'],
        );
    }

    public function create(string $title, string $body, string $authorId): PostDto
    {
        $stmt = $this->pdo->prepare('INSERT INTO posts (title, body, author_id, created_at) VALUES (:title, :body, :author_id, :created_at)');
        $stmt->execute([
            ':title' => $title,
            ':body' => $body,
            ':author_id' => $authorId,
            ':created_at' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return $this->find($id);
    }
}

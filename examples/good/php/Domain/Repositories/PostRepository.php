<?php

declare(strict_types=1);

namespace Examples\Good\Php\Domain\Repositories;

use Examples\Good\Php\Domain\DTO\PostDto;
use PDO;

final class PostRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /** @return PostDto[] */
    public function list(int $limit, ?string $cursor): array
    {
        $sql = 'SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return array_map(
            fn (array $row) => PostDto::fromArray($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC),
        );
    }

    public function create(string $title, string $body, string $authorId): PostDto
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO posts (title, body, author_id, created_at) VALUES (:title, :body, :author_id, :created_at)'
        );

        $stmt->execute([
            ':title'      => $title,
            ':body'       => $body,
            ':author_id'  => $authorId,
            ':created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);

        $id = (string) $this->pdo->lastInsertId();

        return new PostDto(
            id: $id,
            title: $title,
            body: $body,
            authorId: $authorId,
            createdAt: new \DateTimeImmutable(),
        );
    }
}

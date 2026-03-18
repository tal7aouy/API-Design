<?php

declare(strict_types=1);

namespace Projects\EcommerceApi\Php\Domain\Repositories;

use PDO;
use Projects\EcommerceApi\Php\Domain\DTO\OrderDto;

final class OrderRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return OrderDto[]
     */
    public function listForUser(string $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC');
        $stmt->execute([':user_id' => $userId]);

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $this->hydrate($row);
        }

        return $orders;
    }

    public function create(string $userId, array $payload, string $idempotencyKey): OrderDto
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO orders (user_id, payload, created_at, idempotency_key) VALUES (:user_id, :payload, :created_at, :idempotency_key)'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':payload' => json_encode($payload),
            ':created_at' => (new \DateTimeImmutable())->format(DATE_ATOM),
            ':idempotency_key' => $idempotencyKey,
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return $this->find($id);
    }

    public function findByIdempotencyKey(string $key): ?OrderDto
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE idempotency_key = :key LIMIT 1');
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $row) {
            return null;
        }

        return $this->hydrate($row);
    }

    private function hydrate(array $row): OrderDto
    {
        return new OrderDto(
            (int) $row['id'],
            (string) $row['user_id'],
            json_decode((string) $row['payload'], true),
            (string) $row['created_at'],
        );
    }
}

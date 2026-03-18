<?php

declare(strict_types=1);

namespace Projects\EcommerceApi\Php\Domain\Repositories;

use PDO;
use Projects\EcommerceApi\Php\Domain\DTO\ProductDto;

final class ProductRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return ProductDto[]
     */
    public function list(int $limit = 20): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $this->hydrate($row);
        }

        return $items;
    }

    public function find(int $id): ?ProductDto
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function create(array $attributes): ProductDto
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO products (name, description, price_cents, available_quantity) VALUES (:name, :description, :price_cents, :available_quantity)'
        );
        $stmt->execute([
            ':name' => $attributes['name'],
            ':description' => $attributes['description'],
            ':price_cents' => $attributes['price_cents'],
            ':available_quantity' => $attributes['available_quantity'],
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return $this->find($id);
    }

    public function updateQuantity(int $id, int $delta): void
    {
        $stmt = $this->pdo->prepare('UPDATE products SET available_quantity = available_quantity + :delta WHERE id = :id');
        $stmt->execute([':delta' => $delta, ':id' => $id]);
    }

    private function hydrate(array $row): ProductDto
    {
        return new ProductDto(
            (int) $row['id'],
            (string) $row['name'],
            (string) $row['description'],
            (int) $row['price_cents'],
            (int) $row['available_quantity'],
        );
    }
}

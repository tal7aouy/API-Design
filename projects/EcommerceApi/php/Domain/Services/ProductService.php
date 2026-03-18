<?php

declare(strict_types=1);

namespace Projects\EcommerceApi\Php\Domain\Services;

use Projects\EcommerceApi\Php\Domain\DTO\ProductDto;
use Projects\EcommerceApi\Php\Domain\Repositories\ProductRepository;

final class ProductService
{
    public function __construct(private readonly ProductRepository $repository)
    {
    }

    /**
     * @return ProductDto[]
     */
    public function list(int $limit = 20): array
    {
        return $this->repository->list($limit);
    }

    public function create(array $attributes): ProductDto
    {
        if (trim((string) ($attributes['name'] ?? '')) === '') {
            throw new \InvalidArgumentException('Product name is required.');
        }

        if ((int) ($attributes['price_cents'] ?? 0) <= 0) {
            throw new \InvalidArgumentException('Price must be greater than 0.');
        }

        return $this->repository->create($attributes);
    }

    public function adjustInventory(int $productId, int $delta): void
    {
        $this->repository->updateQuantity($productId, $delta);
    }
}

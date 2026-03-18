<?php

declare(strict_types=1);

namespace Projects\EcommerceApi\Php\Domain\Services;

use Projects\EcommerceApi\Php\Domain\Repositories\OrderRepository;
use Projects\EcommerceApi\Php\Domain\Repositories\ProductRepository;
use Projects\EcommerceApi\Php\Domain\DTO\OrderDto;

final class OrderService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly ProductRepository $productRepository,
    ) {
    }

    /**
     * @return OrderDto[]
     */
    public function listForUser(string $userId): array
    {
        return $this->orderRepository->listForUser($userId);
    }

    public function createOrder(string $userId, array $items, string $idempotencyKey): OrderDto
    {
        if (trim($idempotencyKey) === '') {
            throw new \InvalidArgumentException('Idempotency key is required.');
        }

        if ($existing = $this->orderRepository->findByIdempotencyKey($idempotencyKey)) {
            return $existing;
        }

        foreach ($items as $item) {
            $product = $this->productRepository->find((int) ($item['product_id'] ?? 0));
            if (! $product) {
                throw new \InvalidArgumentException('Product not found.');
            }

            if ($product->availableQuantity < (int) ($item['quantity'] ?? 0)) {
                throw new \InvalidArgumentException('Insufficient inventory.');
            }

            $this->productRepository->updateQuantity($product->id, -(int) $item['quantity']);
        }

        return $this->orderRepository->create($userId, $items, $idempotencyKey);
    }
}

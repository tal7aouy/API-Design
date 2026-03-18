<?php

declare(strict_types=1);

namespace Projects\EcommerceApi\Php\Domain\DTO;

final class OrderDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $userId,
        public readonly array $items,
        public readonly string $createdAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'items' => $this->items,
            'created_at' => $this->createdAt,
        ];
    }
}

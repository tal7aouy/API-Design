<?php

declare(strict_types=1);

namespace Projects\EcommerceApi\Php\Domain\DTO;

final class ProductDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly int $priceCents,
        public int $availableQuantity,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price_cents' => $this->priceCents,
            'available_quantity' => $this->availableQuantity,
        ];
    }
}

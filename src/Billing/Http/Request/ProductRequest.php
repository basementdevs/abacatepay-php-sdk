<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Billing\Http\Request;

final readonly class ProductRequest
{
    public function __construct(
        public string $externalId,
        public string $name,
        public string $description,
        public int    $quantity,
        public int    $price,
    ) {}

    public function toArray(): array
    {
        return [
            'externalId' => $this->externalId,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}

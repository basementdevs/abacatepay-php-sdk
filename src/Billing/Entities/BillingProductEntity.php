<?php

declare(strict_types=1);

namespace AbacatePay\Billing\Entities;

use JsonSerializable;

final readonly class BillingProductEntity implements JsonSerializable
{
    public function __construct(
        public string $id,
        public string $external_id,
        public int $quantity,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            external_id: $data['externalId'],
            quantity: $data['quantity'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'quantity' => $this->quantity,
        ];
    }
}

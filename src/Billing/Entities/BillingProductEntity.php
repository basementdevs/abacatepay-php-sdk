<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Billing\Entities;

use JsonSerializable;

final readonly class BillingProductEntity implements JsonSerializable
{
    public function __construct(
        public string $id,
        public string $externalId,
        public int $quantity,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            externalId: $data['externalId'],
            quantity: $data['quantity'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'externalId' => $this->externalId,
            'quantity' => $this->quantity,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Store\Entities;

use Basement\AbacatePay\Store\Http\Response\StoreBalanceResponse;

final readonly class StoreEntity
{
    public function __construct(
        public string $id,
        public string $name,
        public StoreBalanceResponse $balance,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            balance: StoreBalanceResponse::fromArray($data['balance']),
        );
    }
}

<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Store\Http\Response;

final readonly class StoreBalanceResponse
{
    public function __construct(
        public int $available,
        public int $pending,
        public int $blocked
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            available: $data['available'],
            pending: $data['pending'],
            blocked: $data['blocked']
        );
    }
}

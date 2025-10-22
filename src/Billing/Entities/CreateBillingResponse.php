<?php

namespace AbacatePay\Billing\Entities;

final readonly class CreateBillingResponse
{
    public function __construct(
        public BillingEntity $data,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: BillingEntity::fromArray($data['data']),
        );
    }
}
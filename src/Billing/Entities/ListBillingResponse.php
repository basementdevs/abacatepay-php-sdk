<?php

namespace AbacatePay\Billing\Entities;

final readonly class ListBillingResponse
{
    public function __construct(
        public BillingEntityCollection $data,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: BillingEntityCollection::fromArray($data['data']),
        );
    }
}
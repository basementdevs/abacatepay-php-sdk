<?php

declare(strict_types=1);

namespace AbacatePay\Customer\Http\Response;

use AbacatePay\Customer\Entities\CustomerEntity;

final readonly class CreateCustomerResponse
{
    public function __construct(
        public CustomerEntity $data,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: CustomerEntity::fromArray($data['data']),
        );
    }
}

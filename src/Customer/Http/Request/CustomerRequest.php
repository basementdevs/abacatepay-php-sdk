<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Customer\Http\Request;

final readonly class CustomerRequest
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cellphone,
        public string $email,
        public string $tax_id,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'taxId' => $this->tax_id,
        ];
    }
}

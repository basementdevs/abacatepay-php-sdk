<?php
declare(strict_types=1);
namespace AbacatePay\Customer\Entities;

final readonly class CreateCustomerRequest
{
    public function __construct(
        public string $name,
        public string $cellphone,
        public string $email,
        public string $tax_id,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'taxId' => $this->tax_id,
        ];
    }
}
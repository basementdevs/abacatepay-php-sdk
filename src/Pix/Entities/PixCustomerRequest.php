<?php

final readonly class PixCustomerRequest
{
    public function __construct(
        public string $name,
        public string $cellphone,
        public string $email,
        public string $taxId,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'taxId' => $this->taxId,
        ];
    }
}
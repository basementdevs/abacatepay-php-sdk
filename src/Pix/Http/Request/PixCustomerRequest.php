<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Request;

final readonly class PixCustomerRequest
{
    public function __construct(
        public string $name,
        public string $cellphone,
        public string $email,
        public string $taxId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            $data['name'],
            $data['cellphone'],
            $data['email'],
            $data['taxId'],
        );
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

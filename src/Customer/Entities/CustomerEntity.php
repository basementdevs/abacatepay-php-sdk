<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Customer\Entities;

use JsonSerializable;

final readonly class CustomerEntity implements JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cellphone,
        public string $email,
        public string $tax_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['metadata']['name'],
            cellphone: $data['metadata']['cellphone'],
            email: $data['metadata']['email'],
            tax_id: $data['metadata']['taxId']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'tax_id' => $this->tax_id,
        ];
    }
}

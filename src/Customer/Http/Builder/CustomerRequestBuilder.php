<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Customer\Http\Builder;

use Basement\AbacatePay\Customer\Http\Request\CustomerRequest;
use Basement\AbacatePay\Exception\AbacatePayException;

final class CustomerRequestBuilder
{
    private ?string $id = null;

    private ?string $name = null;

    private ?string $cellphone = null;

    private ?string $email = null;

    private ?string $taxId = null;

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function cellphone(string $cellphone): self
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function taxId(string $taxId): self
    {
        $this->taxId = $taxId;

        return $this;
    }

    /**
     * @throws AbacatePayException
     */
    public function build(): CustomerRequest
    {
        $errors = [];

        if ($this->id === null) {
            $errors[] = 'id';
        }

        if ($this->name === null) {
            $errors[] = 'name';
        }

        if ($this->cellphone === null) {
            $errors[] = 'cellphone';
        }

        if ($this->email === null) {
            $errors[] = 'email';
        }

        if ($this->taxId === null) {
            $errors[] = 'taxId';
        }

        if ($errors !== []) {
            throw AbacatePayException::missingRequiredFields($errors);
        }

        return new CustomerRequest(
            id: $this->id,
            name: $this->name,
            cellphone: $this->cellphone,
            email: $this->email,
            tax_id: $this->taxId,
        );
    }
}

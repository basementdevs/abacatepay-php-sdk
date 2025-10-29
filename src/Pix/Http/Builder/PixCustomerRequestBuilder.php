<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Builder;

use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Pix\Http\Request\PixCustomerRequest;
use InvalidArgumentException;

final class PixCustomerRequestBuilder
{
    private ?string $name = null;

    private ?string $cellphone = null;

    private ?string $email = null;

    private ?string $taxId = null;

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
    public function build(): PixCustomerRequest
    {
        $errors = [];

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

        return new PixCustomerRequest(
            name: $this->name,
            cellphone: $this->cellphone,
            email: $this->email,
            taxId: $this->taxId,
        );
    }
}

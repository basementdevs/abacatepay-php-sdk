<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Builder;

use Basement\AbacatePay\Pix\Http\Request\CreatePixQrCodeRequest;
use Basement\AbacatePay\Pix\Http\Request\PixCustomerRequest;
use Basement\AbacatePay\Pix\Http\Request\PixMetadataRequest;
use InvalidArgumentException;

final class CreatePixQrCodeRequestBuilder
{
    private ?int $amount = null;

    private ?int $expiresIn = null;

    private ?string $description = null;

    private ?PixCustomerRequest $customer = null;

    private ?PixMetadataRequest $metadata = null;

    public function amount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function expiresIn(int $seconds): self
    {
        $this->expiresIn = $seconds;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function customer(PixCustomerRequest $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function metadata(PixMetadataRequest $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function build(): CreatePixQrCodeRequest
    {
        $errors = [];

        if ($this->amount === null) {
            $errors[] = 'amount';
        }

        if ($this->expiresIn === null) {
            $errors[] = 'expiresIn';
        }

        if ($this->description === null) {
            $errors[] = 'description';
        }

        if (! $this->customer instanceof PixCustomerRequest) {
            $errors[] = 'customer';
        }

        if (! $this->metadata instanceof PixMetadataRequest) {
            $errors[] = 'metadata';
        }

        if ($errors !== []) {
            throw new InvalidArgumentException('Missing required fields: '.implode(', ', $errors));
        }

        return new CreatePixQrCodeRequest(
            amount: $this->amount,
            expiresIn: $this->expiresIn,
            description: $this->description,
            customer: $this->customer,
            metadata: $this->metadata,
        );
    }
}

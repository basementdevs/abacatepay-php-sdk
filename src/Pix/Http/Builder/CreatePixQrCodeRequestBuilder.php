<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Builder;

use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Pix\Http\Request\CreatePixQrCodeRequest;
use Basement\AbacatePay\Pix\Http\Request\PixCustomerRequest;
use Basement\AbacatePay\Pix\Http\Request\PixMetadataRequest;

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

    public function expiresIn(?int $seconds): self
    {
        $this->expiresIn = $seconds;

        return $this;
    }

    public function description(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function customer(?PixCustomerRequest $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function metadata(?PixMetadataRequest $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @throws AbacatePayException
     */
    public function build(): CreatePixQrCodeRequest
    {
        if ($this->amount === null) {
            throw AbacatePayException::missingRequiredFields(['amount']);
        }

        return new CreatePixQrCodeRequest(
            amount: $this->amount,
            expiresIn: $this->expiresIn ?? 0,
            description: $this->description ?? '',
            customer: $this->customer ?? new PixCustomerRequest('', '', '', ''),
            metadata: $this->metadata ?? new PixMetadataRequest(''),
        );
    }
}

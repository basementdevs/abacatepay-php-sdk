<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Request;

use Basement\AbacatePay\Pix\Http\Builder\CreatePixQrCodeRequestBuilder;
use JsonSerializable;

final readonly class CreatePixQrCodeRequest
{
    public function __construct(
        public int $amount,
        public ?int $expiresIn,
        public ?string $description,
        public ?PixCustomerRequest $customer,
        public ?PixMetadataRequest $metadata,
    ) {}

    public static function builder(): CreatePixQrCodeRequestBuilder
    {
        return new CreatePixQrCodeRequestBuilder;
    }

    public static function make(array $data): self
    {
        return new self(
            amount: $data['amount'],
            expiresIn: $data['expiresIn'] ?? null,
            description: $data['description'] ?? null,
            customer: $data['customer'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = ['amount' => $this->amount];

        if ($this->expiresIn !== null) {
            $data['expiresIn'] = $this->expiresIn;
        }

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->customer instanceof PixCustomerRequest) {
            $data['customer'] = $this->customer->toArray();
        }

        if ($this->metadata instanceof PixMetadataRequest) {
            $data['metadata'] = $this->metadata->toArray();
        }

        return $data;
    }
}

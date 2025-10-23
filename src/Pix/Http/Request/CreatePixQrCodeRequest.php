<?php

declare(strict_types=1);

namespace AbacatePay\Pix\Http\Request;

use JsonSerializable;

final readonly class CreatePixQrCodeRequest implements JsonSerializable
{
    public function __construct(
        public int $amount,
        public int $expiresIn,
        public string $description,
        public PixCustomerRequest $customer,
        public PixMetadataRequest $metadata,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount,
            'expiresIn' => $this->expiresIn,
            'description' => $this->description,
            'customer' => $this->customer->toArray(),
            'metadata' => $this->metadata->toArray(),
        ];
    }
}

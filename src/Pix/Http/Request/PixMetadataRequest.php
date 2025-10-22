<?php

declare(strict_types=1);

namespace AbacatePay\Pix\Http\Request;

final readonly class PixMetadataRequest
{
    public function __construct(
        public string $externalId,
    ) {
    }

    public function toArray(): array
    {
        return [
            'externalId' => $this->externalId,
        ];
    }
}

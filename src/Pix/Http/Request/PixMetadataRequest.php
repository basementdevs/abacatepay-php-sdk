<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Request;

final readonly class PixMetadataRequest
{
    public function __construct(
        public string $externalId,
    ) {}

    public static function make(array $data): self
    {
        return new self(
            $data['externalId'],
        );
    }

    public function toArray(): array
    {
        return [
            'externalId' => $this->externalId,
        ];
    }
}

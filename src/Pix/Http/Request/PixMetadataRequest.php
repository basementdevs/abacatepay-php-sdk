<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Request;

use Basement\AbacatePay\Pix\Http\Builder\PixMetadataRequestBuilder;

final readonly class PixMetadataRequest
{
    public function __construct(
        public string $externalId,
    ) {}

    public static function builder(): PixMetadataRequestBuilder
    {
        return new PixMetadataRequestBuilder;
    }

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

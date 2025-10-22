<?php

namespace Entities;

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
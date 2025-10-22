<?php

namespace Entities;

final readonly class CreatePixQrCodeResponse
{
    public function __construct(
        public PixQrCodeEntity $data,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: PixQrCodeEntity::fromArray($data['data']),
        );
    }
}
<?php

declare(strict_types=1);

namespace AbacatePay\Pix\Http\Response;

use Entities\PixQrCodeEntity;

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

<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Response;

use Basement\AbacatePay\Pix\Entities\PixQrCodeEntity;

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

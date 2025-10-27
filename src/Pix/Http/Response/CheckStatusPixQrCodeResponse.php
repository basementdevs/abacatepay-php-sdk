<?php

declare(strict_types=1);

namespace AbacatePay\Pix\Http\Response;

use AbacatePay\Pix\Entities\StatusPixQrCode;

class CheckStatusPixQrCodeResponse
{
    public function __construct(
        public StatusPixQrCode $data
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: StatusPixQrCode::fromArray($data['data'])
        );
    }
}

<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Response;

use Basement\AbacatePay\Pix\Entities\StatusPixQrCode;

class CheckStatusPixQrCodeResponse
{
    public function __construct(
        public StatusPixQrCode $data
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            data: StatusPixQrCode::fromArray($data['data'])
        );
    }
}

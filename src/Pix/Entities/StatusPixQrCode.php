<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Entities;

use Basement\AbacatePay\Pix\Enum\StatusQrCode;
use JsonSerializable;

class StatusPixQrCode implements JsonSerializable
{
    public function __construct(
        public StatusQrCode $status,
        public string $expiresAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            status: StatusQrCode::from($data['status']),
            expiresAt: $data['expiresAt']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status->value,
            'expiresAt' => $this->expiresAt
        ];
    }
}

<?php

namespace Entities;

use DateTimeImmutable;
use JsonSerializable;

final readonly class PixQrCodeEntity implements JsonSerializable
{
    public function __construct(
        public string $id,
        public int $amount,
        public string $status,
        public bool $dev_mode,
        public string $br_code,
        public string $br_code_base64,
        public int $platform_fee,
        public DateTimeImmutable $created_at,
        public DateTimeImmutable $updated_at,
        public DateTimeImmutable $expires_at,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            amount: $data['amount'],
            status: $data['status'],
            dev_mode: $data['devMode'],
            br_code: $data['brCode'],
            br_code_base64: $data['brCodeBase64'],
            platform_fee: $data['platformFee'],
            created_at: new DateTimeImmutable($data['createdAt']),
            updated_at: new DateTimeImmutable($data['updatedAt']),
            expires_at: new DateTimeImmutable($data['expiresAt']),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status,
            'dev_mode' => $this->dev_mode,
            'br_code' => $this->br_code,
            'br_code_base64' => $this->br_code_base64,
            'platform_fee' => $this->platform_fee,
            'created_at' => $this->created_at->format('c'),
            'updated_at' => $this->updated_at->format('c'),
            'expires_at' => $this->expires_at->format('c'),
        ];
    }
}
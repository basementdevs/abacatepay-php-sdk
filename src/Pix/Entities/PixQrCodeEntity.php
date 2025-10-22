<?php

declare(strict_types=1);

namespace Entities;

use AbacatePay\Billing\Enum\AbacatePayBillingMethodEnum;
use AbacatePay\Billing\Enum\AbacatePayBillingStatusEnum;
use JsonSerializable;

final readonly class PixQrCodeEntity implements JsonSerializable
{
    /**
     * @param  AbacatePayBillingMethodEnum[]  $methods
     */
    public function __construct(
        public string $id,
        public int $amount,
        public AbacatePayBillingStatusEnum $status,
        public bool $dev_mode,
        public string $br_code,
        public array $methods,
        public string $br_code_base64,
        public int $platform_fee,
        public string $created_at,
        public string $updated_at,
        public string $expires_at,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            amount: $data['amount'],
            status: AbacatePayBillingStatusEnum::from($data['status']),
            dev_mode: $data['devMode'],
            br_code: $data['brCode'],
            methods: array_map(
                AbacatePayBillingMethodEnum::from(...),
                $data['methods'] ?? []
            ),
            br_code_base64: $data['brCodeBase64'],
            platform_fee: $data['platformFee'],
            created_at: $data['createdAt'],
            updated_at: $data['updatedAt'],
            expires_at: $data['expiresAt'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status->value,
            'dev_mode' => $this->dev_mode,
            'br_code' => $this->br_code,
            'br_code_base64' => $this->br_code_base64,
            'platform_fee' => $this->platform_fee,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'expires_at' => $this->expires_at,
        ];
    }
}

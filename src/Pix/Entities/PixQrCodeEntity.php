<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Entities;

use Basement\AbacatePay\Billing\Enum\BillingMethodEnum;
use Basement\AbacatePay\Billing\Enum\BillingStatusEnum;
use JsonSerializable;

final readonly class PixQrCodeEntity implements JsonSerializable
{
    /**
     * @param  BillingMethodEnum[]  $methods
     */
    public function __construct(
        public string            $id,
        public int               $amount,
        public BillingStatusEnum $status,
        public bool              $devMode,
        public string            $brCode,
        public string            $brCodeBase64,
        public int               $platformFee,
        public string            $createdAt,
        public string            $updatedAt,
        public string            $expiresAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            amount: $data['amount'],
            status: BillingStatusEnum::from($data['status']),
            devMode: $data['devMode'],
            brCode: $data['brCode'],
            brCodeBase64: $data['brCodeBase64'],
            platformFee: $data['platformFee'],
            createdAt: $data['createdAt'],
            updatedAt: $data['updatedAt'],
            expiresAt: $data['expiresAt'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status->value,
            'dev_mode' => $this->devMode,
            'br_code' => $this->brCode,
            'br_code_base64' => $this->brCodeBase64,
            'platform_fee' => $this->platformFee,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'expires_at' => $this->expiresAt,
        ];
    }
}

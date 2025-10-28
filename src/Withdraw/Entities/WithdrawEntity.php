<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Withdraw\Entities;

use Basement\AbacatePay\Withdraw\Enums\WithdrawKindEnum;
use Basement\AbacatePay\Withdraw\Enums\WithdrawStatusEnum;
use JsonSerializable;

final readonly class WithdrawEntity implements JsonSerializable
{
    public function __construct(
        public string $id,
        public WithdrawStatusEnum $status,
        public bool $devMode,
        public string $url,
        public WithdrawKindEnum $kind,
        public int $amount,
        public int $platformFee,
        public ?string $externalId,
        public string $created_at,
        public string $updated_at,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: WithdrawStatusEnum::from($data['status']),
            devMode: $data['devMode'],
            url: $data['receiptUrl'],
            kind: WithdrawKindEnum::from($data['kind']),
            amount: $data['amount'],
            platformFee: $data['platformFee'],
            externalId: $data['externalId'] ?? null,
            created_at: $data['createdAt'],
            updated_at: $data['updatedAt'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'devMode' => $this->devMode,
            'url' => $this->url,
            'kind' => $this->kind->value,
            'amount' => $this->amount,
            'platformFee' => $this->platformFee,
            'externalId' => $this->externalId,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

declare(strict_types=1);

namespace AbacatePay\WithDraw\Entities;

use AbacatePay\WithDraw\Enums\AvailableWithDrawStatusEnum;
use JsonSerializable;

final readonly class WithDrawEntity implements JsonSerializable
{
    /**
     * @param string $id
     * @param AvailableWithDrawStatusEnum $status
     * @param bool $devMode
     * @param string $url
     * @param string $kind
     * @param int $amount
     * @param int $platformFee
     * @param string $externalId
     * @param string $created_at
     * @param string $updated_at
     */
    public function __construct(
        public string $id,
        public AvailableWithDrawStatusEnum $status,
        public bool $devMode,
        public string $url,
        public string $kind,
        public int $amount,
        public int $platformFee,
        public string $externalId,
        public string $created_at,
        public string $updated_at,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: AvailableWithDrawStatusEnum::from($data['status']),
            devMode: $data['devMode'],
            url: $data['receiptUrl'],
            kind: $data['kind'],
            amount: $data['amount'],
            platformFee: $data['platformFee'],
            externalId: $data['externalId'],
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
            'kind' => $this->kind,
            'amount' => $this->amount,
            'platformFee' => $this->platformFee,
            'externalId' => $this->externalId,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

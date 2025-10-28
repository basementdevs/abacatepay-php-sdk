<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Coupon\Entities;

use Basement\AbacatePay\Coupon\Enums\CouponDiscountKindEnum;
use Basement\AbacatePay\Coupon\Enums\CouponStatusEnum;
use JsonSerializable;

final readonly class CouponEntity implements JsonSerializable
{
    public function __construct(
        public string $id,
        public CouponDiscountKindEnum $discountKind,
        public int $discount,
        public int $maxRedeems,
        public int $redeemsCount,
        public CouponStatusEnum $status,
        public bool $devMode,
        public string $notes,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            discountKind: CouponDiscountKindEnum::from($data['discountKind']),
            discount: $data['discount'],
            maxRedeems: $data['maxRedeems'],
            redeemsCount: $data['redeemsCount'],
            status: CouponStatusEnum::from($data['status']),
            devMode: $data['devMode'],
            notes: $data['notes'],
            createdAt: $data['createdAt'],
            updatedAt: $data['updatedAt'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'discountKind' => $this->discountKind,
            'discount' => $this->discount,
            'maxRedeems' => $this->maxRedeems,
            'redeemsCount' => $this->redeemsCount,
            'status' => $this->status->value,
            'devMode' => $this->devMode,
            'notes' => $this->notes,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Coupon\Http\Request;

use Basement\AbacatePay\Coupon\Enums\CouponDiscountKindEnum;
use JsonSerializable;

final readonly class CreateCouponRequest implements JsonSerializable
{
    public function __construct(
        public string $code,
        public string $notes,
        public int $maxRedeems,
        public CouponDiscountKindEnum $discountKind,
        public int $discount,
        public ?array $metadata,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            code: $data['code'],
            notes: $data['notes'],
            maxRedeems: $data['maxRedeems'],
            discountKind: CouponDiscountKindEnum::from($data['discountKind']),
            discount: $data['discount'],
            metadata: $data['metadata'] ?? ['null' => null],
        );
    }

    public function jsonSerialize(): array
    {
        return [
                'code' => $this->code,
                'notes' => $this->notes,
                'maxRedeems' => $this->maxRedeems,
                'discountKind' => $this->discountKind->value,
                'discount' => $this->discount,
                'metadata' => $this->metadata,
        ];
    }
}

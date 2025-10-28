<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Coupon\Http\Response;

use Basement\AbacatePay\Coupon\Entities\CouponEntity;

final readonly class CouponResponse
{
    public function __construct(
        public CouponEntity $data,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            data: CouponEntity::fromArray($data['data']),
        );
    }
}

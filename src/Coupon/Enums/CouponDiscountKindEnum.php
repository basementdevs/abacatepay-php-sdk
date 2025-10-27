<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Coupon\Enums;

enum CouponDiscountKindEnum: string
{
    case Percentage = 'PERCENTAGE';
    case Fixed = 'FIXED';
}

<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Coupon\Enums;

enum CouponStatusEnum: string
{
    case Active = 'ACTIVE';
    case Deleted = 'DELETED';
    case Disabled = 'DISABLED';
}

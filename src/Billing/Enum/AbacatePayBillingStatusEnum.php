<?php

declare(strict_types=1);

namespace AbacatePay\Billing\Enum;

enum AbacatePayBillingStatusEnum: string
{
    case Pending = 'PENDING';
    case Expired = 'EXPIRED';
    case Cancelled = 'CANCELLED';
    case Paid = 'PAID';
    case Refunded = 'REFUNDED';
}

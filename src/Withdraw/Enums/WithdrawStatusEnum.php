<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Withdraw\Enums;

enum WithdrawStatusEnum: string
{
    case Pending = 'PENDING';
    case Expired = 'EXPIRED';
    case Cancelled = 'CANCELLED';
    case Complete = 'COMPLETE';
    case Refunded = 'REFUNDED';
}

<?php

declare(strict_types=1);

namespace AbacatePay\WithDraw\Enums;

enum AvailableWithDrawStatusEnum: string
{
    case PENDING = 'PENDING';
    case EXPIRED = 'EXPIRED';
    case CANCELLED = 'CANCELLED';
    case COMPLETE = 'COMPLETE';
    case REFUNDED = 'REFUNDED';
}

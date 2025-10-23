<?php

namespace AbacatePay\WithDraw\Enums;

enum AvailableWithDrawStatusEnum: string
{
    case PENDING = 'PENDING';
    case EXPIRED = 'EXPIRED';
    case CANCELLED = 'CANCELLED';
    case COMPLETE = 'COMPLETE';
    case REFUNDED = 'REFUNDED';
}

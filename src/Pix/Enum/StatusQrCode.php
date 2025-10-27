<?php

declare(strict_types=1);

namespace AbacatePay\Pix\Enum;

enum StatusQrCode: string
{
    case Pending = 'PENDING';
    case Expired = 'EXPIRED';
    case Cancelled = 'CANCELLED';
    case Paid = 'PAID';
    case Refunded = 'REFUNDED';
}

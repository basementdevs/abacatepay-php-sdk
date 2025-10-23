<?php

declare(strict_types=1);

namespace AbacatePay\Billing\Enum;

enum BillingMethodEnum: string
{
    case Pix = 'PIX';
    case Card = 'CARD';
}

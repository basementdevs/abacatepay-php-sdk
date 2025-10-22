<?php

declare(strict_types=1);

namespace AbacatePay\Billing\Enum;

enum AbacatePayBillingFrequencyEnum: string
{
    case OneTime = 'ONE_TIME';
    case MultiplePayments = 'MULTIPLE_PAYMENTS';
}

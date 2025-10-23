<?php

declare(strict_types=1);

namespace AbacatePay\WithDraw\Enums;

enum AvailableWithDrawPixTypeEnum: string
{
    case CPF = 'CPF';
    case CNPJ = 'CNPJ';
    case PHONE = 'PHONE';
    case EMAIL = 'EMAIL';
    case RANDOM = 'RANDOM';
    case BR_CODE = 'BR_CODE';
}

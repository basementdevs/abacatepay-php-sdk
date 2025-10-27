<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Withdraw\Enums;

enum WithdrawPixTypeEnum: string
{
    case Cpf = 'CPF';
    case Cnpj = 'CNPJ';
    case Phone = 'PHONE';
    case Email = 'EMAIL';
    case Random = 'RANDOM';
    case Br_Code = 'BR_CODE';
}

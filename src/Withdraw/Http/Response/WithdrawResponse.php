<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Withdraw\Http\Response;

use Basement\AbacatePay\Withdraw\Entities\WithdrawEntity;

final readonly class WithdrawResponse
{
    public function __construct(
        public WithdrawEntity $data,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: WithdrawEntity::fromArray($data['data']),
        );
    }
}

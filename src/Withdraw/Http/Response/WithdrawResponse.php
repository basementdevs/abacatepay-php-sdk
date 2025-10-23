<?php

declare(strict_types=1);

namespace AbacatePay\Withdraw\Http\Response;

use AbacatePay\Withdraw\Entities\WithdrawEntity;

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

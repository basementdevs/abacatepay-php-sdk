<?php

declare(strict_types=1);

namespace AbacatePay\WithDraw\Http\Response;

use AbacatePay\WithDraw\Entities\WithDrawEntity;

final readonly class WithDrawResponse
{
    public function __construct(
        public WithDrawEntity $data,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: WithDrawEntity::fromArray($data['data']),
        );
    }
}

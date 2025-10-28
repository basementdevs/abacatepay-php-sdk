<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Billing\Http\Response;

use Basement\AbacatePay\Billing\Entities\BillingEntity;

final readonly class CreateBillingResponse
{
    public function __construct(
        public BillingEntity $data,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            data: BillingEntity::fromArray($data['data']),
        );
    }
}

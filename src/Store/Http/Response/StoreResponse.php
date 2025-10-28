<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Store\Http\Response;

use Basement\AbacatePay\Store\Entities\StoreEntity;

final readonly class StoreResponse
{
    public function __construct(
        public StoreEntity $data,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            data: StoreEntity::fromArray($data['data'])
        );
    }

}

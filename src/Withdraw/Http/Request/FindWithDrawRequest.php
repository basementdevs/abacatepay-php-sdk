<?php

namespace AbacatePay\Withdraw\Http\Request;

final readonly class FindWithDrawRequest
{
    public function __construct(
        public string $externalId,
    ){}

    public static function make(array $data): self
    {
        return new self(
            externalId: $data['externalId'],
        );
    }
}
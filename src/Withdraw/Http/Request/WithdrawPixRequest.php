<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Withdraw\Http\Request;

use Basement\AbacatePay\Withdraw\Enums\WithdrawPixTypeEnum;
use Basement\AbacatePay\Withdraw\Http\Builder\WithdrawPixRequestBuilder;
use JsonSerializable;

final readonly class WithdrawPixRequest implements JsonSerializable
{
    public function __construct(
        public WithdrawPixTypeEnum $type,
        public string $key
    ) {}

    public static function builder(): WithdrawPixRequestBuilder
    {
        return new WithdrawPixRequestBuilder;
    }

    public static function make(array $data): self
    {
        return new self(
            type: WithdrawPixTypeEnum::from($data['type']),
            key: $data['key'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type->value,
            'key' => $this->key,
        ];
    }
}

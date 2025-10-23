<?php

declare(strict_types=1);

namespace AbacatePay\WithDraw\Http\Request;

use AbacatePay\WithDraw\Enums\AvailableWithDrawPixTypeEnum;
use JsonSerializable;

final readonly class WithDrawPixRequest implements JsonSerializable
{
    public function __construct(
        public AvailableWithDrawPixTypeEnum $type,
        public string                       $key
    ) {}

    public static function make(array $data): self
    {
        return new self(
            type: AvailableWithDrawPixTypeEnum::from($data['type']),
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

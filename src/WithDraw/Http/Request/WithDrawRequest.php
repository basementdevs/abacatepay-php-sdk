<?php

declare(strict_types=1);

namespace AbacatePay\WithDraw\Http\Request;

use AbacatePay\WithDraw\Enums\AvailableWithDrawMethodsEnum;
use JsonSerializable;

final readonly class WithDrawRequest implements JsonSerializable
{
    public function __construct(
        public string                       $externalId,
        public AvailableWithDrawMethodsEnum $method,
        public string                       $amount,
        public WithDrawPixRequest           $pix,
        public ?string                      $description = null,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            externalId: $data['externalId'],
            method: AvailableWithDrawMethodsEnum::from($data['method']),
            amount: $data['amount'],
            pix: WithDrawPixRequest::make($data['pix']),
            description: $data['description'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'description' => $this->description,
            'externalId' => $this->externalId,
            'method' => $this->method->value,
            'amount' => $this->amount,
            'pix' => $this->pix->jsonSerialize(),
        ];
    }
}

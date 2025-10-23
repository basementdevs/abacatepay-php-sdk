<?php

declare(strict_types=1);

namespace AbacatePay\Withdraw\Http\Request;

use AbacatePay\Withdraw\Enums\WithdrawMethodsEnum;
use JsonSerializable;

final readonly class CreateWithdrawRequest implements JsonSerializable
{
    public function __construct(
        public string              $externalId,
        public WithdrawMethodsEnum $method,
        public int                 $amount,
        public WithdrawPixRequest  $pix,
        public ?string             $description = null,
    )
    {
    }

    public static function make(array $data): self
    {
        return new self(
            externalId: $data['externalId'],
            method: WithdrawMethodsEnum::from($data['method']),
            amount: $data['amount'],
            pix: $data['pix'],
            description: $data['description'] ?? null,
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

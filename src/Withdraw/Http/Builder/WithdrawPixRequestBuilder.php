<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Withdraw\Http\Builder;

use Basement\AbacatePay\Withdraw\Enums\WithdrawPixTypeEnum;
use Basement\AbacatePay\Withdraw\Http\Request\WithdrawPixRequest;
use InvalidArgumentException;

final class WithdrawPixRequestBuilder
{
    private ?WithdrawPixTypeEnum $type = null;

    private ?string $key = null;

    public function type(WithdrawPixTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function key(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function build(): WithdrawPixRequest
    {
        $errors = [];

        if (! $this->type instanceof WithdrawPixTypeEnum) {
            $errors[] = 'type';
        }

        if ($this->key === null) {
            $errors[] = 'key';
        }

        if ($errors !== []) {
            throw new InvalidArgumentException('Missing required fields: '.implode(', ', $errors));
        }

        return new WithdrawPixRequest(
            type: $this->type,
            key: $this->key,
        );
    }
}

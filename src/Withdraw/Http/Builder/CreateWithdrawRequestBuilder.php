<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Withdraw\Http\Builder;

use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Withdraw\Enums\WithdrawMethodsEnum;
use Basement\AbacatePay\Withdraw\Http\Request\CreateWithdrawRequest;
use Basement\AbacatePay\Withdraw\Http\Request\WithdrawPixRequest;

final class CreateWithdrawRequestBuilder
{
    private ?string $externalId = null;

    private ?WithdrawMethodsEnum $method = null;

    private ?int $amount = null;

    private ?WithdrawPixRequest $pix = null;

    private ?string $description = null;

    public function externalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function method(WithdrawMethodsEnum $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function amount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function pix(WithdrawPixRequest $pix): self
    {
        $this->pix = $pix;

        return $this;
    }

    public function description(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @throws AbacatePayException
     */
    public function build(): CreateWithdrawRequest
    {
        $errors = [];

        if ($this->externalId === null) {
            $errors[] = 'externalId';
        }

        if (! $this->method instanceof WithdrawMethodsEnum) {
            $errors[] = 'method';
        }

        if ($this->amount === null) {
            $errors[] = 'amount';
        }

        if (! $this->pix instanceof WithdrawPixRequest) {
            $errors[] = 'pix';
        }

        if ($errors !== []) {
            throw AbacatePayException::missingRequiredFields($errors);
        }

        return new CreateWithdrawRequest(
            externalId: $this->externalId,
            method: $this->method,
            amount: $this->amount,
            pix: $this->pix,
            description: $this->description,
        );
    }
}

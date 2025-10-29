<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Builder;

use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Pix\Http\Request\PixMetadataRequest;

final class PixMetadataRequestBuilder
{
    private ?string $externalId = null;

    public function externalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @throws AbacatePayException
     */
    public function build(): PixMetadataRequest
    {
        $errors = [];
        if ($this->externalId === null) {
            $errors[] = 'externalId';
        }

        if ($errors !== []) {
            throw AbacatePayException::missingRequiredFields($errors);
        }

        return new PixMetadataRequest(
            externalId: $this->externalId,
        );
    }
}

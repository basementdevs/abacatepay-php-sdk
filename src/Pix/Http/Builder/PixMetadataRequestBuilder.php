<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix\Http\Builder;

use Basement\AbacatePay\Pix\Http\Request\PixMetadataRequest;
use InvalidArgumentException;

final class PixMetadataRequestBuilder
{
    private ?string $externalId = null;

    public function externalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function build(): PixMetadataRequest
    {
        if ($this->externalId === null) {
            throw new InvalidArgumentException('Missing required field: externalId');
        }

        return new PixMetadataRequest(
            externalId: $this->externalId,
        );
    }
}

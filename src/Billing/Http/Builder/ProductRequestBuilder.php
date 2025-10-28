<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Billing\Http\Builder;

use Basement\AbacatePay\Billing\Http\Request\ProductRequest;
use InvalidArgumentException;

final class ProductRequestBuilder
{
    private ?string $externalId = null;

    private ?string $name = null;

    private ?string $description = null;

    private ?int $quantity = null;

    private ?int $price = null;

    public function externalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function quantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function price(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function build(): ProductRequest
    {
        $errors = [];

        if ($this->externalId === null) {
            $errors[] = 'externalId';
        }

        if ($this->name === null) {
            $errors[] = 'name';
        }

        if ($this->description === null) {
            $errors[] = 'description';
        }

        if ($this->quantity === null) {
            $errors[] = 'quantity';
        }

        if ($this->price === null) {
            $errors[] = 'price';
        }

        if ($errors !== []) {
            throw new InvalidArgumentException('Missing required fields: '.implode(', ', $errors));
        }

        return new ProductRequest(
            externalId: $this->externalId,
            name: $this->name,
            description: $this->description,
            quantity: $this->quantity,
            price: $this->price,
        );
    }
}

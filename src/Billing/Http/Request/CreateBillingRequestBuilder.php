<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Billing\Http\Request;

use Basement\AbacatePay\Billing\Enum\BillingFrequencyEnum;
use Basement\AbacatePay\Billing\Enum\BillingMethodEnum;
use Basement\AbacatePay\Customer\Http\Request\CustomerRequest;
use InvalidArgumentException;

final class CreateBillingRequestBuilder
{
    private ?BillingFrequencyEnum $frequency = null;

    /** @var BillingMethodEnum[] */
    private array $methods = [];

    /** @var ProductRequest[] */
    private array $products = [];

    private ?string $returnUrl = null;

    private ?string $completionUrl = null;

    private bool $allowCoupons = false;

    /** @var string[] */
    private array $coupons = [];

    private ?string $customerId = null;

    private ?CustomerRequest $customer = null;

    private ?string $externalId = null;

    public function frequency(BillingFrequencyEnum $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function methods(BillingMethodEnum ...$methods): self
    {
        foreach ($methods as $method) {
            $this->methods[] = $method;
        }

        return $this;
    }

    public function creditCard(): self
    {
        $this->methods[] = BillingMethodEnum::Card;

        return $this;
    }

    public function pix(): self
    {
        $this->methods[] = BillingMethodEnum::Pix;

        return $this;
    }

    public function addMethod(BillingMethodEnum $method): self
    {
        $this->methods[] = $method;

        return $this;
    }

    public function products(ProductRequest ...$products): self
    {
        foreach ($products as $product) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function addProduct(ProductRequest $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    public function returnUrl(string $url): self
    {
        $this->returnUrl = $url;

        return $this;
    }

    public function completionUrl(string $url): self
    {
        $this->completionUrl = $url;

        return $this;
    }

    public function allowCoupons(bool $allow = true): self
    {
        $this->allowCoupons = $allow;

        return $this;
    }

    public function coupons(string ...$codes): self
    {
        foreach ($codes as $code) {
            $this->coupons[] = $code;
        }

        return $this;
    }

    public function addCoupon(string $code): self
    {
        $this->coupons[] = $code;

        return $this;
    }

    public function forCustomerId(string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function forCustomer(CustomerRequest $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function externalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function build(): CreateBillingRequest
    {
        $errors = [];
        if (! $this->frequency instanceof BillingFrequencyEnum) {
            $errors[] = 'frequency';
        }

        if ($this->methods === []) {
            $errors[] = 'methods (at least one)';
        }

        if ($this->products === []) {
            $errors[] = 'products (at least one)';
        }

        if ($this->returnUrl === null) {
            $errors[] = 'returnUrl';
        }

        if ($this->completionUrl === null) {
            $errors[] = 'completionUrl';
        }

        if ($this->customerId !== null && $this->customer instanceof CustomerRequest) {
            throw new InvalidArgumentException('Only one of customerId or customer may be provided.');
        }

        if ($errors !== []) {
            throw new InvalidArgumentException('Missing required fields: '.implode(', ', $errors));
        }

        return new CreateBillingRequest(
            frequency: $this->frequency,
            methods: $this->methods,
            products: $this->products,
            return_url: $this->returnUrl,
            completion_url: $this->completionUrl,
            allow_coupons: $this->allowCoupons,
            coupons: $this->coupons,
            customerId: $this->customerId,
            customer: $this->customer,
            externalId: $this->externalId,
        );
    }
}

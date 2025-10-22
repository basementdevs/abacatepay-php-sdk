<?php

namespace AbacatePay\Billing\Entities;

final readonly class CreateBillingRequest
{
    /**
     * @param  string[]  $methods
     * @param  ProductRequest[]  $products
     * @param  string[]  $coupons
     */
    public function __construct(
        public string $frequency,
        public array $methods,
        public array $products,
        public string $returnUrl,
        public string $completionUrl,
        public ?string $customerId,
        public ?CustomerRequest $customer,
        public bool $allowCoupons,
        public array $coupons,
        public ?string $externalId,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'frequency' => $this->frequency,
            'methods' => $this->methods,
            'products' => array_map(
                fn(ProductRequest $product) => $product->toArray(),
                $this->products
            ),
            'returnUrl' => $this->returnUrl,
            'completionUrl' => $this->completionUrl,
            'allowCoupons' => $this->allowCoupons,
            'coupons' => $this->coupons,
        ];

        if ($this->customerId !== null) {
            $data['customerId'] = $this->customerId;
        }

        if ($this->customer !== null) {
            $data['customer'] = $this->customer->toArray();
        }

        if ($this->externalId !== null) {
            $data['externalId'] = $this->externalId;
        }

        return $data;
    }
}
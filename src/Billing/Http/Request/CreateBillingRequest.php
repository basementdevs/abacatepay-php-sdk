<?php

declare(strict_types=1);

namespace AbacatePay\Billing\Http\Request;

use AbacatePay\Billing\Enum\AbacatePayBillingFrequencyEnum;
use AbacatePay\Billing\Enum\AbacatePayBillingMethodEnum;
use AbacatePay\Customer\Http\Request\CustomerRequest;

final readonly class CreateBillingRequest
{
    /**
     * @param  AbacatePayBillingMethodEnum[]  $methods
     * @param  ProductRequest[]  $products
     * @param  string[]  $coupons
     */
    public function __construct(
        public AbacatePayBillingFrequencyEnum $frequency,
        public array $methods,
        public array $products,
        public string $return_url,
        public string $completion_url,
        public ?string $customer_id,
        public ?CustomerRequest $customer,
        public bool $allow_coupons,
        public array $coupons,
        public ?string $external_id
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'frequency' => $this->frequency,
            'methods' => $this->methods,
            'products' => array_map(
                fn (ProductRequest $product): array => $product->toArray(),
                $this->products
            ),
            'returnUrl' => $this->return_url,
            'completionUrl' => $this->completion_url,
            'allowCoupons' => $this->allow_coupons,
            'coupons' => $this->coupons,
        ];

        if ($this->customer_id !== null) {
            $data['customerId'] = $this->customer_id;
        }

        if ($this->customer instanceof CustomerRequest) {
            $data['customer'] = $this->customer->toArray();
        }

        if ($this->external_id !== null) {
            $data['externalId'] = $this->external_id;
        }

        return $data;
    }
}

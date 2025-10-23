<?php

declare(strict_types=1);

namespace AbacatePay\Billing\Http\Request;

use AbacatePay\Billing\Enum\BillingFrequencyEnum;
use AbacatePay\Billing\Enum\BillingMethodEnum;
use AbacatePay\Customer\Http\Request\CustomerRequest;

final readonly class CreateBillingRequest
{
    /**
     * @param  BillingMethodEnum[]  $methods
     * @param  ProductRequest[]  $products
     * @param  string[]  $coupons
     */
    public function __construct(
        public BillingFrequencyEnum $frequency,
        public array $methods,
        public array $products,
        public string $return_url,
        public string $completion_url,
        public ?string $customerId,
        public ?CustomerRequest $customer,
        public bool $allow_coupons,
        public array $coupons,
        public ?string $externalId
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

        if ($this->customerId !== null) {
            $data['customerId'] = $this->customerId;
        }

        if ($this->customer instanceof CustomerRequest) {
            $data['customer'] = $this->customer->toArray();
        }

        if ($this->externalId !== null) {
            $data['externalId'] = $this->externalId;
        }

        return $data;
    }
}

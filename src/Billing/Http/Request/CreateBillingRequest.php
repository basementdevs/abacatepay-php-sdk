<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Billing\Http\Request;

use Basement\AbacatePay\Billing\Enum\BillingFrequencyEnum;
use Basement\AbacatePay\Billing\Enum\BillingMethodEnum;
use Basement\AbacatePay\Billing\Http\Builder\CreateBillingRequestBuilder;
use Basement\AbacatePay\Customer\Http\Request\CustomerRequest;

final class CreateBillingRequest
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
        public bool $allow_coupons,
        public array $coupons,
        public ?string $customerId,
        public ?CustomerRequest $customer,
        public ?string $externalId
    ) {}

    public static function builder(): CreateBillingRequestBuilder
    {
        return new CreateBillingRequestBuilder;
    }

    public static function oneTime(): CreateBillingRequestBuilder
    {
        return (new CreateBillingRequestBuilder)
            ->frequency(BillingFrequencyEnum::OneTime);
    }

    public static function multipleTimes(): CreateBillingRequestBuilder
    {
        return (new CreateBillingRequestBuilder)
            ->frequency(BillingFrequencyEnum::MultiplePayments);
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

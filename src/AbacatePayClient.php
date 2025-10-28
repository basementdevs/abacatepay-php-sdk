<?php

declare(strict_types=1);

namespace Basement\AbacatePay;

use Basement\AbacatePay\Billing\BillingResource;
use Basement\AbacatePay\Coupon\CouponResource;
use Basement\AbacatePay\Customer\CustomerResource;
use Basement\AbacatePay\Pix\PixResource;
use Basement\AbacatePay\Store\StoreResource;
use Basement\AbacatePay\Withdraw\WithdrawResource;
use GuzzleHttp\Client;

final readonly class AbacatePayClient
{
    private const string BASE_URL = 'https://api.abacatepay.com/v1/';

    private Client $client;

    public function __construct(
        private string $token,
    ) {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->token),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function customer(): CustomerResource
    {
        return new CustomerResource($this->client);
    }

    public function billing(): BillingResource
    {
        return new BillingResource($this->client);
    }

    public function pix(): PixResource
    {
        return new PixResource($this->client);
    }

    public function withdraw(): WithdrawResource
    {
        return new WithdrawResource($this->client);
    }

    public function coupon(): CouponResource
    {
        return new CouponResource($this->client);
    }

    public function store(): StoreResource
    {
        return new StoreResource($this->client);
    }
}

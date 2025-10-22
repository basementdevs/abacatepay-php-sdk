<?php

declare(strict_types=1);

use AbacatePay\Customer\CustomerResource;
use GuzzleHttp\Client;

final class AbacatePayClient
{
    private Client $client;

    public function __construct(
        private readonly string $token,
    ) {
        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer '.$this->token,
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
}
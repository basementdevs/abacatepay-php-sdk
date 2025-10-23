<?php

declare(strict_types=1);

namespace AbacatePay;

use AbacatePay\Customer\CustomerResource;
use GuzzleHttp\Client;

final readonly class AbacatePayClient
{
    private Client $client;

    private const string BASE_URL = 'https://api.abacatepay.com/v1';

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
}

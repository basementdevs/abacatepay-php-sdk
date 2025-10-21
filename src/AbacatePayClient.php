<?php

declare(strict_types=1);

use GuzzleHttp\Client;

class AbacatePayClient
{
    public Client $client;

    public function __construct(public string $token)
    {
        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer '.$token
            ]
        ]);
    }

    public function customer(): CustomerResource
    {
        return new CustomerResource(
            client: $this->client
        );
    }
}
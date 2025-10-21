<?php

declare(strict_types=1);

namespace AbacatePay\Customer;

use Customer\Collection\CustomerCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class CustomerResource
{
    public const string BASE_URI = "https://api.abacatepay.com/v1/customer";

    public function __construct(
        public Client $client,
    ) {
    }

    public function listCustomers(): CustomerCollection
    {
        try {
            $response = $this->client->get(self::BASE_URI."/list");
        } catch (GuzzleException $e) {

        }
        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return CustomerCollection::fromArray($responsePayload['data']);
    }

}
<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Customer;

use Basement\AbacatePay\Customer\Entities\CustomerEntityCollection;
use Basement\AbacatePay\Customer\Http\Request\CreateCustomerRequest;
use Basement\AbacatePay\Customer\Http\Response\CreateCustomerResponse;
use Basement\AbacatePay\Exception\AbacatePayException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final readonly class CustomerResource
{
    public const string BASE_PATH = 'customer';

    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function create(CreateCustomerRequest $request): CreateCustomerResponse
    {
        try {
            $response = $this->client->post(sprintf('%s/create', self::BASE_PATH), [
                'json' => $request->toArray(),
            ]);

            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return CreateCustomerResponse::fromArray($responsePayload);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function list(): CustomerEntityCollection
    {
        try {
            $response = $this->client->get(sprintf('%s/list', self::BASE_PATH));
            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return CustomerEntityCollection::fromArray($responsePayload['data']);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }
}

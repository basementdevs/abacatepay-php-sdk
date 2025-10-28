<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Billing;

use Basement\AbacatePay\Billing\Http\Request\CreateBillingRequest;
use Basement\AbacatePay\Billing\Http\Response\CreateBillingResponse;
use Basement\AbacatePay\Billing\Http\Response\ListBillingResponse;
use Basement\AbacatePay\Exception\AbacatePayException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final readonly class BillingResource
{
    private const string BASE_PATH = 'billing';

    public function __construct(
        private Client $client,
    ) {}

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function create(CreateBillingRequest $request): CreateBillingResponse
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

            return CreateBillingResponse::fromArray($responsePayload);
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
    public function list(): ListBillingResponse
    {
        try {
            $response = $this->client->get(sprintf('%s/list', self::BASE_PATH));

            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return ListBillingResponse::fromArray($responsePayload);

        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }
}

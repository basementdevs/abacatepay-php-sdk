<?php

declare(strict_types=1);

namespace AbacatePay\Store;

use AbacatePay\Exception\AbacatePayException;
use AbacatePay\Store\Http\Response\StoreResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

final readonly class StoreResource
{
    public const string BASE_PATH = 'store';
    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @throws AbacatePayException
     * @throws \JsonException
     */
    public function getStore(): StoreResponse
    {
        try {
            $response = $this->client->get(sprintf('%s/list', self::BASE_PATH));
            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return StoreResponse::fromArray($responsePayload['data']);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }
}

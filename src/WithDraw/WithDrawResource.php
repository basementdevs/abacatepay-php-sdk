<?php

declare(strict_types=1);

namespace AbacatePay\WithDraw;

use AbacatePay\Exception\AbacatePayException;
use AbacatePay\WithDraw\Http\Request\WithDrawRequest;
use AbacatePay\WithDraw\Http\Response\WithDrawResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final readonly class WithDrawResource
{
    public const string BASE_PATH = '/withdraw';

    public function __construct(
        private Client $client
    ) {}

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function withDraw(WithDrawRequest $request): WithDrawResponse
    {
        try {
            $response = $this->client->post(sprintf('%s/create', self::BASE_PATH), [
                'json' => $request->jsonSerialize(),
            ]);

            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return WithDrawResponse::fromArray($responsePayload);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_INTERNAL_SERVER_ERROR => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }
}

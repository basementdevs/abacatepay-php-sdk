<?php

declare(strict_types=1);

namespace AbacatePay\Withdraw;

use AbacatePay\Exception\AbacatePayException;
use AbacatePay\Withdraw\Entities\WithDrawEntityCollection;
use AbacatePay\Withdraw\Http\Request\CreateWithdrawRequest;
use AbacatePay\Withdraw\Http\Request\FindWithDrawRequest;
use AbacatePay\Withdraw\Http\Response\WithdrawResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final readonly class WithdrawResource
{
    public const string BASE_PATH = 'withdraw';

    public function __construct(
        private Client $client
    )
    {
    }

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function withdraw(CreateWithdrawRequest $request): WithdrawResponse
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

            return WithdrawResponse::fromArray($responsePayload);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
    }

    /**
     * @throws AbacatePayException
     */
    public function findWithDraw(FindWithdrawRequest $request): WithdrawResponse
    {
        try {
            $response = $this->client->get(sprintf('%s/get/%s', self::BASE_PATH, $request->externalId));

            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return WithdrawResponse::fromArray($responsePayload);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
    }

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function listWithDraw(): WithDrawEntityCollection
    {
        try {
            $response = $this->client->get(sprintf('%s/list', self::BASE_PATH));

            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return WithDrawEntityCollection::fromArray($responsePayload);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
    }
}

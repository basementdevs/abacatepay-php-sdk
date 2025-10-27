<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Withdraw;

use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Withdraw\Entities\WithdrawEntityCollection;
use Basement\AbacatePay\Withdraw\Http\Request\CreateWithdrawRequest;
use Basement\AbacatePay\Withdraw\Http\Response\WithdrawResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final readonly class WithdrawResource
{
    public const string BASE_PATH = 'withdraw';

    public function __construct(
        private Client $client
    ) {
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
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function findWithdrawById(string $externalId): WithdrawResponse
    {
        try {
            $response = $this->client->get(sprintf('%s/get/%s', self::BASE_PATH, $externalId));

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
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function listWithdraw(): WithdrawEntityCollection
    {
        try {
            $response = $this->client->get(sprintf('%s/list', self::BASE_PATH));

            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return WithdrawEntityCollection::fromArray($responsePayload);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }
}

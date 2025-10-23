<?php

declare(strict_types=1);

namespace AbacatePay\Withdraw;

use AbacatePay\Exception\AbacatePayException;
use AbacatePay\Withdraw\Http\Request\CreateWithdrawRequest;
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
                default => throw new AbacatePayException('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }
    }
}

<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Pix;

use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Pix\Http\Request\CreatePixQrCodeRequest;
use Basement\AbacatePay\Pix\Http\Response\CheckStatusPixQrCodeResponse;
use Basement\AbacatePay\Pix\Http\Response\CreatePixQrCodeResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

final readonly class PixResource
{
    public const string BASE_PATH = 'pixQrCode';

    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function createQrCode(CreatePixQrCodeRequest $request): CreatePixQrCodeResponse
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

            return CreatePixQrCodeResponse::fromArray($responsePayload);
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
    public function checkStatus(string $id): CheckStatusPixQrCodeResponse
    {
        try {
            $response = $this->client->post(sprintf('%s/check', self::BASE_PATH), [
                'query' => [
                    'id' => $id
                ],
            ]);

            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return CheckStatusPixQrCodeResponse::fromArray($responsePayload);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }
}

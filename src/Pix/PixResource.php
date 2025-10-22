<?php

use AbacatePay\Exception\AbacatePayException;
use Entities\CreatePixQrCodeRequest;
use Entities\CreatePixQrCodeResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

final class PixResource
{
    public const string BASE_URI = "https://api.abacatepay.com/v1/pixQrCode";

    public function __construct(
        private readonly Client $client,
    ) {
    }

    /**
     * @throws AbacatePayException
     * @throws JsonException
     */
    public function createQrCode(CreatePixQrCodeRequest $request): CreatePixQrCodeResponse
    {
        try {
            $response = $this->client->post(self::BASE_URI."/create", [
                'json' => $request->toArray(),
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
}
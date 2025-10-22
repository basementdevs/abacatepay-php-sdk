<?php

declare(strict_types=1);

use AbacatePay\Exception\AbacatePayException;
use AbacatePay\Pix\Http\Request\CreatePixQrCodeRequest;
use AbacatePay\Pix\Http\Response\CreatePixQrCodeResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

final readonly class PixResource
{
    public const string BASE_PATH = '/pixQrCode';

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

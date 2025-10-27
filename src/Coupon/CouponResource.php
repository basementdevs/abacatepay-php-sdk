<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Coupon;

use Basement\AbacatePay\Coupon\Entities\CouponCollection;
use Basement\AbacatePay\Coupon\Http\Request\CreateCouponRequest;
use Basement\AbacatePay\Coupon\Http\Response\CouponResponse;
use Basement\AbacatePay\Exception\AbacatePayException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

final readonly class CouponResource
{
    public const string BASE_PATH = 'coupon';

    public function __construct(
        private Client $client,
    ) {
    }

    /**
     * @throws AbacatePayException
     */
    public function create(CreateCouponRequest $request): CouponResponse
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

            return CouponResponse::fromArray($responsePayload);

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
    public function list(): CouponCollection
    {
        try {
            $response = $this->client->get(sprintf('%s/list', self::BASE_PATH));

            $responsePayload = json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return CouponCollection::fromArray($responsePayload);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw AbacatePayException::unauthorized(),
                default => throw new AbacatePayException($e->getMessage(), $e->getCode()),
            };
        }
    }
}

<?php

declare(strict_types=1);

use Basement\AbacatePay\Coupon\CouponResource;
use Basement\AbacatePay\Coupon\Entities\CouponEntity;
use Basement\AbacatePay\Coupon\Enums\CouponDiscountKindEnum;
use Basement\AbacatePay\Coupon\Enums\CouponStatusEnum;
use Basement\AbacatePay\Coupon\Http\Request\CreateCouponRequest;
use Basement\AbacatePay\Coupon\Http\Response\CouponResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

it('should be able to create coupon', function (): void {
    $responseData = [
        'data' => [
            'id' => 'DEYVIN_20',
            'notes' => 'Cupom de desconto pro meu público',
            'maxRedeems' => -1,
            'redeemsCount' => 0,
            'discountKind' => 'PERCENTAGE',
            'discount' => 123,
            'devMode' => true,
            'status' => 'ACTIVE',
            'createdAt' => '2025-05-25T23:43:25.250Z',
            'updatedAt' => '2025-05-25T23:43:25.250Z',
            'metadata' => [
            ],
        ],
    ];

    $handler = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);

    $client = new Client(['handler' => $handler]);

    $resource = new CouponResource(client: $client);
    $requestDto = CreateCouponRequest::make([
        'code' => 'DEYVIN_20',
        'notes' => 'Cupom de desconto pro meu público',
        'maxRedeems' => -1,
        'discountKind' => CouponDiscountKindEnum::Percentage->value,
        'discount' => 123,
        'metadata' => [
            'null' => null,
        ],
    ]);

    $response = $resource->create($requestDto);

    expect($response)->toBeInstanceOf(CouponResponse::class)
        ->and($response->data)->toBeInstanceOf(CouponEntity::class)
        ->and($response->data->id)->toBe('DEYVIN_20')
        ->and($response->data->notes)->toBe('Cupom de desconto pro meu público')
        ->and($response->data->maxRedeems)->toBe(-1)
        ->and($response->data->redeemsCount)->toBe(0)
        ->and($response->data->discountKind)->toBe(CouponDiscountKindEnum::Percentage)
        ->and($response->data->discount)->toBe(123)
        ->and($response->data->devMode)->toBeTrue()
        ->and($response->data->status)->toBe(CouponStatusEnum::Active)
        ->and($response->data->createdAt)->toBe('2025-05-25T23:43:25.250Z')
        ->and($response->data->updatedAt)->toBe('2025-05-25T23:43:25.250Z');
});

it('should be able to list all coupon', function (): void {
    $responseData = [
        'data' => [
            [
                'id' => 'DEYVIN_20',
                'notes' => 'Cupom de desconto pro meu público',
                'maxRedeems' => -1,
                'redeemsCount' => 0,
                'discountKind' => 'PERCENTAGE',
                'discount' => 123,
                'devMode' => true,
                'status' => 'ACTIVE',
                'createdAt' => '2025-05-25T23:43:25.250Z',
                'updatedAt' => '2025-05-25T23:43:25.250Z',
                'metadata' => [
                ],
            ],
            [
                'id' => 'ABACATE',
                'notes' => 'notes for second',
                'maxRedeems' => 10,
                'redeemsCount' => 0,
                'discountKind' => CouponDiscountKindEnum::Fixed->value,
                'discount' => 100,
                'devMode' => true,
                'status' => CouponStatusEnum::Deleted->value,
                'createdAt' => '2025-05-25T23:43:25.250Z',
                'updatedAt' => '2025-05-25T23:43:25.250Z',
                'metadata' => [
                ],
            ],
        ],
    ];

    $handler = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);

    $client = new Client(['handler' => $handler]);

    $resource = new CouponResource(client: $client);

    $response = $resource->list();

    $firstCoupon = $response->all()[0];
    $secondCoupon = $response->all()[1];

    expect($response->count())->toBe(2)
        ->and($firstCoupon)->toBeInstanceOf(CouponEntity::class)
        ->and($firstCoupon->id)->toBe('DEYVIN_20')
        ->and($firstCoupon->notes)->toBe('Cupom de desconto pro meu público')
        ->and($firstCoupon->maxRedeems)->toBe(-1)
        ->and($firstCoupon->redeemsCount)->toBe(0)
        ->and($firstCoupon->discountKind)->toBe(CouponDiscountKindEnum::Percentage)
        ->and($firstCoupon->discount)->toBe(123)
        ->and($firstCoupon->devMode)->toBeTrue()
        ->and($firstCoupon->status)->toBe(CouponStatusEnum::Active)
        ->and($firstCoupon->createdAt)->toBe('2025-05-25T23:43:25.250Z')
        ->and($firstCoupon->updatedAt)->toBe('2025-05-25T23:43:25.250Z')
        ->and($secondCoupon)->toBeInstanceOf(CouponEntity::class)
        ->and($secondCoupon->id)->toBe('ABACATE')
        ->and($secondCoupon->notes)->toBe('notes for second')
        ->and($secondCoupon->maxRedeems)->toBe(10)
        ->and($secondCoupon->redeemsCount)->toBe(0)
        ->and($secondCoupon->discountKind)->toBe(CouponDiscountKindEnum::Fixed)
        ->and($secondCoupon->discount)->toBe(100)
        ->and($secondCoupon->devMode)->toBeTrue()
        ->and($secondCoupon->status)->toBe(CouponStatusEnum::Deleted)
        ->and($secondCoupon->createdAt)->toBe('2025-05-25T23:43:25.250Z')
        ->and($secondCoupon->updatedAt)->toBe('2025-05-25T23:43:25.250Z');
});

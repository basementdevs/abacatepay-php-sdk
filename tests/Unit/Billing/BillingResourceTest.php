<?php

declare(strict_types=1);

use AbacatePay\Billing\BillingResource;
use AbacatePay\Billing\Collection\BillingEntityCollection;
use AbacatePay\Billing\Entities\BillingEntity;
use AbacatePay\Billing\Enum\AbacatePayBillingFrequencyEnum;
use AbacatePay\Billing\Enum\AbacatePayBillingMethodEnum;
use AbacatePay\Billing\Http\Request\CreateBillingRequest;
use AbacatePay\Billing\Http\Request\ProductRequest;
use AbacatePay\Billing\Http\Response\CreateBillingResponse;
use AbacatePay\Billing\Http\Response\ListBillingResponse;
use AbacatePay\Customer\Http\Request\CustomerRequest;
use AbacatePay\Exception\AbacatePayException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->clientMock = Mockery::mock(Client::class);
    $this->billingResource = new BillingResource($this->clientMock);
});

afterEach(function () {
    Mockery::close();
});

it('creates a billing successfully', function () {
    $responseData = [
        'data' => [
            'id' => 'bill_123456',
            'url' => 'https://pay.abacatepay.com/bill-5678',
            'amount' => 4000,
            'status' => 'PENDING',
            'devMode' => true,
            'methods' => ['PIX'],
            'products' => [
                ['id' => 'prod_123456', 'externalId' => 'prod-1234', 'quantity' => 2]
            ],
            'frequency' => 'ONE_TIME',
            'nextBilling' => null,
            'customer' => [
                'id' => 'bill_123456',
                'metadata' => [
                    'name' => 'Daniel Lima',
                    'cellphone' => '(11) 4002-8922',
                    'email' => 'daniel_lima@abacatepay.com',
                    'taxId' => '123.456.789-01'
                ]
            ],
            'allowCoupons' => false,
            'coupons' => []
        ],
        'error' => null
    ];

    $response = new Response(200, [], json_encode($responseData));

    $this->clientMock
        ->shouldReceive('post')
        ->once()
        ->with('/billing/create', Mockery::type('array'))
        ->andReturn($response);

    $request = new CreateBillingRequest(
        frequency: AbacatePayBillingFrequencyEnum::OneTime,
        methods: [AbacatePayBillingMethodEnum::Pix],
        products: [
            new ProductRequest(
                'prod-1234',
                'Assinatura de Programa Fitness',
                'Acesso ao programa fitness premium por 1 mês.',
                2,
                2000
            )
        ],
        return_url: 'https://example.com/billing',
        completion_url: 'https://example.com/completion',
        customer_id: 'cust_abcdefghij',
        customer: new CustomerRequest(
            'id123',
            'Daniel Lima',
            '(11) 4002-8922',
            'daniel_lima@abacatepay.com',
            '123.456.789-01'
        ),
        allow_coupons: false,
        coupons: ['ABKT10', 'ABKT5', 'PROMO10'],
        external_id: 'seu_id_123'
    );

    $result = $this->billingResource->create($request);

    expect($result)->toBeInstanceOf(CreateBillingResponse::class)
        ->and($result->data)->toBeInstanceOf(BillingEntity::class)
        ->and($result->data->id)->toBe('bill_123456')
        ->and($result->data->amount)->toBe(4000)
        ->and($result->error)->toBeNull();
});

it('throws unauthorized exception on create billing', function () {
    $requestMock = Mockery::mock(Request::class);
    $exception = new RequestException('Token de autenticação inválido ou ausente.', $requestMock, new Response(401));

    $this->clientMock
        ->shouldReceive('post')
        ->once()
        ->andThrow($exception);

    $billingRequest = new CreateBillingRequest(
        frequency: AbacatePayBillingFrequencyEnum::OneTime,
        methods: [AbacatePayBillingMethodEnum::Pix],
        products: [],
        return_url: 'https://example.com/billing',
        completion_url: 'https://example.com/completion',
        customer_id: null,
        customer: null,
        allow_coupons: false,
        coupons: [],
        external_id: null
    );

    $this->billingResource->create($billingRequest);
})->throws(AbacatePayException::class, 'Token de autenticação inválido ou ausente.');

it('lists billing successfully', function () {
    $responseData = [
        'data' => [
            [
                'id' => 'bill_123456',
                'url' => 'https://pay.abacatepay.com/bill-5678',
                'amount' => 4000,
                'status' => 'PENDING',
                'devMode' => true,
                'methods' => ['PIX'],
                'products' => [['id' => 'prod_123456', 'externalId' => 'prod-1234', 'quantity' => 2]],
                'frequency' => 'ONE_TIME',
                'nextBilling' => null,
                'customer' => [
                    'id' => 'bill_123456', 'metadata' => [
                        'name' => 'Daniel Lima', 'cellphone' => '(11) 4002-8922',
                        'email' => 'daniel_lima@abacatepay.com', 'taxId' => '123.456.789-01'
                    ]
                ],
                'allowCoupons' => false,
                'coupons' => []
            ]
        ],
        'error' => null
    ];

    $response = new Response(200, [], json_encode($responseData));

    $this->clientMock
        ->shouldReceive('get')
        ->once()
        ->with('/billing/list')
        ->andReturn($response);

    $result = $this->billingResource->list();

    expect($result)->toBeInstanceOf(ListBillingResponse::class)
        ->and($result->data)->toBeInstanceOf(BillingEntityCollection::class)
        ->and(count($result->data))->toBe(1)
        ->and($result->error)->toBeNull();
});

it('throws unauthorized exception on list billing', function () {
    $requestMock = Mockery::mock(Request::class);
    $exception = new RequestException('Token de autenticação inválido ou ausente.', $requestMock, new Response(401));

    $this->clientMock
        ->shouldReceive('get')
        ->once()
        ->andThrow($exception);

    $this->billingResource->list();
})->throws(AbacatePayException::class, 'Token de autenticação inválido ou ausente.');

it('creates billing with minimal data', function () {
    $responseData = [
        'data' => [
            'id' => 'bill_789',
            'url' => 'https://pay.abacatepay.com/bill-789',
            'amount' => 2000,
            'status' => 'PENDING',
            'devMode' => false,
            'methods' => ['PIX'],
            'products' => [],
            'frequency' => 'MULTIPLE_PAYMENTS',
            'nextBilling' => '2025-11-23',
            'customer' => null,
            'allowCoupons' => true,
            'coupons' => []
        ],
        'error' => null
    ];

    $response = new Response(200, [], json_encode($responseData));

    $this->clientMock
        ->shouldReceive('post')
        ->once()
        ->andReturn($response);

    $request = new CreateBillingRequest(
        frequency: AbacatePayBillingFrequencyEnum::MultiplePayments,
        methods: [AbacatePayBillingMethodEnum::Pix],
        products: [],
        return_url: 'https://example.com/return',
        completion_url: 'https://example.com/complete',
        customer_id: null,
        customer: null,
        allow_coupons: true,
        coupons: [],
        external_id: null
    );

    $result = $this->billingResource->create($request);

    expect($result)->toBeInstanceOf(CreateBillingResponse::class)
        ->and($result->data->id)->toBe('bill_789')
        ->and($result->data->allow_coupons)->toBeTrue();
});

it('lists billing returns empty collection', function () {
    $responseData = ['data' => [], 'error' => null];
    $response = new Response(200, [], json_encode($responseData));

    $this->clientMock
        ->shouldReceive('get')
        ->once()
        ->andReturn($response);

    $result = $this->billingResource->list();

    expect($result)->toBeInstanceOf(ListBillingResponse::class)
        ->and(count($result->data))->toBe(0);
});

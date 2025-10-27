<?php

declare(strict_types=1);

use Basement\AbacatePay\Billing\BillingResource;
use Basement\AbacatePay\Billing\Enum\BillingFrequencyEnum;
use Basement\AbacatePay\Billing\Enum\BillingMethodEnum;
use Basement\AbacatePay\Billing\Enum\BillingStatusEnum;
use Basement\AbacatePay\Billing\Http\Request\CreateBillingRequest;
use Basement\AbacatePay\Billing\Http\Request\ProductRequest;
use Basement\AbacatePay\Customer\Http\Request\CustomerRequest;
use Basement\AbacatePay\Exception\AbacatePayException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

it('creates billing completely', function () {
    $responseData = [
        'data' => [
            'id' => 'bill_123456',
            'url' => 'https://pay.abacatepay.com/bill-5678',
            'amount' => 4000,
            'status' => 'PENDING',
            'devMode' => true,
            'methods' => ['PIX'],
            'products' => [['id' => 'prod_123456', 'externalId' => 'prod-1234', 'quantity' => 2]],
            'frequency' => 'ONE_TIME',
            'nextBilling' => 'null',
            'customer' => [
                'id' => 'cust_123456',
                'metadata' => [
                    'name' => 'Daniel Lima',
                    'cellphone' => '(11) 4002-8922',
                    'email' => 'daniel_lima@abacatepay.com',
                    'taxId' => '123.456.789-01',
                ],
            ],
            'allowCoupons' => false,
            'coupons' => [],
        ]
    ];

    $handler = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);

    $client = new Client(['handler' => $handler]);

    $billingResource = new BillingResource(client: $client);

    $request = new CreateBillingRequest(
        frequency: BillingFrequencyEnum::OneTime,
        methods: [BillingMethodEnum::Pix],
        products: [
            new ProductRequest(
                externalId: 'prod-1234',
                name: 'Assinatura de Programa Fitness',
                description: 'Acesso ao programa fitness premium por 1 mês.',
                quantity: 2,
                price: 2000
            ),
        ],
        return_url: 'https://example.com/billing',
        completion_url: 'https://example.com/completion',
        customerId: 'cust_abcdefghij',
        customer: new CustomerRequest(
            id: 'id123',
            name: 'Daniel Lima',
            cellphone: '(11) 4002-8922',
            email: 'daniel_lima@abacatepay.com',
            tax_id: '123.456.789-01'
        ),
        allow_coupons: false,
        coupons: ['ABKT10', 'ABKT5', 'PROMO10'],
        externalId: 'seu_id_123'
    );

    $response = $billingResource->create($request);

    expect($response->data->id)->toBe('bill_123456')
        ->and($response->data->url)->toBe('https://pay.abacatepay.com/bill-5678')
        ->and($response->data->amount)->toBe(4000)
        ->and($response->data->status)->toBe(BillingStatusEnum::Pending)
        ->and($response->data->dev_mode)->toBeTrue()
        ->and($response->data->methods[0])->toBe(BillingMethodEnum::Pix)
        ->and($response->data->frequency)->toBe(BillingFrequencyEnum::OneTime)
        ->and($response->data->next_billing)->toBeNull()
        ->and($response->data->allow_coupons)->toBeFalse()
        ->and($response->data->customer->name)->toBe('Daniel Lima');
});

it('throws exception on unauthorized', function () {
    $handler = new MockHandler([
        new ClientException(
            'Unauthorized',
            new Request('GET', 'test-abacatepay'),
            new Response(401, [], json_encode(['error' => 'Unauthorized'], JSON_THROW_ON_ERROR))
        )
    ]);

    $client = new Client(['handler' => $handler]);

    $billingResource = new BillingResource(client: $client);

    $request = new CreateBillingRequest(
        frequency: BillingFrequencyEnum::OneTime,
        methods: [BillingMethodEnum::Pix],
        products: [],
        return_url: 'https://example.com/return',
        completion_url: 'https://example.com/complete',
        customerId: null,
        customer: null,
        allow_coupons: false,
        coupons: [],
        externalId: null
    );

    expect(fn () => $billingResource->create($request))->toThrow(AbacatePayException::class);
});

it('throws internal server error exception', function () {
    $handler = new MockHandler([
        new ServerException(
            'Internal Server Error',
            new Request('POST', 'test-abacatepay'),
            new Response(500, [], json_encode(['error' => 'server crashed'], JSON_THROW_ON_ERROR))
        )
    ]);

    $client = new Client(['handler' => $handler]);

    $billingResource = new BillingResource(client: $client);

    $request = new CreateBillingRequest(
        frequency: BillingFrequencyEnum::OneTime,
        methods: [BillingMethodEnum::Pix],
        products: [],
        return_url: 'https://example.com/return',
        completion_url: 'https://example.com/complete',
        customerId: null,
        customer: null,
        allow_coupons: false,
        coupons: [],
        externalId: null
    );

    expect(fn () => $billingResource->create($request))
        ->toThrow(AbacatePayException::class, 'Internal Server Error');
});

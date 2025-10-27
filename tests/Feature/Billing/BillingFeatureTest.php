<?php

declare(strict_types=1);

use AbacatePay\AbacatePayClient;
use AbacatePay\Billing\Enum\AbacatePayBillingFrequencyEnum;
use AbacatePay\Billing\Enum\AbacatePayBillingMethodEnum;
use AbacatePay\Billing\Enum\AbacatePayBillingStatusEnum;
use AbacatePay\Billing\Http\Request\CreateBillingRequest;
use AbacatePay\Billing\Http\Request\ProductRequest;
use AbacatePay\Customer\Http\Request\CustomerRequest;
use AbacatePay\Exception\AbacatePayException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
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
                'id' => 'cust_123456', 'metadata' => [
                    'name' => 'Daniel Lima', 'cellphone' => '(11) 4002-8922', 'email' => 'daniel_lima@abacatepay.com',
                    'taxId' => '123.456.789-01'
                ]
            ],
            'allowCoupons' => false,
            'coupons' => []
        ],
        'error' => null
    ];

    $client = new MockHandler([new Response(200, [], json_encode($responseData))]);
    $abacateClient = new AbacatePayClient('test_token');
    $reflection = new ReflectionClass($abacateClient);
    $prop = $reflection->getProperty('client');
    $prop->setValue($abacateClient, new Client(['handler' => HandlerStack::create($client)]));

    $request = new CreateBillingRequest(
        frequency: AbacatePayBillingFrequencyEnum::OneTime,
        methods: [AbacatePayBillingMethodEnum::Pix],
        products: [
            new ProductRequest(
                external_id: 'prod-1234',
                name: 'Assinatura de Programa Fitness',
                description: 'Acesso ao programa fitness premium por 1 mês.',
                quantity: 2,
                price: 2000
            )
        ],
        return_url: 'https://example.com/billing',
        completion_url: 'https://example.com/completion',
        customer_id: 'cust_abcdefghij',
        customer: new CustomerRequest(
            id: 'id123',
            name: 'Daniel Lima',
            cellphone: '(11) 4002-8922',
            email: 'daniel_lima@abacatepay.com',
            tax_id: '123.456.789-01'
        ),
        allow_coupons: false,
        coupons: ['ABKT10', 'ABKT5', 'PROMO10'],
        external_id: 'seu_id_123'
    );

    $response = $abacateClient->billing()->create($request);

    expect($response->data->id)->toBe('bill_123456')
        ->and($response->data->url)->toBe('https://pay.abacatepay.com/bill-5678')
        ->and($response->data->amount)->toBe(4000)
        ->and($response->data->status)->toBe(AbacatePayBillingStatusEnum::Pending)
        ->and($response->data->dev_mode)->toBeTrue()
        ->and($response->data->methods[0])->toBe(AbacatePayBillingMethodEnum::Pix)
        ->and($response->data->frequency)->toBe(AbacatePayBillingFrequencyEnum::OneTime)
        ->and($response->data->next_billing)->toBeNull()
        ->and($response->data->allow_coupons)->toBeFalse()
        ->and($response->data->customer->metadata['name'])->toBe('Daniel Lima');
});

it('throws exception on unauthorized', function () {
    $client = new MockHandler([new Response(401, [], json_encode(['error' => 'Unauthorized']))]);
    $abacateClient = new AbacatePayClient('token');
    $reflection = new \ReflectionClass($abacateClient);
    $prop = $reflection->getProperty('client');
    $prop->setAccessible(true);
    $prop->setValue($abacateClient, new Client(['handler' => HandlerStack::create($client)]));

    $request = new CreateBillingRequest(
        frequency: AbacatePayBillingFrequencyEnum::OneTime,
        methods: [AbacatePayBillingMethodEnum::Pix],
        products: [],
        return_url: 'https://example.com/return',
        completion_url: 'https://example.com/complete',
        customer_id: null,
        customer: null,
        allow_coupons: false,
        coupons: [],
        external_id: null
    );

    expect(fn () => $abacateClient->billing()->create($request))->toThrow(AbacatePayException::class);
});

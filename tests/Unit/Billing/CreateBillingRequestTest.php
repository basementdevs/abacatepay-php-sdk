<?php

declare(strict_types=1);

use AbacatePay\Billing\Enum\BillingFrequencyEnum;
use AbacatePay\Billing\Enum\BillingMethodEnum;
use AbacatePay\Billing\Http\Request\CreateBillingRequest;
use AbacatePay\Billing\Http\Request\ProductRequest;
use AbacatePay\Customer\Http\Request\CustomerRequest;

it('creates request with all fields', function () {
    $products = [
        new ProductRequest(
            externalId: 'prod-1234',
            name: 'Assinatura de Programa Fitness',
            description: 'Acesso ao programa fitness premium por 1 mês.',
            quantity: 2,
            price: 2000
        )
    ];

    $customer = new CustomerRequest(
        id: 'id123',
        name: 'Daniel Lima',
        cellphone: '(11) 4002-8922',
        email: 'daniel_lima@abacatepay.com',
        tax_id: '123.456.789-01'
    );

    $request = new CreateBillingRequest(
        frequency: BillingFrequencyEnum::OneTime,
        methods: [BillingMethodEnum::Pix],
        products: $products,
        return_url: 'https://example.com/billing',
        completion_url: 'https://example.com/completion',
        customerId: 'cust_abcdefghij',
        customer: $customer,
        allow_coupons: false,
        coupons: ['ABKT10', 'ABKT5', 'PROMO10'],
        externalId: 'seu_id_123'
    );

    expect($request->frequency)->toBe(BillingFrequencyEnum::OneTime)
        ->and($request->methods)->toHaveCount(1)
        ->and($request->methods[0])->toBe(BillingMethodEnum::Pix)
        ->and($request->products)->toHaveCount(1)
        ->and($request->return_url)->toBe('https://example.com/billing')
        ->and($request->completion_url)->toBe('https://example.com/completion')
        ->and($request->customerId)->toBe('cust_abcdefghij')
        ->and($request->customer)->toBeInstanceOf(CustomerRequest::class)
        ->and($request->allow_coupons)->toBeFalse()
        ->and($request->coupons)->toHaveCount(3)
        ->and($request->externalId)->toBe('seu_id_123');
});

it('converts to array with all fields', function () {
    $products = [
        new ProductRequest(
            externalId: 'prod-1234',
            name: 'Product Name',
            description: 'Product Description',
            quantity: 2,
            price: 2000
        )
    ];

    $customer = new CustomerRequest(
        id: 'id123',
        name: 'Daniel Lima',
        cellphone: '(11) 4002-8922',
        email: 'daniel_lima@abacatepay.com',
        tax_id: '123.456.789-01'
    );

    $request = new CreateBillingRequest(
        frequency: BillingFrequencyEnum::MultiplePayments,
        methods: [BillingMethodEnum::Pix, BillingMethodEnum::Card],
        products: $products,
        return_url: 'https://example.com/return',
        completion_url: 'https://example.com/complete',
        customerId: 'cust_123',
        customer: $customer,
        allow_coupons: true,
        coupons: ['PROMO10'],
        externalId: 'ext_123'
    );

    $array = $request->toArray();

    expect($array)->toHaveKeys([
        'frequency', 'methods', 'products', 'returnUrl', 'completionUrl',
        'customerId', 'customer', 'allowCoupons', 'coupons', 'externalId'
    ])->and($array['frequency'])->toBe(BillingFrequencyEnum::MultiplePayments)
        ->and($array['methods'])->toHaveCount(2)
        ->and($array['allowCoupons'])->toBeTrue()
        ->and($array['customerId'])->toBe('cust_123')
        ->and($array['externalId'])->toBe('ext_123');
});

it('converts to array without optional fields', function () {
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

    $array = $request->toArray();

    expect($array)->not->toHaveKeys(['customerId', 'customer', 'externalId'])
        ->and($array)->toHaveKeys([
            'frequency', 'methods', 'products', 'returnUrl', 'completionUrl', 'allowCoupons', 'coupons'
        ]);
});

it('converts to array with customerId only', function () {
    $request = new CreateBillingRequest(
        frequency: BillingFrequencyEnum::OneTime,
        methods: [BillingMethodEnum::Pix],
        products: [],
        return_url: 'https://example.com/return',
        completion_url: 'https://example.com/complete',
        customerId: 'cust_existing',
        customer: null,
        allow_coupons: false,
        coupons: [],
        externalId: null
    );

    $array = $request->toArray();

    expect($array)->toHaveKey('customerId')
        ->not->toHaveKey('customer')
        ->and($array['customerId'])->toBe('cust_existing');
});

it('converts to array with customer object only', function () {
    $customer = new CustomerRequest(
        id: 'id123',
        name: 'John Doe',
        cellphone: '(11) 99999-9999',
        email: 'john@example.com',
        tax_id: '987.654.321-00'
    );

    $request = new CreateBillingRequest(
        frequency: BillingFrequencyEnum::OneTime,
        methods: [BillingMethodEnum::Pix],
        products: [],
        return_url: 'https://example.com/return',
        completion_url: 'https://example.com/complete',
        customerId: null,
        customer: $customer,
        allow_coupons: false,
        coupons: [],
        externalId: null
    );

    $array = $request->toArray();

    expect($array)->not->toHaveKey('customerId')
        ->toHaveKey('customer')
        ->and($array['customer'])->toBeArray();
});

it('converts to array with multiple products', function () {
    $products = [
        new ProductRequest('prod-1', 'Product 1', 'Description 1', 1, 1000),
        new ProductRequest('prod-2', 'Product 2', 'Description 2', 2, 2000),
        new ProductRequest('prod-3', 'Product 3', 'Description 3', 3, 3000),
    ];

    $request = new CreateBillingRequest(
        frequency: BillingFrequencyEnum::OneTime,
        methods: [BillingMethodEnum::Pix],
        products: $products,
        return_url: 'https://example.com/return',
        completion_url: 'https://example.com/complete',
        customerId: null,
        customer: null,
        allow_coupons: false,
        coupons: [],
        externalId: null
    );

    $array = $request->toArray();

    expect($array['products'])->toHaveCount(3)
        ->and($array['products'][0])->toBeArray()
        ->and($array['products'][1])->toBeArray()
        ->and($array['products'][2])->toBeArray();
});

<?php

declare(strict_types=1);

use Basement\AbacatePay\Billing\Entities\BillingEntity;
use Basement\AbacatePay\Billing\Entities\BillingProductEntity;
use Basement\AbacatePay\Billing\Enum\BillingFrequencyEnum;
use Basement\AbacatePay\Billing\Enum\BillingMethodEnum;
use Basement\AbacatePay\Billing\Enum\BillingStatusEnum;
use Basement\AbacatePay\Customer\Entities\CustomerEntity;

it('creates billing entity from array', function () {
    $data = [
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
                'taxId' => '123.456.789-01'
            ]
        ],
        'allowCoupons' => false,
        'coupons' => []
    ];

    $entity = BillingEntity::fromArray($data);

    expect($entity->id)->toBe('bill_123456')
        ->and($entity->url)->toBe('https://pay.abacatepay.com/bill-5678')
        ->and($entity->amount)->toBe(4000)
        ->and($entity->methods[0])->toBe(BillingMethodEnum::Pix)
        ->and($entity->products)->toHaveCount(1)
        ->and($entity->products[0])->toBeInstanceOf(BillingProductEntity::class)
        ->and($entity->frequency)->toBe(BillingFrequencyEnum::OneTime)
        ->and($entity->next_billing)->toBeNull()
        ->and($entity->customer)->toBeInstanceOf(CustomerEntity::class)
        ->and($entity->allow_coupons)->toBeFalse()
        ->and($entity->coupons)->toBeEmpty();
});

it('handles null next billing', function () {
    $data = [
        'id' => 'bill_001',
        'url' => 'https://pay.abacatepay.com/bill-001',
        'amount' => 1000,
        'status' => 'PENDING',
        'devMode' => false,
        'methods' => ['PIX'],
        'products' => [],
        'frequency' => 'ONE_TIME',
        'nextBilling' => 'null',
        'customer' => null,
        'allowCoupons' => true,
        'coupons' => []
    ];

    $entity = BillingEntity::fromArray($data);
    expect($entity->next_billing)->toBeNull();
});

it('sets actual next billing date', function () {
    $data = [
        'id' => 'bill_002',
        'url' => 'https://pay.abacatepay.com/bill-002',
        'amount' => 5000,
        'status' => 'PENDING',
        'devMode' => false,
        'methods' => ['CARD'],
        'products' => [],
        'frequency' => 'MULTIPLE_PAYMENTS',
        'nextBilling' => '2025-11-23',
        'customer' => null,
        'allowCoupons' => false,
        'coupons' => []
    ];

    $entity = BillingEntity::fromArray($data);
    expect($entity->next_billing)->toBe('2025-11-23')
        ->and($entity->frequency)->toBe(BillingFrequencyEnum::MultiplePayments);
});

it('handles multiple methods and coupons', function () {
    $data = [
        'id' => 'bill_003',
        'url' => 'https://pay.abacatepay.com/bill-003',
        'amount' => 3000,
        'status' => 'PENDING',
        'devMode' => true,
        'methods' => ['PIX', 'CARD', 'PIX'],
        'products' => [],
        'frequency' => 'ONE_TIME',
        'nextBilling' => 'null',
        'customer' => null,
        'allowCoupons' => true,
        'coupons' => ['PROMO10', 'PROMO20']
    ];

    $entity = BillingEntity::fromArray($data);
    expect($entity->methods)->toHaveCount(3)
        ->and($entity->methods[0])->toBe(BillingMethodEnum::Pix)
        ->and($entity->methods[1])->toBe(BillingMethodEnum::Card)
        ->and($entity->methods[2])->toBe(BillingMethodEnum::Pix)
        ->and($entity->coupons)->toHaveCount(2);
});

it('json serialization works', function () {
    $data = [
        'id' => 'bill_004',
        'url' => 'https://pay.abacatepay.com/bill-004',
        'amount' => 2500,
        'status' => 'PAID',
        'devMode' => false,
        'methods' => ['PIX'],
        'products' => [['id' => 'prod_001', 'externalId' => 'ext_001', 'quantity' => 1]],
        'frequency' => 'ONE_TIME',
        'nextBilling' => '2026-10-23',
        'customer' => [
            'id' => 'cust_001',
            'metadata' => [
                'name' => 'Test User', 'email' => 'test@example.com', 'cellphone' => '(11) 4002-8922',
                'taxId' => '123.456.789-01'
            ]
        ],
        'allowCoupons' => true,
        'coupons' => ['DISCOUNT']
    ];

    $entity = BillingEntity::fromArray($data);
    $serialized = $entity->jsonSerialize();

    expect($serialized['id'])->toBe('bill_004')
        ->and($serialized['amount'])->toBe(2500)
        ->and($serialized['status'])->toBe('PAID')
        ->and($serialized['devMode'])->toBeFalse()
        ->and($serialized['methods'][0])->toBe('PIX')
        ->and($serialized['frequency'])->toBe('ONE_TIME')
        ->and($serialized['nextBilling'])->toBe('2026-10-23')
        ->and($serialized['allowCoupons'])->toBeTrue()
        ->and($serialized['products'])->toHaveCount(1)
        ->and($serialized['customer'])->not->toBeNull();
});

it('handles entity without customer', function () {
    $data = [
        'id' => 'bill_005',
        'url' => 'https://pay.abacatepay.com/bill-005',
        'amount' => 1500,
        'status' => 'PENDING',
        'devMode' => true,
        'methods' => ['PIX'],
        'products' => [],
        'frequency' => 'ONE_TIME',
        'nextBilling' => 'null',
        'customer' => null,
        'allowCoupons' => false,
        'coupons' => []
    ];

    $entity = BillingEntity::fromArray($data);
    $serialized = $entity->jsonSerialize();

    expect($entity->customer)->toBeNull()
        ->and($serialized['customer'])->toBeNull();
});

it('handles empty arrays', function () {
    $data = [
        'id' => 'bill_006',
        'url' => 'https://pay.abacatepay.com/bill-006',
        'amount' => 0,
        'status' => 'CANCELLED',
        'devMode' => false,
        'methods' => [],
        'products' => [],
        'frequency' => 'ONE_TIME',
        'nextBilling' => 'null',
        'customer' => null,
        'allowCoupons' => false,
        'coupons' => []
    ];

    $entity = BillingEntity::fromArray($data);

    expect($entity->methods)->toBeEmpty()
        ->and($entity->products)->toBeEmpty()
        ->and($entity->coupons)->toBeEmpty()
        ->and($entity->status)->toBe(BillingStatusEnum::Cancelled);
});

it('handles all statuses', function () {
    $statuses = ['PENDING', 'PAID', 'CANCELLED', 'REFUNDED', 'EXPIRED'];

    foreach ($statuses as $status) {
        $data = [
            'id' => 'bill_status_'.$status,
            'url' => 'https://pay.abacatepay.com/bill',
            'amount' => 1000,
            'status' => $status,
            'devMode' => false,
            'methods' => ['PIX'],
            'products' => [],
            'frequency' => 'ONE_TIME',
            'nextBilling' => 'null',
            'customer' => null,
            'allowCoupons' => false,
            'coupons' => []
        ];

        $entity = BillingEntity::fromArray($data);
        expect($entity->status)->toBe(BillingStatusEnum::from($status));
    }
});

it('handles all frequencies', function () {
    $frequencies = ['ONE_TIME', 'MULTIPLE_PAYMENTS'];

    foreach ($frequencies as $frequency) {
        $data = [
            'id' => 'bill_freq_'.$frequency,
            'url' => 'https://pay.abacatepay.com/bill',
            'amount' => 1000,
            'status' => 'PENDING',
            'devMode' => false,
            'methods' => ['PIX'],
            'products' => [],
            'frequency' => $frequency,
            'nextBilling' => $frequency !== 'ONE_TIME' ? '2025-11-23' : 'null',
            'customer' => null,
            'allowCoupons' => false,
            'coupons' => []
        ];

        $entity = BillingEntity::fromArray($data);
        expect($entity->frequency)->toBe(BillingFrequencyEnum::from($frequency));
    }
});

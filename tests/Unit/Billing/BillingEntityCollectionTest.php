<?php

declare(strict_types=1);
use Basement\AbacatePay\Billing\Collection\BillingEntityCollection;
use Basement\AbacatePay\Billing\Entities\BillingEntity;
use Basement\AbacatePay\Billing\Enum\BillingFrequencyEnum;

beforeEach(function () {
    $this->createBillingData = function (string $id, int $amount, string $status): array {
        return [
            'id' => $id,
            'url' => "https://pay.abacatepay.com/{$id}",
            'amount' => $amount,
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
    };
});

it('creates empty collection', function () {
    $collection = new BillingEntityCollection();
    expect($collection)->toHaveCount(0)
        ->and($collection->all())->toBeEmpty();
});

it('adds billing entity to collection', function () {
    $collection = new BillingEntityCollection();
    $entity = BillingEntity::fromArray(($this->createBillingData)('bill_001', 1000, 'PENDING'));
    $collection->add($entity);

    expect($collection)->toHaveCount(1)
        ->and($collection->all()[0]->id)->toBe('bill_001');
});

it('adds multiple billing entities', function () {
    $collection = new BillingEntityCollection();

    $entity1 = BillingEntity::fromArray(($this->createBillingData)('bill_001', 1000, 'PENDING'));
    $entity2 = BillingEntity::fromArray(($this->createBillingData)('bill_002', 2000, 'PAID'));
    $entity3 = BillingEntity::fromArray(($this->createBillingData)('bill_003', 3000, 'CANCELLED'));

    $collection->add($entity1);
    $collection->add($entity2);
    $collection->add($entity3);

    $items = $collection->all();
    expect($collection)->toHaveCount(3)
        ->and($items[0]->id)->toBe('bill_001')
        ->and($items[1]->id)->toBe('bill_002')
        ->and($items[2]->id)->toBe('bill_003');
});

it('creates collection from array', function () {
    $data = [
        ($this->createBillingData)('bill_001', 1000, 'PENDING'),
        ($this->createBillingData)('bill_002', 2000, 'PAID'),
    ];

    $collection = BillingEntityCollection::fromArray($data);
    $items = $collection->all();

    expect($collection)->toHaveCount(2)
        ->and($items[0]->id)->toBe('bill_001')
        ->and($items[0]->amount)->toBe(1000)
        ->and($items[1]->id)->toBe('bill_002')
        ->and($items[1]->amount)->toBe(2000);
});

it('creates collection with items in constructor', function () {
    $entity1 = BillingEntity::fromArray(($this->createBillingData)('bill_001', 1000, 'PENDING'));
    $entity2 = BillingEntity::fromArray(($this->createBillingData)('bill_002', 2000, 'PAID'));
    $collection = new BillingEntityCollection([$entity1, $entity2]);

    expect($collection)->toHaveCount(2);
});

it('iterates over collection', function () {
    $data = [
        ($this->createBillingData)('bill_001', 1000, 'PENDING'),
        ($this->createBillingData)('bill_002', 2000, 'PAID'),
        ($this->createBillingData)('bill_003', 3000, 'CANCELLED'),
    ];

    $collection = BillingEntityCollection::fromArray($data);
    $ids = [];
    foreach ($collection as $billing) {
        expect($billing)->toBeInstanceOf(BillingEntity::class);
        $ids[] = $billing->id;
    }

    expect($ids)->toBe(['bill_001', 'bill_002', 'bill_003']);
});

it('counts collection', function () {
    $collection = new BillingEntityCollection();
    expect($collection)->toHaveCount(0)
        ->and($collection->count())->toBe(0);

    $collection->add(BillingEntity::fromArray(($this->createBillingData)('bill_001', 1000, 'PENDING')));
    expect($collection)->toHaveCount(1);

    $collection->add(BillingEntity::fromArray(($this->createBillingData)('bill_002', 2000, 'PAID')));
    expect($collection)->toHaveCount(2);

    $collection->add(BillingEntity::fromArray(($this->createBillingData)('bill_003', 3000, 'CANCELLED')));
    expect($collection)->toHaveCount(3);
});

it('get all returns array of entities', function () {
    $data = [
        ($this->createBillingData)('bill_001', 1000, 'PENDING'),
        ($this->createBillingData)('bill_002', 2000, 'PAID'),
    ];

    $collection = BillingEntityCollection::fromArray($data);
    $all = $collection->all();

    expect($all)->toBeArray()
        ->and($all)->toHaveCount(2)
        ->and($all[0])->toBeInstanceOf(BillingEntity::class)
        ->and($all[1])->toBeInstanceOf(BillingEntity::class);
});

it('collection with complex billing data', function () {
    $data = [
        [
            'id' => 'bill_complex',
            'url' => 'https://pay.abacatepay.com/bill-complex',
            'amount' => 15000,
            'status' => 'PAID',
            'devMode' => true,
            'methods' => ['PIX', 'CARD'],
            'products' => [
                ['id' => 'prod_001', 'externalId' => 'ext_001', 'quantity' => 2],
                ['id' => 'prod_002', 'externalId' => 'ext_002', 'quantity' => 1],
            ],
            'frequency' => 'MULTIPLE_PAYMENTS',
            'nextBilling' => '2025-11-23',
            'customer' => [
                'id' => 'cust_001',
                'metadata' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'cellphone' => '(11) 99999-9999',
                    'taxId' => '123.456.789-00'
                ]
            ],
            'allowCoupons' => true,
            'coupons' => ['PROMO10', 'PROMO20']
        ]
    ];

    $collection = BillingEntityCollection::fromArray($data);
    $billing = $collection->all()[0];

    expect($collection)->toHaveCount(1)
        ->and($billing->id)->toBe('bill_complex')
        ->and($billing->amount)->toBe(15000)
        ->and($billing->methods)->toHaveCount(2)
        ->and($billing->products)->toHaveCount(2)
        ->and($billing->frequency)->toBe(BillingFrequencyEnum::MultiplePayments)
        ->and($billing->customer)->not->toBeNull()
        ->and($billing->allow_coupons)->toBeTrue()
        ->and($billing->coupons)->toHaveCount(2);
});

it('creates empty collection from array', function () {
    $collection = BillingEntityCollection::fromArray([]);
    expect($collection)->toHaveCount(0)
        ->and($collection->all())->toBeEmpty();
});

it('collection maintains order', function () {
    $amounts = [1000, 5000, 2000, 3000, 4000];
    $data = [];
    foreach ($amounts as $index => $amount) {
        $data[] = ($this->createBillingData)("bill_{$index}", $amount, 'PENDING');
    }

    $collection = BillingEntityCollection::fromArray($data);
    $items = $collection->all();

    foreach ($items as $index => $item) {
        expect($item->amount)->toBe($amounts[$index]);
    }
});

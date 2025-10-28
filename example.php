<?php

declare(strict_types=1);

use Basement\AbacatePay\AbacatePayClient;
use Basement\AbacatePay\Billing\Http\Request\CreateBillingRequest;
use Basement\AbacatePay\Billing\Http\Request\ProductRequest;

require __DIR__.'/vendor/autoload.php';

$client = new AbacatePayClient('test_key');

$response = $client->billing()
    ->create(
        CreateBillingRequest::multipleTimes()
            ->creditCard()
            ->completionUrl('https://fodase.com')
            ->returnUrl('https://google.com')
            ->forCustomerId('cust_abc123412312312')
            ->externalId('some-amazing-key')
            ->addProduct(new ProductRequest('some-amazing-product', 't-shirt', 'def a tshirt', 1, 1337_00))
            ->build()
    );

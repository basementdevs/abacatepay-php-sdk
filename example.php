<?php

declare(strict_types=1);

use Basement\AbacatePay\AbacatePayClient;
use Basement\AbacatePay\Billing\Http\Request\CreateBillingRequest;
use Basement\AbacatePay\Billing\Http\Request\ProductRequest;
use Basement\AbacatePay\Customer\Http\Request\CreateCustomerRequest;

require __DIR__.'/vendor/autoload.php';

$client = new AbacatePayClient('token');

$customer = $client->customer()->create(
    CreateCustomerRequest::builder()
        ->email('fulano@email.com')
        ->cellphone('(11) 4002-8922')
        ->name('fulano')
        ->taxId('209.206.850-48')
        ->build());


$response = $client->billing()
    ->create(
        CreateBillingRequest::oneTime()
            ->pix()
            ->completionUrl('https://google.com')
            ->returnUrl('https://google.com')
            ->forCustomerId($customer->data->id)
            ->externalId((string) time())
            ->addProduct(ProductRequest::builder()
                ->name('some-amazing-product')
                ->description('fodase')
                ->quantity(1)
                ->externalId((string) time())
                ->price(1050)
                ->build())
            ->build()
    );


var_dump($response);

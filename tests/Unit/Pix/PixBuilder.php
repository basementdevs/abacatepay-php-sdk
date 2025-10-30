<?php

declare(strict_types=1);

use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Pix\Http\Request\CreatePixQrCodeRequest;
use Basement\AbacatePay\Pix\Http\Request\PixCustomerRequest;
use Basement\AbacatePay\Pix\Http\Request\PixMetadataRequest;

it('builds a valid CreatePixQrCodeRequest', function () {
    $customer = PixCustomerRequest::builder()
        ->name('Maria')
        ->cellphone('+5511999999999')
        ->email('maria@email.com')
        ->taxId('12345678900')
        ->build();

    $metadata = PixMetadataRequest::builder()
        ->externalId('order-123')
        ->build();

    $request = CreatePixQrCodeRequest::builder()
        ->amount(10000)
        ->expiresIn(3600)
        ->description('Pagamento de assinatura')
        ->customer($customer)
        ->metadata($metadata)
        ->build();

    expect($request)
        ->toBeInstanceOf(CreatePixQrCodeRequest::class)
        ->and($request->amount)->toBe(10000)
        ->and($request->description)->toBe('Pagamento de assinatura')
        ->and($request->customer->name)->toBe('Maria')
        ->and($request->metadata->externalId)->toBe('order-123')
        ->and($customer->toArray())->toBe([
            'name' => 'Maria',
            'cellphone' => '+5511999999999',
            'email' => 'maria@email.com',
            'taxId' => '12345678900',
        ])
        ->and($metadata->toArray())->toBe([
            'externalId' => 'order-123',
        ])
        ->and($request->toArray())->toBe([
            'amount' => 10000,
            'expiresIn' => 3600,
            'description' => 'Pagamento de assinatura',
            'customer' => [
                'name' => 'Maria',
                'cellphone' => '+5511999999999',
                'email' => 'maria@email.com',
                'taxId' => '12345678900',
            ],
            'metadata' => [
                'externalId' => 'order-123',
            ],
        ]);
});

it('throws AbacatePayException when missing required fields', function () {
    CreatePixQrCodeRequest::builder()->build();
})->throws(AbacatePayException::class, 'Missing required fields');

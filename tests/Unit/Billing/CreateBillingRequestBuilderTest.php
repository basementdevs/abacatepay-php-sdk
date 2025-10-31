<?php

declare(strict_types=1);

use Basement\AbacatePay\Billing\Enum\BillingFrequencyEnum;
use Basement\AbacatePay\Billing\Enum\BillingMethodEnum;
use Basement\AbacatePay\Billing\Http\Request\CreateBillingRequest;
use Basement\AbacatePay\Billing\Http\Request\ProductRequest;
use Basement\AbacatePay\Customer\Http\Request\CustomerRequest;
use Basement\AbacatePay\Exception\AbacatePayException;

beforeEach(function () {
    $this->product = ProductRequest::builder()
        ->externalId('prod-123')
        ->name('Curso PHP')
        ->description('Curso completo de PHP moderno')
        ->quantity(1)
        ->price(15000)
        ->build();

    $this->customer = CustomerRequest::builder()
        ->id('cust_abc123')
        ->name('João Silva')
        ->cellphone('+5511999999999')
        ->email('joao@email.com')
        ->taxId('12345678900')
        ->build();
});

it('should build a valid CreateBillingRequest', function () {
    $request = CreateBillingRequest::oneTime()
        ->pix()
        ->returnUrl('https://retorno.exemplo.com')
        ->completionUrl('https://finalizacao.exemplo.com')
        ->addProduct($this->product)
        ->forCustomer($this->customer)
        ->build();

    expect($request)
        ->toBeInstanceOf(CreateBillingRequest::class)
        ->and($request->frequency)->toBe(BillingFrequencyEnum::OneTime)
        ->and($request->methods)->toBe([BillingMethodEnum::Pix])
        ->and($request->products)->toHaveCount(1)
        ->and($request->customer->id)->toBe('cust_abc123')
        ->and($request->allow_coupons)->toBeFalse()
        ->and($request->coupons)->toBe([]);
});

it('should throw AbacatePayException when required fields are missing', function () {
    expect(fn() => CreateBillingRequest::builder()->build())
        ->toThrow(AbacatePayException::class, 'Missing required fields');
});

it('should throw AbacatePayException when both customer and customerId are set', function () {
    $builder = CreateBillingRequest::oneTime()
        ->pix()
        ->returnUrl('https://retorno.exemplo.com')
        ->completionUrl('https://finalizacao.exemplo.com')
        ->addProduct($this->product)
        ->forCustomer($this->customer)
        ->forCustomerId('cust_conflict');

    expect(fn() => $builder->build())
        ->toThrow(InvalidArgumentException::class);
});

it('should allow optional fields to be omitted', function () {
    $request = CreateBillingRequest::oneTime()
        ->pix()
        ->returnUrl('https://retorno.exemplo.com')
        ->completionUrl('https://finalizacao.exemplo.com')
        ->addProduct($this->product)
        ->build();

    expect($request)
        ->toBeInstanceOf(CreateBillingRequest::class)
        ->and($request->externalId)->toBeNull()
        ->and($request->customerId)->toBeNull()
        ->and($request->customer)->toBeNull()
        ->and($request->coupons)->toBe([])
        ->and($request->allow_coupons)->toBeFalse();
});

it('should accept multiple methods and products', function () {
    $product2 = ProductRequest::builder()
        ->externalId('prod-456')
        ->name('Curso Laravel')
        ->description('Aprenda Laravel com exemplos práticos')
        ->quantity(1)
        ->price(18000)
        ->build();

    $request = CreateBillingRequest::multipleTimes()
        ->methods(BillingMethodEnum::Card, BillingMethodEnum::Pix)
        ->returnUrl('https://retorno.exemplo.com')
        ->completionUrl('https://finalizacao.exemplo.com')
        ->products($this->product, $product2)
        ->build();

    expect($request->methods)->toBe([BillingMethodEnum::Card, BillingMethodEnum::Pix])
        ->and($request->products)->toHaveCount(2);
});

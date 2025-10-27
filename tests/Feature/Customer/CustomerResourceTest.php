<?php

declare(strict_types=1);

use Basement\AbacatePay\Customer\CustomerResource;
use Basement\AbacatePay\Customer\Http\Request\CreateCustomerRequest;
use Basement\AbacatePay\Exception\AbacatePayException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->requestDto = new CreateCustomerRequest(
        name: 'Daniel Lima',
        cellphone: '(11) 4002-8922',
        email: 'daniel_lima@abacatepay.com',
        taxId: '123.456.789-01',
    );
});

it('should create a customer', function (): void {
    $data = [
        'data' => [
            'id' => 'bill_123456',
            'metadata' => [
                'name' => 'Daniel Lima',
                'cellphone' => '(11) 4002-8922',
                'email' => 'daniel_lima@abacatepay.com',
                'taxId' => '123.456.789-01',
            ],
        ],
    ];

    $handler = new MockHandler([
        new Response(200, [], json_encode($data)),
    ]);

    $client = new Client(['handler' => $handler]);
    $resource = new CustomerResource(client: $client);

    $response = $resource->create($this->requestDto);

    expect($response->data->id)->toBe('bill_123456')
        ->and($response->data->name)->toBe('Daniel Lima')
        ->and($response->data->email)->toBe('daniel_lima@abacatepay.com')
        ->and($response->data->cellphone)->tobe('(11) 4002-8922')
        ->and($response->data->tax_id)->tobe('123.456.789-01');
});

it('should throw unauthorized exception', function () {
    $handler = new MockHandler([
        new ClientException(
            'Unauthorized',
            new Request('GET', 'test-abacatepay'),
            new Response(401, [], json_encode(['error' => 'Unauthorized'], JSON_THROW_ON_ERROR))
        )
    ]);

    $client = new Client(['handler' => $handler]);
    $resource = new CustomerResource(client: $client);

    expect(fn () => $resource->create($this->requestDto))
        ->toThrow(AbacatePayException::class, 'Token de autenticação inválido ou ausente.');
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
    $resource = new CustomerResource(client: $client);

    expect(fn () => $resource->create($this->requestDto))
        ->toThrow(AbacatePayException::class, 'Internal Server Error');
});

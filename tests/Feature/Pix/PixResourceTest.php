<?php

declare(strict_types=1);

use Basement\AbacatePay\Billing\Enum\BillingStatusEnum;
use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Pix\Http\Request\CreatePixQrCodeRequest;
use Basement\AbacatePay\Pix\Http\Request\PixCustomerRequest;
use Basement\AbacatePay\Pix\Http\Request\PixMetadataRequest;
use Basement\AbacatePay\Pix\Http\Response\CreatePixQrCodeResponse;
use Basement\AbacatePay\Pix\PixResource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->requestDto = CreatePixQrCodeRequest::builder()
        ->amount(100)
        ->expiresIn(10)
        ->description('abacate description')
        ->customer(
            PixCustomerRequest::builder()
                ->name('customer name')
                ->cellphone('11982516627')
                ->email('joe@doe.com')
                ->taxId('123.456.789-01')
                ->build()
        )
        ->metadata(
            PixMetadataRequest::builder()
                ->externalId('customer-id-123')
                ->build()
        )
        ->build();
});

it('should be able to create a Qr for pix', function (): void {

    $responseData = [
        'data' => [
            'id' => 'pix_char_123456',
            'amount' => 100,
            'status' => 'PENDING',
            'devMode' => true,
            'brCode' => '00020101021226950014br.gov.bcb.pix',
            'brCodeBase64' => 'data:image/png;base64,iVBORw0KGgoAAA',
            'platformFee' => 80,
            'createdAt' => '2025-03-24T21:50:20.772Z',
            'updatedAt' => '2025-03-24T21:50:20.772Z',
            'expiresAt' => '2025-03-25T21:50:20.772Z',
        ],
    ];

    $handler = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    $client = new Client(['handler' => $handler]);

    $resource = new PixResource(client: $client);

    $response = $resource->createQrCode($this->requestDto);

    expect($response)->toBeInstanceOf(CreatePixQrCodeResponse::class)
        ->and($response->data->id)->toBe('pix_char_123456')
        ->and($response->data->amount)->toBe(100)
        ->and($response->data->status)->toBe(BillingStatusEnum::Pending)
        ->and($response->data->devMode)->toBeTrue()
        ->and($response->data->brCode)->tobe('00020101021226950014br.gov.bcb.pix')
        ->and($response->data->brCodeBase64)->toBe('data:image/png;base64,iVBORw0KGgoAAA')
        ->and($response->data->platformFee)->toBe(80)
        ->and($response->data->createdAt)->toBe('2025-03-24T21:50:20.772Z')
        ->and($response->data->updatedAt)->toBe('2025-03-24T21:50:20.772Z')
        ->and($response->data->expiresAt)->toBe('2025-03-25T21:50:20.772Z');
});

it('should throw exception when anything goes wrong', function () {
    $handler = new MockHandler([
        new ClientException(
            'Unauthorized',
            new Request('GET', 'test-abacatepay'),
            new Response(401, [], json_encode(['error' => 'Unauthorized'], JSON_THROW_ON_ERROR))
        ),
    ]);

    $client = new Client(['handler' => $handler]);
    $resource = new PixResource(client: $client);

    expect(fn() => $resource->createQrCode($this->requestDto))
        ->toThrow(AbacatePayException::class, 'Token de autenticação inválido ou ausente.');
});

<?php

declare(strict_types=1);

use Basement\AbacatePay\Exception\AbacatePayException;
use Basement\AbacatePay\Withdraw\Enums\WithdrawKindEnum;
use Basement\AbacatePay\Withdraw\Enums\WithdrawMethodsEnum;
use Basement\AbacatePay\Withdraw\Enums\WithdrawPixTypeEnum;
use Basement\AbacatePay\Withdraw\Enums\WithdrawStatusEnum;
use Basement\AbacatePay\Withdraw\Http\Request\CreateWithdrawRequest;
use Basement\AbacatePay\Withdraw\Http\Request\WithdrawPixRequest;
use Basement\AbacatePay\Withdraw\WithdrawResource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->requestDto = new CreateWithdrawRequest(
        externalId: 'tran_1234567890abcdef',
        method: WithdrawMethodsEnum::Pix,
        amount: 100,
        pix: new WithdrawPixRequest(WithdrawPixTypeEnum::Cpf, '123.456.789-10'),
        description: 'description',
    );
    $this->responseData = [
        'data' => [
            'id' => 'tran_1234567890abcdef',
            'status' => WithdrawStatusEnum::Pending->value,
            'devMode' => true,
            'receiptUrl' => 'https://abacatepay.com/receipt/tran_1234567890abcdef',
            'kind' => WithdrawKindEnum::Withdraw->value,
            'amount' => 5000,
            'platformFee' => 80,
            'externalId' => 'withdraw-1234',
            'createdAt' => '2024-12-06T18:56:15.538Z',
            'updatedAt' => '2024-12-06T18:56:15.538Z',
        ],
    ];
});

it('should be able to withdraw something', function (): void {

    $handler = new MockHandler([
        new Response(200, [], json_encode($this->responseData)),
    ]);

    $client = new Client(['handler' => $handler]);

    $withDrawResource = new WithdrawResource(client: $client);

    $response = $withDrawResource->withdraw(
        request: $this->requestDto,
    );

    expect($response->data->id)->toBe('tran_1234567890abcdef')
        ->and($response->data->status)->toBe(WithdrawStatusEnum::Pending)
        ->and($response->data->amount)->toBe(5000)
        ->and($response->data->platformFee)->toBe(80)
        ->and($response->data->devMode)->toBeTrue()
        ->and($response->data->kind)->toBe(WithdrawKindEnum::Withdraw)
        ->and($response->data->externalId)->toBe('withdraw-1234')
        ->and($response->data->created_at)->toBe('2024-12-06T18:56:15.538Z')
        ->and($response->data->updated_at)->toBe('2024-12-06T18:56:15.538Z');
});

it('throws unauthorized exception', function (): void {
    $handler = new MockHandler([
        new ClientException(
            'Unauthorized',
            new Request('GET', 'test-abacatepay'),
            new Response(401, [], json_encode(['error' => 'Unauthorized'], JSON_THROW_ON_ERROR))
        ),
    ]);

    $client = new Client(['handler' => $handler]);

    $withDrawResource = new WithdrawResource(client: $client);

    expect(fn () => $withDrawResource->withdraw(request: $this->requestDto))
        ->toThrow(
            AbacatePayException::class,
            'Token de autenticação inválido ou ausente.'
        );
});

it('throws internal server error exception', function (): void {
    $handler = new MockHandler([
        new ServerException(
            'Internal Server Error',
            new Request('POST', 'test-abacatepay'),
            new Response(500, [], json_encode(['error' => 'server crashed'], JSON_THROW_ON_ERROR))
        ),
    ]);

    $client = new Client(['handler' => $handler]);
    $withDrawResource = new WithdrawResource(client: $client);

    expect(fn () => $withDrawResource->withdraw(request: $this->requestDto))
        ->toThrow(
            AbacatePayException::class,
            'Internal Server Error'
        );
});

it('should return an withdraw', function (): void {

    $handler = new MockHandler([
        new Response(200, [], json_encode($this->responseData)),
    ]);

    $client = new Client(['handler' => $handler]);

    $withDrawResource = new WithdrawResource(client: $client);
    $response = $withDrawResource->findWithDrawById('withdraw-1234');

    expect($response->data->id)->toBe('tran_1234567890abcdef')
        ->and($response->data->status)->toBe(WithdrawStatusEnum::Pending)
        ->and($response->data->amount)->toBe(5000)
        ->and($response->data->platformFee)->toBe(80)
        ->and($response->data->devMode)->toBeTrue()
        ->and($response->data->kind)->toBe(WithdrawKindEnum::Withdraw)
        ->and($response->data->externalId)->toBe('withdraw-1234')
        ->and($response->data->created_at)->toBe('2024-12-06T18:56:15.538Z')
        ->and($response->data->updated_at)->toBe('2024-12-06T18:56:15.538Z');

});

it('should list all withdraw', function (): void {

    $data = [
        'data' => [
            [
                'id' => 'tran_1234567890abcdef',
                'status' => WithdrawStatusEnum::Pending->value,
                'devMode' => true,
                'receiptUrl' => 'https://abacatepay.com/receipt/tran_1234567890abcdef',
                'kind' => WithdrawKindEnum::Withdraw->value,
                'amount' => 5000,
                'platformFee' => 80,
                'externalId' => 'withdraw-1234',
                'createdAt' => '2024-12-06T18:56:15.538Z',
                'updatedAt' => '2024-12-06T18:56:15.538Z',
            ],
            [
                'id' => '12324abcde',
                'status' => WithdrawStatusEnum::Complete->value,
                'devMode' => true,
                'receiptUrl' => 'https://abacatepay.com/receipt/tran_1234567890abcdef',
                'kind' => WithdrawKindEnum::Withdraw->value,
                'amount' => 2000,
                'platformFee' => 80,
                'externalId' => 'another-external-id',
                'createdAt' => '2024-12-06T18:56:15.538Z',
                'updatedAt' => '2024-12-06T18:56:15.538Z',
            ],
        ],
    ];

    $handler = new MockHandler([
        new Response(200, [], json_encode($data)),
    ]);

    $client = new Client(['handler' => $handler]);

    $withDrawResource = new WithdrawResource(client: $client);
    $response = $withDrawResource->listWithdraw();
    $firstWithdraw = $response->all()[0];
    $secondWithdraw = $response->all()[1];

    expect($response->count())->toBe(2)
        ->and($firstWithdraw->id)->toBe('tran_1234567890abcdef')
        ->and($firstWithdraw->status)->toBe(WithdrawStatusEnum::Pending)
        ->and($firstWithdraw->amount)->toBe(5000)
        ->and($firstWithdraw->platformFee)->toBe(80)
        ->and($firstWithdraw->devMode)->toBeTrue()
        ->and($firstWithdraw->kind)->toBe(WithdrawKindEnum::Withdraw)
        ->and($firstWithdraw->externalId)->toBe('withdraw-1234')
        ->and($firstWithdraw->created_at)->toBe('2024-12-06T18:56:15.538Z')
        ->and($firstWithdraw->updated_at)->toBe('2024-12-06T18:56:15.538Z')
        ->and($secondWithdraw->id)->toBe('12324abcde')
        ->and($secondWithdraw->status)->toBe(WithdrawStatusEnum::Complete)
        ->and($secondWithdraw->amount)->toBe(2000)
        ->and($secondWithdraw->platformFee)->toBe(80)
        ->and($secondWithdraw->devMode)->toBeTrue()
        ->and($secondWithdraw->externalId)->toBe('another-external-id')
        ->and($secondWithdraw->created_at)->toBe('2024-12-06T18:56:15.538Z')
        ->and($secondWithdraw->updated_at)->toBe('2024-12-06T18:56:15.538Z');

});

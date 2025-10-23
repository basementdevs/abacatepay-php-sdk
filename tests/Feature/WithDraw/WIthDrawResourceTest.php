<?php

declare(strict_types=1);

use AbacatePay\WithDraw\Enums\AvailableWithDrawMethodsEnum;
use AbacatePay\WithDraw\Enums\AvailableWithDrawPixTypeEnum;
use AbacatePay\WithDraw\Enums\AvailableWithDrawStatusEnum;
use AbacatePay\WithDraw\Http\Request\WithDrawPixRequest;
use AbacatePay\WithDraw\Http\Request\WithDrawRequest;
use AbacatePay\WithDraw\WithDrawResource;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

it('should be able to withdraw something', function (): void {
    $data = [
        'data' => [
            'id' => 'tran_1234567890abcdef',
            'status' => AvailableWithDrawStatusEnum::PENDING->value,
            'devMode' => true,
            'receiptUrl' => 'https://abacatepay.com/receipt/tran_1234567890abcdef',
            'kind' => 'WITHDRAW',
            'amount' => 5000,
            'platformFee' => 80,
            'externalId' => 'withdraw-1234',
            'createdAt' => '2024-12-06T18:56:15.538Z',
            'updatedAt' => '2024-12-06T18:56:15.538Z',
        ],
    ];

    $handler = new MockHandler([
        new Response(200, [], json_encode($data)),
    ]);

    $client = new Client(['handler' => $handler]);

    $withDrawResource = new WithDrawResource(client: $client);

    $requestDto = new WithDrawRequest(
        externalId: 'tran_1234567890abcdef',
        method: AvailableWithDrawMethodsEnum::PIX,
        amount: '100',
        pix: WithDrawPixRequest::make([
            'type' => AvailableWithDrawPixTypeEnum::CPF->value,
            'key' => '123.456.789-10',
        ]),
        description: 'description',
    );

    $response = $withDrawResource->withDraw(
        request: $requestDto,
    );

    expect($response->data->id)->toBe('tran_1234567890abcdef')
        ->and($response->data->status->value)->toBe('PENDING')
        ->and($response->data->amount)->toBe(5000)
        ->and($response->data->platformFee)->toBe(80)
        ->and($response->data->devMode)->toBe(true)
        ->and($response->data->kind)->toBe('WITHDRAW')
        ->and($response->data->externalId)->toBe('withdraw-1234')
        ->and($response->data->created_at)->toBe('2024-12-06T18:56:15.538Z')
        ->and($response->data->updated_at)->toBe('2024-12-06T18:56:15.538Z');
});

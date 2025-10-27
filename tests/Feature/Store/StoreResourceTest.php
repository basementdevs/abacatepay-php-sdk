<?php

declare(strict_types=1);

use Basement\AbacatePay\Store\Http\Response\StoreResponse;
use Basement\AbacatePay\Store\StoreResource;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

it('returns store data', function () {
    $storeResponse = [
        'data' => [
            'id' => 'store_123456',
            'name' => 'Minha Loja Online',
            'balance' => [
                'available' => 15000,
                'pending' => 5000,
                'blocked' => 2000,
            ],
        ],
    ];

    $handler = new MockHandler([
        new Response(200, [], json_encode($storeResponse)),
    ]);
    $client = new Client(['handler' => $handler]);

    $resource = new StoreResource(client: $client);

    $response = $resource->getStore();

    expect($response)->toBeInstanceOf(StoreResponse::class);
});

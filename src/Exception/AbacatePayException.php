<?php

declare(strict_types=1);

namespace Basement\AbacatePay\Exception;

use Exception;

final class AbacatePayException extends Exception
{
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        public readonly ?array $responseBody = null,
    ) {
        parent::__construct($message, $statusCode);
    }

    public static function unauthorized(string $message = 'Token de autenticação inválido ou ausente.'): self
    {
        return new self($message, 401);
    }

    public static function missingRequiredFields(array $fields = []): self
    {
        $message = 'Missing required fields: '.implode(', ', $fields);

        return new self($message, 400);
    }
}

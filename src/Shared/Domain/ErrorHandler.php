<?php

namespace App\Shared\Domain;

use ErrorException;
use Throwable;

class ErrorHandler
{
    public static function handleError(
        int $errCode,
        string $errName,
        string $errFile,
        int $errLine
    ): void 
    {
        throw new ErrorException($errName, 0, $errCode, $errFile, $errLine);
    }

    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
                "code" => $exception->getCode(),
                "message" => $exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine()
        ]);
    }
}
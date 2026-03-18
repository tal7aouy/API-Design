<?php

declare(strict_types=1);

function getRequestId(): string
{
    if (!empty($_SERVER['HTTP_X_REQUEST_ID'])) {
        return (string) $_SERVER['HTTP_X_REQUEST_ID'];
    }

    return bin2hex(random_bytes(16));
}

function logRequest(string $requestId): void
{
    $data = [
        'request_id' => $requestId,
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
        'path' => $_SERVER['REQUEST_URI'] ?? 'UNKNOWN',
        'timestamp' => (new DateTimeImmutable())->format(DATE_ATOM),
    ];

    error_log(json_encode($data));
}

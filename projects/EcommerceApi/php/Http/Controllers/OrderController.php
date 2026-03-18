<?php

declare(strict_types=1);

namespace Projects\EcommerceApi\Php\Http\Controllers;

use Projects\EcommerceApi\Php\Domain\Services\OrderService;

final class OrderController
{
    public function __construct(private readonly OrderService $service)
    {
    }

    public function index(): void
    {
        // In a real app this would come from an auth layer.
        $userId = $_SERVER['HTTP_AUTHORIZATION'] ?? 'user_1';

        $orders = array_map(fn($o) => $o->toArray(), $this->service->listForUser($userId));

        header('Content-Type: application/json');
        echo json_encode($orders);
    }

    public function store(): void
    {
        $userId = $_SERVER['HTTP_AUTHORIZATION'] ?? 'user_1';
        $idempotencyKey = $_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? '';

        $input = json_decode((string) file_get_contents('php://input'), true);
        $items = $input['items'] ?? [];

        try {
            $order = $this->service->createOrder($userId, $items, $idempotencyKey);
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($order->toArray());
        } catch (\InvalidArgumentException $e) {
            http_response_code(422);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => $e->getMessage(),
                ],
            ]);
        }
    }
}

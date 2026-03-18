<?php

declare(strict_types=1);

namespace Projects\EcommerceApi\Php\Http\Controllers;

use Projects\EcommerceApi\Php\Domain\Services\ProductService;

final class ProductController
{
    public function __construct(private readonly ProductService $service)
    {
    }

    public function index(): void
    {
        $products = array_map(fn($p) => $p->toArray(), $this->service->list());

        header('Content-Type: application/json');
        echo json_encode(['data' => $products]);
    }

    public function store(): void
    {
        $input = json_decode((string) file_get_contents('php://input'), true);

        try {
            $product = $this->service->create($input ?? []);
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($product->toArray());
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

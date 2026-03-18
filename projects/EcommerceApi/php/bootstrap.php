<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/middleware.php';

use Projects\EcommerceApi\Php\Http\Controllers\OrderController;
use Projects\EcommerceApi\Php\Http\Controllers\ProductController;
use Projects\EcommerceApi\Php\Domain\Services\OrderService;
use Projects\EcommerceApi\Php\Domain\Services\ProductService;
use Projects\EcommerceApi\Php\Domain\Repositories\OrderRepository;
use Projects\EcommerceApi\Php\Domain\Repositories\ProductRepository;

$requestId = getRequestId();
header('X-Request-Id: ' . $requestId);
logRequest($requestId);

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Minimal schema for the example.
$pdo->exec('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, description TEXT, price_cents INTEGER, available_quantity INTEGER)');
$pdo->exec('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id TEXT, payload TEXT, created_at TEXT, idempotency_key TEXT UNIQUE)');

$productRepo = new ProductRepository($pdo);
$orderRepo = new OrderRepository($pdo);

$productService = new ProductService($productRepo);
$orderService = new OrderService($orderRepo, $productService);

$productController = new ProductController($productService);
$orderController = new OrderController($orderService);

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET' && $path === '/api/v1/products') {
    $productController->index();
    return;
}

if ($method === 'POST' && $path === '/api/v1/products') {
    $productController->store();
    return;
}

if ($method === 'GET' && $path === '/api/v1/orders') {
    $orderController->index();
    return;
}

if ($method === 'POST' && $path === '/api/v1/orders') {
    $orderController->store();
    return;
}

http_response_code(404);
header('Content-Type: application/json');

echo json_encode([
    'error' => [
        'code' => 'NOT_FOUND',
        'message' => 'Route not found.',
    ],
]);

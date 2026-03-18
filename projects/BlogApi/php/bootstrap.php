<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';
require __DIR__ . '/middleware.php';

use Projects\BlogApi\Php\Http\Controllers\PostController;
use Projects\BlogApi\Php\Domain\Services\PostService;
use Projects\BlogApi\Php\Domain\Repositories\PostRepository;

$requestId = getRequestId();
header('X-Request-Id: ' . $requestId);
logRequest($requestId);

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Minimal schema for the example.
$pdo->exec('CREATE TABLE posts (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, body TEXT, author_id TEXT, created_at TEXT)');

$postRepository = new PostRepository($pdo);
$postService    = new PostService($postRepository);
$postController = new PostController($postService);

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET' && $path === '/api/v1/posts') {
    $postController->index();
    return;
}

if ($method === 'POST' && $path === '/api/v1/posts') {
    $postController->store();
    return;
}

if ($method === 'GET' && preg_match('#^/api/v1/posts/(?<id>\d+)$#', $path, $matches)) {
    $postController->show((int) $matches['id']);
    return;
}

http_response_code(404);
header('Content-Type: application/json');

echo json_encode([
    'error' => [
        'code'    => 'NOT_FOUND',
        'message' => 'Route not found.',
    ],
]);

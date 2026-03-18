<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Examples\Good\Php\Http\Controllers\PostController;
use Examples\Good\Php\Domain\Services\PostService;
use Examples\Good\Php\Domain\Repositories\PostRepository;

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Minimal schema for the example.
$pdo->exec('CREATE TABLE posts (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, body TEXT, author_id TEXT, created_at TEXT)');

$postRepository = new PostRepository($pdo);
$postService    = new PostService($postRepository);
$postController = new PostController($postService);

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET' && $path === '/posts') {
    $postController->index();
    return;
}

if ($method === 'POST' && $path === '/posts') {
    $postController->store();
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

<?php

declare(strict_types=1);

namespace Projects\BlogApi\Php\Http\Controllers;

use Projects\BlogApi\Php\Domain\Services\PostService;

final class PostController
{
    public function __construct(private readonly PostService $service)
    {
    }

    public function index(): void
    {
        $posts = array_map(fn($post) => $post->toArray(), $this->service->listPosts());

        header('Content-Type: application/json');
        echo json_encode(['data' => $posts]);
    }

    public function show(int $id): void
    {
        $post = $this->service->getPost($id);
        if (! $post) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => ['code' => 'NOT_FOUND', 'message' => 'Post not found']]);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($post->toArray());
    }

    public function store(): void
    {
        $body = json_decode((string) file_get_contents('php://input'), true);

        $title = $body['title'] ?? '';
        $postBody = $body['body'] ?? '';
        $authorId = $body['author_id'] ?? '';

        try {
            $post = $this->service->createPost($title, $postBody, $authorId);
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($post->toArray());
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

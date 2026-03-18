<?php

declare(strict_types=1);

namespace Examples\Good\Php\Http\Controllers;

use Examples\Good\Php\Domain\Services\PostService;
use InvalidArgumentException;

final class PostController
{
    public function __construct(private PostService $service)
    {
    }

    public function index(): void
    {
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;
        $cursor = $_GET['cursor'] ?? null;

        $posts = $this->service->listPosts($limit, $cursor);

        http_response_code(200);
        header('Content-Type: application/json');

        echo json_encode([
            'data'       => array_map(fn ($p) => $p->toArray(), $posts),
            'pagination' => [
                'next_cursor'     => null,
                'previous_cursor' => null,
                'limit'           => $limit,
            ],
        ]);
    }

    public function store(): void
    {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $title    = $body['title']     ?? null;
        $content  = $body['body']      ?? null;
        $authorId = $body['author_id'] ?? null;

        if (!$title || !$content || !$authorId) {
            http_response_code(422);
            header('Content-Type: application/json');

            echo json_encode([
                'error' => [
                    'code'    => 'VALIDATION_ERROR',
                    'message' => 'Missing required fields.',
                    'details' => [
                        'title'     => $title ? [] : ['This field is required.'],
                        'body'      => $content ? [] : ['This field is required.'],
                        'author_id' => $authorId ? [] : ['This field is required.'],
                    ],
                ],
            ]);
            return;
        }

        try {
            $post = $this->service->createPost($title, $content, $authorId);
        } catch (InvalidArgumentException $e) {
            http_response_code(422);
            header('Content-Type: application/json');

            echo json_encode([
                'error' => [
                    'code'    => 'VALIDATION_ERROR',
                    'message' => $e->getMessage(),
                ],
            ]);
            return;
        }

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode($post->toArray());
    }
}

<?php

// BAD EXAMPLE: Do NOT copy this style.

$pdo = new PDO('mysql:host=localhost;dbname=blog', 'root', 'root');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'createPost') {
    // No authentication.
    // No validation.
    // SQL injection risk.
    $title = $_POST['title'];
    $body = $_POST['body'];
    $userId = $_POST['user_id'];

    $sql = "INSERT INTO posts (title, body, user_id) VALUES ('$title', '$body', $userId)";
    $pdo->exec($sql);

    http_response_code(200); // Wrong: should be 201
    echo json_encode([
        'status' => 'ok',
        'postId' => $pdo->lastInsertId(), // leaking raw DB ID
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'getAll') {
    $rows = $pdo->query('SELECT * FROM posts')->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows); // raw DB structure, includes internal columns
    exit;
}

http_response_code(500);
echo 'Something went wrong';

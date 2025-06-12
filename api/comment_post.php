<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['post_id']) || empty(trim($_POST['post_id']))) {
        $response = ['status' => 'error', 'message' => 'Post ID is required.'];
        echo json_encode($response);
        exit;
    }

    if (!isset($_POST['user_id']) || empty(trim($_POST['user_id']))) {
        $response = ['status' => 'error', 'message' => 'User ID is required.'];
        echo json_encode($response);
        exit;
    }

    if (!isset($_POST['content']) || empty(trim($_POST['content']))) {
        $response = ['status' => 'error', 'message' => 'Comment content is required.'];
        echo json_encode($response);
        exit;
    }

    $post_id = trim($_POST['post_id']);
    $user_id = trim($_POST['user_id']);
    $content = trim($_POST['content']);

    // Sanitize data
    $post_id = mysqli_real_escape_string($conn, $post_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $content = mysqli_real_escape_string($conn, $content);

    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$content')";

    if (mysqli_query($conn, $sql)) {
        $response = ['status' => 'success', 'message' => 'Comment created successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error creating comment: ' . mysqli_error($conn)];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
}

echo json_encode($response);
?>
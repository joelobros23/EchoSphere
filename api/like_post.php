<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['post_id']) && !empty($_POST['post_id']) && isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        // Check if the post is already liked by the user
        $check_query = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $post_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $response = ['status' => 'error', 'message' => 'Post already liked.'];
            echo json_encode($response);
            exit();
        }

        $query = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ii", $post_id, $user_id);
            if ($stmt->execute()) {
                $response = ['status' => 'success', 'message' => 'Post liked successfully.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Failed to like post.'];
            }
            $stmt->close();
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to prepare statement.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Post ID and User ID are required.'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
}

echo json_encode($response);
?>
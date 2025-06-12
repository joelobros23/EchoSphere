<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['post_id']) && !empty(trim($_POST['post_id'])) && isset($_POST['user_id']) && !empty(trim($_POST['user_id']))) {
        $postId = trim($_POST['post_id']);
        $userId = trim($_POST['user_id']);

        // Prepare and execute the SQL query to delete the like
        $sql = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $postId, $userId);

        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Post unliked successfully.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to unlike post. ' . $stmt->error];
        }

        $stmt->close();
    } else {
        $response = ['status' => 'error', 'message' => 'Post ID and User ID are required.'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
}

echo json_encode($response);

$conn->close();
?>
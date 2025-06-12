<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    $response = array('status' => 'error', 'message' => 'Post ID is required.');
    echo json_encode($response);
    exit;
}

$post_id = $_GET['post_id'];

$sql = "SELECT comments.id, comments.content, comments.created_at, users.username, users.profile_picture FROM comments INNER JOIN users ON comments.user_id = users.id WHERE post_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $comments = array();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    $response = array('status' => 'success', 'message' => 'Comments retrieved successfully.', 'comments' => $comments);
} else {
    $response = array('status' => 'error', 'message' => 'Error retrieving comments: ' . $stmt->error);
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
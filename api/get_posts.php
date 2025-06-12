<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT posts.id, posts.user_id, posts.content, posts.image_url, posts.created_at, users.username, users.profile_picture 
            FROM posts 
            INNER JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC";

    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $posts = array();
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    $response = array('status' => 'success', 'message' => 'Posts retrieved successfully', 'posts' => $posts);

} catch (Exception $e) {
    $response = array('status' => 'error', 'message' => $e->getMessage());
}

echo json_encode($response);
?>
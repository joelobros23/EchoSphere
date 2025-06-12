<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "SELECT id, username, email, first_name, last_name, profile_picture, bio, created_at FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $response = array('status' => 'success', 'user' => $user);
    } else {
        $response = array('status' => 'error', 'message' => 'User not found.');
    }

    $stmt->close();
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request.');
}

echo json_encode($response);

$conn->close();
?>
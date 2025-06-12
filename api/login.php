<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['username']) || empty(trim($_POST['username']))) {
        $response = ['status' => 'error', 'message' => 'Username is required.'];
        echo json_encode($response);
        exit;
    }

    if (!isset($_POST['password']) || empty(trim($_POST['password']))) {
        $response = ['status' => 'error', 'message' => 'Password is required.'];
        echo json_encode($response);
        exit;
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a new session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            $response = ['status' => 'success', 'message' => 'Login successful.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Incorrect password.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'No user found with that username.'];
    }

    $stmt->close();
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
}

echo json_encode($response);
?>
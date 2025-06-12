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

    if (!isset($_POST['email']) || empty(trim($_POST['email']))) {
        $response = ['status' => 'error', 'message' => 'Email is required.'];
        echo json_encode($response);
        exit;
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : null;
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : null;

    // Basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['status' => 'error', 'message' => 'Invalid email format.'];
        echo json_encode($response);
        exit;
    }


    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = ['status' => 'error', 'message' => 'Username or email already exists.'];
        echo json_encode($response);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $hashed_password, $email, $first_name, $last_name);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'User registered successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Registration failed. ' . $stmt->error];
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);

} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
    echo json_encode($response);
}
?>
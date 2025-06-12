<?php
require_once 'config.php';

session_start();

if (session_destroy()) {
    $response = array('status' => 'success', 'message' => 'Logged out successfully.');
} else {
    $response = array('status' => 'error', 'message' => 'Failed to log out.');
}

echo json_encode($response);
?>
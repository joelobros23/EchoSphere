<?php
require_once 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $response = ['status' => 'error', 'message' => 'Not logged in'];
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Retrieve and sanitize input data
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';

    // Handle profile picture upload
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_size = $_FILES['profile_picture']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $extensions = ['jpeg', 'jpg', 'png'];

        if (in_array($file_ext, $extensions)) {
            if ($file_size < 2097152) { // 2MB limit
                $new_file_name = uniqid('', true) . '.' . $file_ext;
                $file_destination = 'assets/img/' . $new_file_name;  //Make sure the assets/img folder exists and is writable. Adjust path if necessary

                if (move_uploaded_file($file_tmp, $file_destination)) {
                    $profile_picture = $new_file_name;  //Store only the filename in the database
                } else {
                    $response = ['status' => 'error', 'message' => 'Failed to upload profile picture.'];
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response = ['status' => 'error', 'message' => 'File size too large. Max size is 2MB.'];
                echo json_encode($response);
                exit;
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid file type. Allowed types are jpeg, jpg, and png.'];
            echo json_encode($response);
            exit;
        }
    }


    // Construct the SQL query
    $sql = "UPDATE users SET ";
    $updates = [];

    if ($first_name !== '') {
        $updates[] = "first_name = '$first_name'";
    }
    if ($last_name !== '') {
        $updates[] = "last_name = '$last_name'";
    }
    if ($bio !== '') {
        $updates[] = "bio = '$bio'";
    }
    if ($profile_picture !== null) {
        $updates[] = "profile_picture = '$profile_picture'";
    }

    if (empty($updates)) {
         $response = ['status' => 'success', 'message' => 'No changes were provided.'];
         echo json_encode($response);
         exit;
    }
    $sql .= implode(", ", $updates);
    $sql .= " WHERE id = $user_id";

    if ($conn->query($sql) === TRUE) {
        $response = ['status' => 'success', 'message' => 'Profile updated successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Error updating profile: ' . $conn->error];
    }

    echo json_encode($response);
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
    echo json_encode($response);
}

$conn->close();
?>
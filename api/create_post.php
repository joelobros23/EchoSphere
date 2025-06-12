<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['user_id']) || empty(trim($_POST['user_id']))) {
        $response = ['status' => 'error', 'message' => 'User ID is required.'];
        echo json_encode($response);
        exit;
    }

    if (!isset($_POST['content']) || empty(trim($_POST['content']))) {
        $response = ['status' => 'error', 'message' => 'Post content is required.'];
        echo json_encode($response);
        exit;
    }

    $user_id = $_POST['user_id'];
    $content = trim($_POST['content']);
    $image_url = null;

    // Image Upload Handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['image']['name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $response = ['status' => 'error', 'message' => 'Invalid image format. Allowed formats: jpg, jpeg, png, gif.'];
            echo json_encode($response);
            exit;
        }

        $file_size = $_FILES['image']['size'];
        if ($file_size > 2000000) { // 2MB limit
            $response = ['status' => 'error', 'message' => 'Image size exceeds the limit of 2MB.'];
            echo json_encode($response);
            exit;
        }

        $new_file_name = uniqid() . '.' . $file_extension;
        $upload_directory = 'uploads/';  // Create this directory in your project root.  Make sure it has write permissions.
		
		//Ensure the uploads directory exists
		if (!file_exists($upload_directory)) {
			mkdir($upload_directory, 0777, true); //Create directory with write permissions
		}


        $target_path = $upload_directory . $new_file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_url = $target_path;
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to upload image.'];
            echo json_encode($response);
            exit;
        }
    }

    // Database Insertion
    $sql = "INSERT INTO posts (user_id, content, image_url) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $response = ['status' => 'error', 'message' => 'Database error: ' . $conn->error];
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("iss", $user_id, $content, $image_url);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Post created successfully.'];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to create post: ' . $stmt->error];
    }

    $stmt->close();
    echo json_encode($response);

} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
    echo json_encode($response);
}

$conn->close();
?>
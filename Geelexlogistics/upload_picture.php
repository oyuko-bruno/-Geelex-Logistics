<?php
session_start();
include 'db.php'; // This should create the $pdo connection

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Check if a file was uploaded
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    // File upload logic
    $file = $_FILES['profile_pic'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    // Generate a unique name for the file to avoid name conflicts
    $unique_file_name = time() . '_' . uniqid() . '.' . $file_ext;
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . $unique_file_name;

    // Check if the file is an image
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array(strtolower($file_ext), $allowed_extensions)) {
        // Move the file to the upload directory
        if (move_uploaded_file($file_tmp, $upload_file)) {
            // Update the database with the new profile picture path
            $stmt = $pdo->prepare("UPDATE clients SET profile_picture = ? WHERE id = ?");
            if ($stmt->execute([$unique_file_name, $client_id])) {
                // Set success message in session
                $_SESSION['message'] = 'Profile picture uploaded successfully!';
                header("Location: profile.php");
                exit();
            }
        } else {
            $_SESSION['message'] = 'Error uploading file.';
        }
    } else {
        $_SESSION['message'] = 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.';
    }
} else {
    $_SESSION['message'] = 'No file uploaded or error occurred during upload.';
}

header("Location: profile.php");
exit();

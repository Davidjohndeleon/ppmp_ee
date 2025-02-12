<?php
// upload_image.php

// Database connection
include('../assets/connection/sqlconnection.php'); // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form was submitted
    if (isset($_POST['itemID']) && isset($_FILES['itemImage'])) {
        $itemID = $_POST['itemID'];
        $file = $_FILES['itemImage'];

        // File upload handling
        $targetDir = "../source/inventory_image/"; // Directory to store uploaded images
        $fileName = basename($file['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Allow certain file formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Upload file to server
            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                // Update the database with the new image path
                $sql = "UPDATE items SET itemImage = ? WHERE itemID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $fileName, $itemID);

                if ($stmt->execute()) {
                    echo "Image uploaded and database updated successfully!";
                } else {
                    echo "Failed to update the database.";
                }
            } else {
                echo "Failed to upload the file.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}
?>
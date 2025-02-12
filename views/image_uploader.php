<?php
session_start();
require('../assets/connection/sqlconnection.php');
date_default_timezone_set('Asia/Manila');

// Allowed file types and maximum file size
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
$maxFileSize = 2 * 1024 * 1024; // 2MB
$uploadDir = '../source/inventory_image/';

// Ensure upload directory exists and is writable
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['itemImage']) && isset($_POST['itemID'])) {
    try {
        // Get the item ID from POST and validate
        $id = filter_input(INPUT_POST, 'itemID', FILTER_VALIDATE_INT);
        if (!$id) {
            throw new Exception("Invalid item ID");
        }

        $image = $_FILES['itemImage'];
        
        // Validate image file type and size
        if (!in_array($image['type'], $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, and GIF allowed.");
        }

        if ($image['size'] > $maxFileSize) {
            throw new Exception("File too large. Max 2MB allowed.");
        }

        if ($image['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Upload failed with error code: " . $image['error']);
        }

        // Create a secure random temporary filename
        $tempName = $uploadDir . bin2hex(random_bytes(16));

        // Move the uploaded file to a temporary location
        if (!move_uploaded_file($image['tmp_name'], $tempName)) {
            throw new Exception("Failed to upload file");
        }

        // Verify that the file is an image
        $imageInfo = @getimagesize($tempName);
        if ($imageInfo === false || !in_array($imageInfo['mime'], $allowedTypes)) {
            unlink($tempName);
            throw new Exception("Invalid image file");
        }

        // Generate a unique filename for the image
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        $imageFileName = 'item_' . $id . '_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $imagePath = $uploadDir . $imageFileName;

        // Process the image (resize if needed)
        // You can add any image processing code here if necessary

        // Move the image to the final destination
        if (!rename($tempName, $imagePath)) {
            unlink($tempName);
            throw new Exception("Failed to save image");
        }

        // Update the database with the new image path
        $stmt = $pdo->prepare("UPDATE imiss_inventory SET itemImage = :path WHERE itemID = :id");
        $stmt->bindParam(':path', $imagePath, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new Exception("Database update failed");
        }

        $result = ["success" => true, "message" => "Image uploaded successfully"];
    } catch (Exception $e) {
        // Clean up any temporary files
        if (isset($tempName) && file_exists($tempName)) {
            unlink($tempName);
        }
        $result = ["success" => false, "message" => $e->getMessage()];
    }

    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Uploader</title>
    <link rel="stylesheet" href="../css/image_uploader.css">
    <?php require "../links/header_link.php" ?>
</head>
<body>
    <?php 
        $view = "imiss-uploader-sub-div";
        include("./sidebar.php")
    ?>

    <div class="right-container">
        <div class="function-bar">
            <div class="search-bar">
                <input type="text" id="search-input" class="form-control">
                <button id="search-btn" class="btn btn-primary">Search</button>
            </div>
        </div>

        <div class="inventory-div">
            <?php 
            try {
                $stmt = $pdo->query("SELECT itemID, itemName, itemPrice, itemImage FROM imiss_inventory");
                while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                    // Default image if no image is found
                    $imagePath = !empty($item['itemImage']) && file_exists($item['itemImage']) 
                        ? $item['itemImage'] 
                        : "../source/inventory_image/default.png"; 
            ?>
                <div class="tiles-div">
                    <img class="item-img" 
                         src="<?php echo $imagePath; ?>" 
                         alt="<?php echo htmlspecialchars($item['itemName']); ?>">

                         <p class="item-description" title="<?php echo htmlspecialchars($item['itemName']); ?>">
                            <?php 
                                $itemName = htmlspecialchars($item['itemName']);
                                echo strlen($itemName) > 90 ? substr($itemName, 0, 90) . '...' : $itemName;
                            ?>
                            <span class="item-id d-none"><?php echo htmlspecialchars($item['itemID']); ?></span>
                        </p>

                    <span class="item-price">₱<?php echo number_format($item['itemPrice'], 2); ?></span>
                    
                    <div>
                        <button class="upload-btn btn btn-primary" 
                                data-item-id="<?php echo htmlspecialchars($item['itemID']); ?>" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modal-image-uploader">
                            Upload Image
                        </button>
                    </div>
                </div>
            <?php 
                }
            } catch (PDOException $e) {
                echo "Error fetching data: " . $e->getMessage();
            }
            ?>
        </div>
    </div>

    <!-- Modal for Image Upload -->
    <div class="modal fade" id="modal-image-uploader" tabindex="-1" aria-labelledby="modal-image-uploaderLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-image-uploaderLabel">Upload Item Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="upload-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="itemImage">Choose Image</label>
                            <input type="file" class="form-control" name="itemImage" id="itemImage" required>
                        </div>
                        <input type="hidden" id="itemID" name="itemID">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                    <div id="upload-result"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle the modal button click
            const uploadButtons = document.querySelectorAll('.upload-btn');
            uploadButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const itemID = this.getAttribute('data-item-id');
                    document.getElementById('itemID').value = itemID;
                });
            });

            // Handle the form submission
            document.getElementById('upload-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);

                // AJAX request to upload the image
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('upload-result');
                    if (data.success) {
                        resultDiv.textContent = data.message;

                        // Optionally, close the modal after successful upload
                        $('#modal-image-uploader').modal('hide');

                        // Update the image dynamically in the tile
                        const itemID = document.getElementById('itemID').value;
                        const imagePath = data.imagePath; // Assuming the server returns the new image path

                        // Find the item and update its image src
                        const itemImage = document.querySelector(`.tiles-div .item-id[data-id='${itemID}']`).closest('.tiles-div').querySelector('.item-img');
                        itemImage.src = imagePath;

                    } else {
                        resultDiv.textContent = 'Error: ' + data.message;
                    }
                })
                .catch(error => {
                    document.getElementById('upload-result').textContent = 'Error uploading image';
                });
            });
        });
    </script>
    <script>
        var section = "<?php echo $section ?>";
    </script>
    <script src="../js/home_traverse.js?v=<?php echo time(); ?>"></script>
    <script src="../js/home_function.js?v=<?php echo time(); ?>"></script>
</body>
</html>
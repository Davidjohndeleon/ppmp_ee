<?php
session_start();
include('../assets/connection/sqlconnection.php');
date_default_timezone_set('Asia/Manila');

    // Create
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the form data
        $itemName = $_POST['item_name'];
        $itemPrice = $_POST['item_price'];
        $itemDescription = $_POST['item_description'];

        // Handle the image upload
        $imageData = null;
        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
            
            $imageTmpName = $_FILES['item_image']['tmp_name'];
            $imageType = $_FILES['item_image']['type'];

            // Get the actual file content as binary (to store in LONGBLOB)
            $imageData = file_get_contents($imageTmpName);
        }

        $sql = "INSERT INTO imiss_inventory (itemName, itemPrice, itemDescription, itemImage) VALUES (:itemName, :itemPrice, :itemDescription, :itemImage)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':itemName', $itemName);
        $stmt->bindParam(':itemPrice', $itemPrice);
        $stmt->bindParam(':itemDescription', $itemDescription);
        $stmt->bindParam(':itemImage', $imageData, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Item ' . htmlspecialchars($itemName) . ' added successfully!';
            header("Location: ../views/imiss_inventory.php");
            exit();
        } else {
            $_SESSION['error_message'] = 'Error adding item.';
            header("Location: ../views/imiss_inventory.php");
            exit();
        }
    }

    // Update
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     $itemID = $_POST['itemID'];
    //     $itemName = $_POST['itemName'];
    //     $itemPrice = $_POST['itemPrice'];
    //     $itemDescription = $_POST['itemDescription'];
        
        
    //     $sql = "UPDATE imiss_inventory SET itemName=?, itemPrice=?, itemDescription=? WHERE itemID=?";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->execute([$itemName, $itemPrice, $itemDescription, $itemID]);
        

    //     // success
    //     header("Location: ../views/imiss_inventory.php");
    //     exit();
    // }


    // Delete
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $itemID = $_GET['id'];

        $sql = "DELETE FROM imiss_inventory WHERE itemID = :itemID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':itemID', $itemID, PDO::PARAM_INT);

        $stmt->execute();

        header("Location: ../views/imiss_inventory.php");
        exit();
    }
?>
<?php
session_start();
include('../assets/connection/sqlconnection.php');
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemID = (int)$_POST['itemID'];
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $itemDescription = $_POST['itemDescription'];

    // Check if a new image was uploaded
    if (isset($_FILES['itemImage']) && $_FILES['itemImage']['error'] == 0) {
        $imageData = file_get_contents($_FILES['itemImage']['tmp_name']);
    } else {
        $imageData = null;
    }

    $sql = "UPDATE imiss_inventory SET itemName=?, itemPrice=?, itemDescription=?, itemImage=? WHERE itemID=?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $itemName,
        $itemPrice,
        $itemDescription,
        $imageData,
        $itemID
    ]);

    echo json_encode(["status" => "success", "message" => "Item updated successfully!"]);
}
?>
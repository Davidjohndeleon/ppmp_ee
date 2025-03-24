<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $itemName = $_POST['itemName'];
        $itemPrice = $_POST['itemPrice'];
        $itemDescription = $_POST['itemDescription'];

        if (isset($_FILES['itemImage'])) {
            $itemImage = file_get_contents($_FILES['itemImage']['tmp_name']);
        } else {
            $itemImage = null;
        }

        $sql = "INSERT INTO imiss_inventory (itemName, itemPrice, itemDescription, itemImage) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $itemName,
            $itemPrice,
            $itemDescription,
            $itemImage
        ]);

        echo json_encode("Item added successfully");
    }
    // data type between JS, PHP, DB == all same
    // spelling column Field, PHP === DB
    // length ng data, <= length ng database

?>
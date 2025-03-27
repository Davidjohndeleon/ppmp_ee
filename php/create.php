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
            $defaultImagePath = '../source/inventory_image/item_1.png';
            $itemImage = file_get_contents($defaultImagePath);
        }

        $sql = "INSERT INTO imiss_inventory (itemName, itemPrice, itemDescription, itemImage) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $itemName,
            $itemPrice,
            $itemDescription,
            $itemImage
        ]);
        
        $itemID = $pdo->lastInsertId();

        echo json_encode([
            'itemID' => $itemID,    
            'itemName' => $itemName,
            'itemPrice' => $itemPrice,
            'itemDescription' => $itemDescription,
            'itemImage' => "data:image/jpeg;base64," . base64_encode($itemImage)
        ]);
    }
    // data type between JS, PHP, DB == all same
    // spelling column Field, PHP === DB
    // length ng data, <= length ng database

?>
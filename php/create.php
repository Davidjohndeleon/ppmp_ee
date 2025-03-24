<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    $sql = "INSERT INTO imiss_inventory (itemName, itemPrice, itemDescription, itemImage) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['itemName'],
        $_POST['itemPrice'],
        $_POST['itemDescription'],
        $_POST['itemImage'],
    ]);

    // data type between JS, PHP, DB == all same
    // spelling column Field, PHP === DB
    // length ng data, <= length ng database

    echo json_encode($_POST);

?>
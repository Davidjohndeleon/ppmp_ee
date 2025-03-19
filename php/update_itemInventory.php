<?php 
    // session_start();
    include('../assets/connection/sqlconnection.php');

    $sql = "UPDATE imiss_inventory SET itemName=?, itemPrice=?, itemDescription=? WHERE itemID=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['itemNameKey'],
        $_POST['itemPriceKey'],
        $_POST['itemDescriptionKey'],
        (int)$_POST['itemIDKey'],
    ]);

    // data type between JS, PHP, DB == all same
    // spelling column Field, PHP === DB
    // length ng data, <= length ng database

    echo json_encode($_POST);
?>
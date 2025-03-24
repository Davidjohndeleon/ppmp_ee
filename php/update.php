<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    $sql = "UPDATE imiss_inventory SET itemName=?, itemPrice=?, itemDescription=?, itemImage=? WHERE itemID=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['itemName'],
        $_POST['itemPrice'],
        $_POST['itemDescription'],
        $_POST['itemImage'],
        (int)$_POST['itemID']
    ]);

    echo json_encode($_POST);
?>
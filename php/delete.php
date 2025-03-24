<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    $sql = "DELETE FROM imiss_inventory WHERE itemID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        (int) $_POST['itemID']
    ]);
?>
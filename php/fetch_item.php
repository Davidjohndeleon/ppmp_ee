<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    $stmt = $pdo->prepare("SELECT * FROM imiss_inventory");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
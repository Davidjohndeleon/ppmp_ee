<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    
        $itemID = (int) $_POST['itemID'];
        
        $sql = "DELETE FROM imiss_inventory WHERE itemID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$itemID]);
        
        echo json_encode(['message' => 'success']);
?>
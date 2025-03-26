<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');
        $itemID = (int)$_POST['itemID'];
        $itemName = $_POST['itemName'];
        $itemPrice = $_POST['itemPrice'];
        $itemDescription = $_POST['itemDescription'];
    
        if (isset($_FILES['itemImage']) && $_FILES['itemImage']['error'] == 0) {
            $imageData = file_get_contents($_FILES['itemImage']['tmp_name']);
    
            $sql = "UPDATE imiss_inventory SET itemName = ?, itemPrice = ?, itemDescription = ?, itemImage = ? WHERE itemID = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $itemName,
                $itemPrice,
                $itemDescription,
                $imageData,
                $itemID
            ]);
            $itemImage = "data:image/jpeg;base64," . base64_encode($imageData);
        } else {
            $sql = "UPDATE imiss_inventory SET itemName = ?, itemPrice = ?, itemDescription = ? WHERE itemID = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $itemName,
                $itemPrice,
                $itemDescription,
                $itemID
            ]);
            // Fetch existing image if  nno new image is uploaded
            $sql = "SELECT itemImage FROM imiss_inventory WHERE itemID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$itemID]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
            // Checker if image exist
            $itemImage = $existingItem ? "data:image/jpeg;base64," . base64_encode($existingItem['itemImage']) : null;
        }

        //response
        echo json_encode([
            'itemID' => $itemID,
            'itemName' => $itemName,
            'itemPrice' => $itemPrice,
            'itemDescription' => $itemDescription,
            'itemImage' => $itemImage
        ])
?>
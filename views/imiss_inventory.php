<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $itemID = $_POST['itemID'];
    
        if ($_FILES['itemImage']['error'] == 0) {
            $imageData = file_get_contents($_FILES['itemImage']['tmp_name']);
    
            try {
                $sql = "UPDATE imiss_inventory SET itemImage = :image WHERE itemID = :id"; 
                $stmt = $pdo->prepare($sql);
                
                $stmt->bindParam(":image", $imageData, PDO::PARAM_LOB);
                
                $stmt->bindParam(":id", $itemID, PDO::PARAM_INT);
    
                if ($stmt->execute()) {
                    header("Location: imiss_inventory.php");
                    exit();
                }
            } catch (PDOException $e) {
                die("Upload Failed: " . $e->getMessage());
            }
        }
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/item_crud.css">
    <?php require "../links/header_link.php" ?>
</head>
<body>
    <?php 
        $view = "imiss-inventory-sub-div";
        include("./sidebar.php")
    ?>
    
    <div class="right-container">

        <div class="function-bar">
            <div class="search-bar">
                <input type="text" id="search-input"/>
                <button id="search-btn">Search</button>
            </div>

            <div class="add-item-function">
                <button id="add-item">Add Item</button>
            </div>
        </div>

        <div class="inventory-div">
            <?php for($i = 0; $i < count($item_data); $i++){
                $image = !empty($item_data[$i]['itemImage']) 
                ? 'data:image/...;base64,' . base64_encode($item_data[$i]['itemImage']) 
                : '../source/inventory_image/item_1.png';
            ?>
                <div class="tiles-div" id="tile-div-1">
                    <img class="item-img" src="<?php echo $image; ?>" alt="item-image">
                    
                    <p class="item-description"><?php echo $item_data[$i]['itemName'] ?> <span style="display:none" class="item-id"><?php echo $item_data[$i]['itemID'] ?></span></p>
                    <span class="item-price">P 80,000.00</span>
                    
                    <div id="update-delete-button">
                        <button data-itemid="<?php echo $item_data[$i]['itemID']; ?>" id="update-function" data-bs-toggle="modal">Update</button>
                        <button data-itemid="<?php echo $item_data[$i]['itemID']; ?>" id="delete-function" data-bs-toggle="modal">Delete</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php require "../links/script_links.php" ?>
    <script> 
        var section = "<?php echo $section ?>";
    </script>
    <script src="../js/home_traverse.js?v=<?php echo time(); ?>"></script>
    <script src="../js/imiss_inventory.js?v=<?php echo time(); ?>"></script>
</body>
</html>
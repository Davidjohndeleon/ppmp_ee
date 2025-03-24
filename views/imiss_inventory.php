<?php
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    // Retrieve any message from the session and clear it
    $message = '';
    if (isset($_SESSION['success_message'])) {
        $message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
    } elseif (isset($_SESSION['error_message'])) {
        $message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    }

    $stmt = $pdo->prepare("SELECT * FROM imiss_inventory");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/imiss_inventory.css">
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
                <input type="text" id="search-input" />
                <button id="search-btn">Search</button>
            </div>

            <div class="add-item-function">
                <button class="add-item" data-bs-toggle="modal" data-bs-target="#modal-add-item">Add Item</button>
            </div>
        </div>

        <div class="inventory-div">
            <?php foreach ($items as $item): ?>
                <div class="tiles-div" id="tile-div-<?php echo $item['itemID']; ?>">
                    <img class="item-image" src="data:image/jpeg;base64,<?php echo base64_encode($item['itemImage']); ?>" alt="item-image">
                    <p class="item-description">
                    <?php echo htmlspecialchars(strlen($item['itemName']) > 80 ? substr($item['itemName'], 0, 80) . '...' : $item['itemName']); ?>

                    </p>
                    <span class="item-price">₱ <?php echo $item['itemPrice']; ?></span>
                    <div class="update-delete-button">
                        <button class="update-function"
                            data-itemid="<?php echo $item['itemID']; ?>"
                            data-itemname="<?php echo htmlspecialchars($item['itemName']); ?>"
                            data-itemprice="<?php echo $item['itemPrice']; ?>"
                            data-itemdescription="<?php echo htmlspecialchars($item['itemDescription']); ?>"
                            data-itemimage="data:image/jpeg;base64,<?php echo base64_encode($item['itemImage']); ?>"
                            data-bs-toggle="modal" data-bs-target="#modal-update-item">
                            Update
                        </button>
                        <button class="delete-function" data-itemid="<?php echo $item['itemID']; ?>" data-bs-toggle="modal" data-bs-target="#modal-delete-item">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add modal form -->
    <div class="modal fade" id="modal-add-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered custom-modal-width modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Add Item</h5>
                </div>
                <div id="modal-body-incoming" class="modal-body-incoming">
                    <form id="addItem-form" enctype="multipart/form-data">
                        <div class="item_name">
                            <label for="itemName">Item Name</label><br>
                            <input type="text" id="itemName" name="itemName" required>
                        </div>

                        <div class="item_price">
                            <label for="itemPrice">Item Price</label><br>
                            <input type="number" id="itemPrice" name="itemPrice" required>
                        </div>

                        <div class="item_description">
                            <label for="itemDescription">Item Description</label><br>
                            <input type="text" id="itemDescription" name="itemDescription" >
                        </div>

                        <div class="item_image">
                            <label for="itemImage">Item Image</label><br>
                            <input type="file" id="itemImage" name="itemImage" accept="image/*" required>
                        </div>

                        <div class="modal-footer">
                            <button id="close-modal-btn-incoming" type="button" data-bs-dismiss="modal">Close</button>
                            <button id="add-item-btn">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update modal form -->
    <div class="modal fade" id="modal-update-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered custom-modal-width modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Update Item</h5>
                </div>

                <div id="modal-body-incoming" class="modal-body-incoming">
                    <form id="updateItem-form" enctype="multipart/form-data">
                        <input type="hidden" name="itemID" id="update-itemID">

                        <div class="item_name">
                            <label for="itemName">Item Name</label><br>
                            <input type="text" id="update-itemName" name="itemName" required>
                        </div>

                        <div class="item_price">
                            <label for="itemPrice">Item Price</label><br>
                            <input type="number" id="update-itemPrice" name="itemPrice" required>
                        </div>

                        <div class="item_description">
                            <label for="itemDescription">Item Description</label><br>
                            <input type="text" id="update-itemDescription" name="itemDescription" required>
                        </div>

                        <div class="item_image">
                            <label for="itemImage">Item Image</label><br>
                            <img id="updateitem-image-preview"  alt="Current Image" style="max-height: 80px; margin-top: 10px;"><br>
                            <input type="file" id="update-itemImage" name="itemImage" accept="image/*">
                        </div>

                        <div class="modal-footer">
                            <button id="close-modal-btn-incoming" type="button" data-bs-dismiss="modal">Close</button>
                            <button id="update-item-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="modal-delete-item" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal">Cancel</button>
                    <button id="delete-item-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var modalMessage = <?php echo json_encode($message); ?>;
    </script>

    <?php require "../links/script_links.php" ?>
    <script>
        var section = "<?php echo $section ?>";
    </script>
    <script src="../js/home_traverse.js?v=<?php echo time(); ?>"></script>
    <script src="../js/imiss_inventory.js?v=<?php echo time(); ?>"></script>
</body>

</html>
<?php 
    session_start();
    include('../assets/connection/sqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    // Fetch item data from the database (You may have already fetched this earlier in your code)
    $item_data = []; // Ensure this contains your inventory data from the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP-PPMPee</title>
    <link rel="stylesheet" href="../css/home.css">

    <?php require "../links/header_link.php" ?>
</head>
<body>
    
    <?php 
        $view = "inventory-list-sub-div";
        include("./sidebar.php")
    ?>

    <div class="right-container">

        <div class="function-bar">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search for items..."/>
                <button id="search-btn">Search</button>
            </div>
            
            <div class="cart-div">
                <img id="item-img-animation" src="../source/inventory_image/item_1.png" alt="item-1-img">
                <span id="notif-value">0</span>
                <i class="fa-solid fa-cart-shopping" id="cart-icon" data-bs-toggle="modal" data-bs-target="#modal-place-order"></i>
            </div>
        </div>

        <div class="inventory-div">
            <?php 
            // Loop through the item data and display it
            for($i = 0; $i < count($item_data); $i++) {
                // Fetch image path from database if available, otherwise use a default
                $imagePath = !empty($item_data[$i]['itemImage']) ? $item_data[$i]['itemImage'] : "../source/inventory_image/default.png";
            ?>
                <div class="tiles-div" id="tile-div-<?php echo $item_data[$i]['itemID']; ?>">
                    <img class="item-img" src="<?php echo $imagePath; ?>" alt="item-image-<?php echo $item_data[$i]['itemID']; ?>">
                    
                    <p class="item-description">
                        <?php echo $item_data[$i]['itemName']; ?> 
                        <span style="display:none" class="item-id"><?php echo $item_data[$i]['itemID']; ?></span>
                    </p>
                    <span class="item-price">P <?php echo number_format($item_data[$i]['itemPrice'], 2); ?></span>
                    
                    <div class="function-div">
                        <div class="add-div">
                            <button class="minus-btn">-</button>
                            <span class="current-total-span">0</span>
                            <button class="add-btn">+</button>
                        </div>
                        <button class="add-to-cart-btn" data-item-id="<?php echo $item_data[$i]['itemID']; ?>">Add to Cart</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Modal for Cart Review -->
    <div class="modal fade" id="modal-place-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered custom-modal-width modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Your Cart</h5>
                </div>
                <div id="modal-body-incoming" class="modal-body-incoming ml-2">
                    <table id="cart-table" class="display">
                        <thead>
                            <tr>
                                <th>IMAGE</th>
                                <th>PRODUCT</th>
                                <th>PRICE</th>
                                <th>QUANTITY</th>
                                <th>SUBTOTAL</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>

                        <tbody id="cart-items">
                            <!-- Cart items will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="close-modal-btn-incoming" type="button" data-bs-dismiss="modal">CLOSE</button>
                    <button id="placeorder-btn" type="button">PLACE ORDER</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Notifications -->
    <div class="modal fade" id="modal-notif" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Your Cart</h5>
                </div>
                <div id="modal-body-incoming" class="modal-body-incoming ml-2">
                    <!-- Notification content here -->
                </div>
                <div class="modal-footer">
                    <button id="close-modal-btn-incoming" type="button" data-bs-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    <?php require "../links/script_links.php" ?>
    <script>
        var section = "<?php echo $section ?>";
    </script>
    <script src="../js/home_traverse.js?v=<?php echo time(); ?>"></script>
    <script src="../js/home_function.js?v=<?php echo time(); ?>"></script>

</body>
</html>

<?php
    if (isset($_SESSION['user_id'])) { 
        $userID = $_SESSION['user_id'];

        if (isset($_SERVER['HTTP_REFERER'])) {
            $httpReferer = ($_SERVER['HTTP_REFERER']);
    
            if (str_ends_with($httpReferer,'cart.php')) { updateCartOnButtonClick(); }
            else { updateCartOnDrop(); }
        }
    }

    /* Functions */
    
    function updateCartOnButtonClick() {
        if (isset($_POST['updateItemID']) && isset($_POST['updateQty'])) {
            // retrieve id of item that will have its qty updated
            $updateItemID = $_POST['updateItemID'];
            // retrieve what type of button was clicked (+/-)
            $updateQty = $_POST['updateQty'];

            // retrieve the item's corresponding entry in the Item table
            $sql = "SELECT * FROM Item WHERE item_id = " . "$updateItemID"; 
            $result = $GLOBALS['conn']->query($sql);
            $row = $result->fetch_assoc();

            if (isset($row['sales_price']) && $row['sales_price'] > 0) {
                $itemPrice = $row['sales_price'];
            } else {
                $itemPrice = $row['price'];
            }

            // Update the item's entry (price & qty) in the Shopping Cart table according
            // to the type of button that was clicked
            if ($updateQty == 'increase') {
                $sql = "UPDATE ShoppingCart SET quantity = quantity+1, price = price+$itemPrice WHERE item_id = $updateItemID";
                $result = $GLOBALS['conn']->query($sql);
            }
            else {
                // Check the current quantity before decreasing
                $sql = "SELECT quantity, price FROM ShoppingCart WHERE item_id = $updateItemID";
                $result = $GLOBALS['conn']->query($sql);
                $row = $result->fetch_assoc();
                
                if ($row && $row['quantity'] > 1) {
                    // Only decrease if quantity is greater than 1 to prevent negative values
                    $sql = "UPDATE ShoppingCart SET quantity = quantity-1, price = price-$itemPrice WHERE item_id = $updateItemID";
                    $result = $GLOBALS['conn']->query($sql);
                } else {
                    // If quantity reaches zero, delete the item immediately
                    $sql = "DELETE FROM ShoppingCart WHERE item_id = $updateItemID";
                    $result = $GLOBALS['conn']->query($sql);
                }
            }            
        }
    }
    function updateCartOnDrop() {
        if (isset($_POST['droppedItemID']) && isset($_SESSION['user_id'])) {
            // retrieve the logged-in user ID
            $userID = $_SESSION['user_id']; 
            
            // retrieve dropped item's id
            $droppedItemID = $_POST['droppedItemID']; 
    
            // retrieve its corresponding entry in the Item table
            $sql = "SELECT * FROM Item WHERE item_id = " . $droppedItemID; 
            $result = $GLOBALS['conn']->query($sql);
            $row = $result->fetch_assoc();
    
            if (isset($row['sales_price']) && $row['sales_price'] > 0) {
                $itemPrice = $row['sales_price'];
            } else {
                $itemPrice = $row['price'];
            } 
    
            // check if the item already exists in the cart for this user
            $sql = "SELECT * FROM ShoppingCart WHERE item_id = $droppedItemID AND user_id = $userID";
            $result = $GLOBALS['conn']->query($sql);
    
            if ($result->num_rows > 0) {
                // update quantity and price for this user's cart
                $sql = "UPDATE ShoppingCart SET quantity = quantity+1, price = price+$itemPrice WHERE item_id = $droppedItemID AND user_id = $userID";
                $result = $GLOBALS['conn']->query($sql);
            } else {
                // insert new item for this specific user
                $sql = "INSERT INTO ShoppingCart(item_id, user_id, quantity, price) VALUES($droppedItemID, $userID, 1, $itemPrice)";
                $result = $GLOBALS['conn']->query($sql);
            }
        }
    }    
?>

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
            $itemPrice = $row['price']; 

            // Update the item's entry (price & qty) in the Shopping Cart table according
            // to the type of button that was clicked
            if ($updateQty == 'increase') {
                $sql = "UPDATE ShoppingCart SET quantity = quantity+1, price = price+$itemPrice WHERE item_id = $updateItemID";
                $result = $GLOBALS['conn']->query($sql);
            }
            else {
                $sql = "UPDATE ShoppingCart SET quantity = quantity-1, price = price-$itemPrice WHERE item_id = $updateItemID";
                $result = $GLOBALS['conn']->query($sql);

                $sql = "SELECT * FROM ShoppingCart WHERE item_id = $updateItemID";
                $result = $GLOBALS['conn']->query($sql);
                $row = $result->fetch_assoc();
                
                if ($row['quantity'] == 0) {
                    $sql = "DELETE FROM ShoppingCart WHERE item_id = $updateItemID";
                    $result = $GLOBALS['conn']->query($sql);
                } 
            }
        }
    }
    function updateCartOnDrop() {
        if (isset($_POST['droppedItemID'])) {
            // retrieve dropped item's id
            $droppedItemID = $_POST['droppedItemID']; 

            // retrieve its corresponding entry in the Item table
            $sql = "SELECT * FROM Item WHERE item_id = " . $droppedItemID; 
            $result = $GLOBALS['conn']->query($sql);
            $row = $result->fetch_assoc();
            
            $itemPrice = $row['price']; 

            // update Shopping Cart table accordingly
            $sql = "SELECT * FROM ShoppingCart WHERE item_id = " . "$droppedItemID";
            $result = $GLOBALS['conn']->query($sql);

            // if an instance of the item is already in the table, update its entry's price and qty
            if ($result->num_rows > 0) {
                $sql = "UPDATE ShoppingCart SET quantity = quantity+1, price = price+$itemPrice WHERE item_id = $droppedItemID";
                $result = $GLOBALS['conn']->query($sql);
            }
            // if not, add a new entry w qty=1
            else {
                $userID = $GLOBALS['userID'];
                $sql = "INSERT INTO ShoppingCart(item_id,user_id,quantity,price) VALUES($droppedItemID,$userID,1,$itemPrice)";
                $result = $GLOBALS['conn']->query($sql);
            }
        }
    }
?>

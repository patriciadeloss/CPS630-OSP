<?php
    session_start();
    include("database.php");

    $userID = $_SESSION['user_id'];

    // retrieve dropped item's price from Item table
    $droppedItemID = $_POST['itemID'];
    $sql = "SELECT * FROM Item WHERE item_id = " . "$droppedItemID";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $itemName = $row['item_name'];
    $itemPrice = $row['price'];

    // update Shopping Cart table accordingly
    $sql = "SELECT * FROM ShoppingCart WHERE item_id = " . "$droppedItemID";
    $result = $conn->query($sql);

    // if an instance of the item is already in the table, update its entry's price and qty
    if ($result->num_rows > 0) {
        $sql = "UPDATE ShoppingCart SET quantity = quantity+1, price = price+$itemPrice WHERE item_id = $droppedItemID";
        $result = $conn->query($sql);
    }
    // if not, add a new entry w qty=1
    // ** order is currently fixed !!
    else {
        $sql = "INSERT INTO ShoppingCart(order_id,user_id,item_id,quantity,price) VALUES($userID,1,$droppedItemID,1,$itemPrice)";
        $result = $conn->query($sql);
    }

    // print user's shopping cart
    $sql = "SELECT * FROM ShoppingCart WHERE order_id = 1 AND user_id = $userID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($cartRow = $result->fetch_assoc()) {
            // for the curr fetched item from the Cart table,
            // use its item_id to retrieve its item name from the Item table
            $currItemID = $cartRow['item_id'];
            $sql = "SELECT * FROM Item WHERE item_id = " . "$currItemID";
            $result2 = $conn->query($sql); // srry, ik result2 might be a bad var namee
            $itemRow = $result2->fetch_assoc();

            echo '<p> Item ID: ' . htmlspecialchars($currItemID) . ',' .
                    ' Item Name: ' . htmlspecialchars($itemRow['item_name']) . ',' .
                    ' Quantity: ' . htmlspecialchars($cartRow['quantity']) . ',' .
                    ' Price: ' . htmlspecialchars($cartRow['price']) . '</p>';
        }
    }
?>  

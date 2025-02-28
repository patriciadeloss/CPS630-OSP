<?php
    include("database.php");

    // retrieve item price from Item table
    $itemID = $_POST['itemID'];
    $sql = "SELECT * FROM Item WHERE item_id = " . "$itemID";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $itemName = $row['item_name'];
    $itemPrice = $row['price'];

    // update Shopping Cart table accordingly
    $sql = "SELECT * FROM ShoppingCart WHERE item_id = " . "$itemID";
    $result = $conn->query($sql);

    // if an instance of the item is already in the table, update its entry's price and qty
    if ($result->num_rows > 0) {
        $sql = "UPDATE ShoppingCart SET quantity = quantity+1, price = price+$itemPrice WHERE item_id = $itemID";
        $result = $conn->query($sql);
    }
    // if not, add a new entry w qty=1
    // ** user id & order are currently fixed !!
    else {
        $sql = "INSERT INTO ShoppingCart(order_id,user_id,item_id,quantity,price) VALUES(1,1,$itemID,1,$itemPrice)";
        $result = $conn->query($sql);
    }

    // print user's shopping cart
    $sql = "SELECT * FROM ShoppingCart WHERE order_id = 1 AND user_id = 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<p> Item ID: ' . htmlspecialchars($itemID) . ',' .
                    ' Item Name: ' . htmlspecialchars($itemName) . ',' .
                    ' Quantity: ' . htmlspecialchars($row['quantity']) . ',' .
                    ' Price: ' . htmlspecialchars($row['price']) . '</p>';
        }
    }
?>  
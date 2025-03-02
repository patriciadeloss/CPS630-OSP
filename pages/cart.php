<?php
    session_start();
    include("../external-php-scripts/database.php");


    if (isset($_SESSION['account_type'])) {
        $userID = $_SESSION['user_id'];

        // Differentiates the 2 ways a user navigates to the cart - dropping an item or
        // simply clicking on the icon
        // This if statement accounts for the first case
        if (isset($_POST['itemID'])) {
            // retrieve dropped item's id
            $droppedItemID = $_POST['itemID']; 
            // retrieve its corresponding entry in the Item table
            $sql = "SELECT * FROM Item WHERE item_id = " . "$droppedItemID"; 
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            
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
            // *** NOTE: order id is currently fixed !!
            else {
                $sql = "INSERT INTO ShoppingCart(order_id,item_id,user_id,quantity,price) VALUES(1,$droppedItemID,$userID,1,$itemPrice)";
                $result = $conn->query($sql);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online Web Service Platform</title>
        <link rel="stylesheet" href="../css/base-style.css">
        <link rel="stylesheet" href="../css/cart-style.css">
    </head>

    <body>
        <header id="top-navbar">
            <div class="logo">
                <a href="index.php">
                    <img src="../img/logo.png" alt="Logo" class="logo-img">
                    <p>Name</p>
                </a>
            </div>

            <form class="search-container" action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search">
                <button type="submit">Go</button>
            </form>

            <div class="login">
                <a href="cart.php" class="cart-icon" id="cartIcon">
                    <div id="cart" class="cart-dropzone" ondrop="drop(event)" ondragover="allowDrop(event)">
                        <img src="../img/shopping-cart.png" alt="Shopping Cart">
                    </div>
                </a>
        
                <?php if (isset($_SESSION['account_type'])) { ?>
                    <!-- If the user is logged in, show the Logout button -->
                    <a href="logout.php" class="signin-btn">Logout</a>
                <?php } else { ?>
                    <!-- If the user is not logged in, show the Sign In button -->
                    <a href="signin.php" class="signin-btn">Sign In</a>
                <?php } ?>
            </div>
        </header>

        <div class="container">
            <h1>Your Shopping Cart</h1>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="cart-container">
                    <?php
                        $grandTotal = 0.00;
                        $grandQty = 0;
                    ?>
                    <!-- If user is logged in, display cart -->
                    <?php if (isset($_SESSION['account_type'])) { ?>
                        <!-- display user's shopping cart items -->
                        <?php
                            // retrieve a user's entries for their curr order from the Shopping Cart Table
                            // *** NOTE: order id is currently fixed !!
                            $sql = "SELECT * FROM ShoppingCart WHERE order_id = 1 AND user_id = $userID";
                            $result = $conn->query($sql);
                            $orderid = 1;

                            if ($result->num_rows > 0) {
                                while ($cartRow = $result->fetch_assoc()) {
                                    // for the curr fetched item from the Cart table,
                                    
                                    // add its total price to the order total,
                                    $grandTotal = $grandTotal+$cartRow['price'];
                                    // increment item count of order,
                                    $grandQty = $grandQty+$cartRow['quantity'];

                                    // and use its item_id to retrieve its item name from the Item table
                                    $sql = "SELECT * FROM Item WHERE item_id = " . $cartRow['item_id'];
                                    $result2 = $conn->query($sql); 
                                    $itemRow = $result2->fetch_assoc();
                                    
                                    echo "
                                        <tr>
                                            <td class='child-left'>
                                                <img src='../img/{$itemRow['image_url']}' alt=''>
                                                <div id='itemInfo'>
                                                    <h3>" . htmlspecialchars($itemRow['item_name']) . "</h3>
                                                    <p>Item details</p>
                                                </div>
                                            </td>
                                            <td id='price'> $" . htmlspecialchars($itemRow['price']) . "</td>
                                            <td id='amountSelector'>
                                                <button>-</button>
                                                <p id='amount'>" . htmlspecialchars($cartRow['quantity']) . "</p>
                                                <button>+</button>
                                            </td>
                                            <td> $" . htmlspecialchars($cartRow['price']) . "</td>
                                        </tr>
                                    ";
                                }
                            } else {
                                echo "
                                    <tr>
                                        <td class=\"span-all\"> <p style=\"text-align: center;\"> Your Shopping Cart is Empty </p> </td>
                                    </tr>
                                ";
                            }
                        ?>
                    
                    <?php } else { ?>
                    <!-- If not logged in, display message -->
                    <tr>
                        <td class="span-all"> 
                            <p style="text-align: center;"> You are currently not signed in. <a href="signin.php">Sign in</a> to view your Shopping Cart </p> 
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <footer class="overview">
            <div id="p1">
                <?php echo "<p>Number of items: <span id='numItems'>" . $grandQty . "</span></p>" ?>
                <p>Discounts: $<span id="discount">0.00</span></p>
                <?php 
                    $tax = $grandTotal*0.13;
                    //number_format to format 2 decimal places
                    echo "<p>Tax: $<span id='tax'>" . number_format(round($tax,2), 2) . "</span></p>"
                ?>
            </div>
            <div id="p2">
                <!-- number_format to format 2 decimal places -->
                <?php echo "<h2>Grand Total: $<span id='grandTotal'>" . number_format(round($grandTotal+$tax,2),2) . "</span></h2>" ?>
                <a href="payments.php?orderid=<?php echo $orderid?>"><button>Check Out</button></a>
            </div>
        </footer>
    </body>
</html>

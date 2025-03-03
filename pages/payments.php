
<?php 
    include("../external-php-scripts/database.php");
    session_start();
    //hide PHP warning messages
    error_reporting(E_ERROR);
    $orderid = $_GET['orderid'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online Web Service Platform</title>
        <link rel="stylesheet" href="../css/base-style.css">
        <link rel="stylesheet" href="../css/payments-style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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


    <?php //only allow payment access if valid session and order number
    if ($orderid != NULL && isset($_SESSION['account_type'])) {
        $userID = $_SESSION['user_id']; ?>
        <div class="subheader">
            <h1>Payments</h1> <hr>
        </div>
        
        <div class="container2">
            
            <div class="summary-container">
                <h2>Order Summary:</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Quantity</th>
                            <th>Item</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $subtotal = 0.00;
                        $grandTotal = 0.00;
                        $grandQty = 0;
                        $tax = 0.00;
                        //Referenced from shopping cart
                        //orderid is currently fixed
                        $sql = "SELECT * FROM ShoppingCart WHERE order_id = 1 AND user_id = $userID";
                        $res = $conn->query($sql);

                        if ($res->num_rows > 0) {
                            while($cartRow = $res->fetch_assoc()) {
                                //add price to subtotal
                                $subtotal += $cartRow['price'];
                                //increment total item count
                                $grandQty += $cartRow['quantity'];

                                //retreive item details from item table
                                $sql = "SELECT * FROM Item WHERE item_id = " . $cartRow['item_id'];
                                $res2 = $conn->query($sql);
                                $itemRow = $res2->fetch_assoc();
                                
                                //htmlspecialchars to interpret as chars, not html code
                                echo "
                                    <tr>
                                        <td>" . htmlspecialchars($cartRow['quantity']) . " x </td>
                                        <td>" . htmlspecialchars($itemRow['item_name']) . "</td>
                                        <td>" . htmlspecialchars($cartRow['price']) . "</td>
                                    </tr>
                                ";
                                
                            }
                        }
                    ?>
                    </tbody>
                </table>
                <div class="overview">
                    <?php 
                        $tax = $subtotal*0.13; 
                        $grandTotal = $subtotal + $tax;
                    ?>
                    <div id="p1">
                        <p>Number of Items: <?php echo $grandQty ?></p>
                        <p>Discount: $0.00</p>
                        <p>Tax: $<?php echo number_format(round($tax, 2),2); ?></p>
                    </div>
                    <div id="p2">
                        <div id="totals">
                            <p>Subtotal: $<?php echo number_format(round($subtotal, 2),2); ?></p>
                            <h3>Grand Total: $<?php echo number_format(round($grandTotal, 2),2);?></h3>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="payments-container">
                <h3>Payment Details:</h3>
                <form action="confirmation.php" method="POST">
                    <input type="text" value="<?php echo $orderid; ?>" name="orderid" style="display: none;" readonly>
                    <label for="cardholder_name">Cardholder Name:</label>
                    <input type="text" id="cardholder_name"  name="cardholder_name" placeholder="Cardholder Name">
                    <label for="card_number">Card Number:</label>
                    <input type="number" id="card_number" name="card_number" placeholder="Card Number">
                    <div class="child-left" id="p3">
                        <label for="exp_month">Expiry:</label>
                        <label for="cvc">CVC:</label>
                    </div>
                    <div class="child-left" id="p4">
                        <select name="exp_month" id="exp_month">
                            <option value="" selected disabled>MM</option>
                            <option value="01" >01</option>
                            <option value="02" >02</option>
                            <option value="03" >03</option>
                            <option value="04" >04</option>
                            <option value="05" >05</option>
                            <option value="06" >06</option>
                            <option value="07" >07</option>
                            <option value="08" >08</option>
                            <option value="09" >09</option>
                            <option value="10" >10</option>
                            <option value="11" >11</option>
                            <option value="12" >12</option>
                        </select>
                        <select name="exp_year" id="exp_year">
                            <option value="" selected disabled>YYYY</option>
                        </select>
                        <input type="text" id="cvc" name="cvc" maxlength="3"> </br>
                    </div>
                    <div id="payment_icons">
                        <img src="../img/icons/visa.png" alt="">
                        <img src="../img/icons/mastercard.png" alt="">
                        <img src="../img/icons/jcb.png" alt="">
                        <img src="../img/icons/amex.png" alt="">
                    </div>
                    <button>Pay Now</button>
                </form>
            </div>
        </div>
    <?php } else { 
        //Else, return page not found error
        ?>
        <div class="error">
            <h1 >404 - Page Not Found </h1>
            <p>Looks like that page doesn't exist. Return to <a href="index.php">Home</a>?</p>
        </div>
    <?php }?>
    </body>

    <script>
        $(document).ready(function() {
            //get current year
            let yr = new Date().getFullYear();
            var content = document.getElementById("exp_year").innerHTML;
            //javascript to create options for expiry year dropdown 
            //based on current year
            for (let i = 0; i < 8; i++) {
                n = yr + i;
                console.log(n);
                content = content + `<option value="${n}"> ${n} </option>`;
            }
            document.getElementById("exp_year").innerHTML = content;
        });
    </script>
</html>

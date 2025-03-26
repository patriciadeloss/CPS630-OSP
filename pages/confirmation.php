<?php 
session_start();
include("header.php"); 
include("../external-php-scripts/database.php");
include("../external-php-scripts/security.php"); 
// Superglobal used to collect form data via the POST method
$grand_total = $_POST['grandTotal'];
$payment_method = $_POST['payment_method'];
// Superglobal used to store session variables across multiple pages
$user_id = $_SESSION['user_id'];
$store_code = $_SESSION['branch_code'];
$cur_date = date("Y-m-d"); //get current date

//create entry in database for Shopping and output generated value
$sql = "INSERT INTO Shopping (store_code, total_price) VALUES ('" . $store_code . "', $grand_total)";
$conn->query($sql);

//retrieve generated receipt_id from Shopping table
//insert_id returns the ID generated in the last query
$receipt_id = $conn->insert_id;

if (isset($_POST['card_number'])) {
    $card_number = $_POST['card_number'];
    $last_four = substr($card_number, -4); // get the last four digits of card number
} else {
    $card_number = null;
}

if (isset($_POST['gift_card_number'])) {
    $gift_card_number = $_POST['gift_card_number'];
} else {
    $gift_card_number = null;
}

//create entry for orders table
//**currently doesn't create entries for payment_code and trip_id fields
if ($payment_method === 'card' && $card_number) {
    $order_id = insertOrder($cur_date, $cur_date, $grand_total, $user_id, $receipt_id, $card_number);

} elseif ($payment_method === 'giftCard' && $gift_card_number) {
    $order_id = insertOrder($cur_date, $cur_date, $grand_total, $user_id, $receipt_id, $gift_card_number);

} elseif ($payment_method === 'cash' && isset($_POST['cash_amount'])) {
    $order_id = insertOrder($cur_date, $cur_date, $grand_total, $user_id, $receipt_id, "Cash Payment");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Web Service Platform</title>
    <link rel="stylesheet" href="../css/base-style.css">
    <link rel="stylesheet" href="../css/confirmation.css">
</head>
<body>
    <div class="container">
        <div id="thank-you">
            <img id="checkMark" src="../img/icons/check-mark.png" alt="">
            <h1>Thank you!</h1>
            <p>Your order [id #<?php echo $order_id; ?>] has been confirmed.</p>
            <p>Please review the details of your order below</p>
        </div>
    </div>
    <div class="container2">
        <div id="invoice">
            <h2>Invoice</h2>
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
                    $sql = "SELECT * FROM ShoppingCart WHERE user_id = $user_id";
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
                <tr>
                    <td colspan="3" class="summary">
                        <div class="overview">
                            <?php 
                                $tax = $subtotal*0.13; 
                                $grandTotal = $subtotal + $tax;
                            ?>
                            <div id="p1">
                                <p>Number of Items: <?php echo $grandQty ?></p>
                                <p>Tax: $<?php echo number_format(round($tax, 2),2); ?></p>
                                <p>Order ID: <?php echo $order_id; ?></p>
                                <p>Card Ending in <?php echo isset($_POST['card_number']) ? $last_four : "####"; ?></p>
                            </div>
                            <div id="p2">
                                <div id="totals">
                                    <p>Delivery: Free</p>
                                    <p>Subtotal: $<?php echo number_format(round($subtotal, 2),2); ?></p>
                                    <h3>Grand Total: $<?php echo number_format(round($grandTotal, 2),2);?></h3>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="map">
            <h2>Trip Details</h2>
            <iframe src="map.php" width="500px" height="500px" style="border:0;"></iframe>
        </div>
    </div>
</body>
</html>

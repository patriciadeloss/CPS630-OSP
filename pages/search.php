<?php
session_start();
include("../external-php-scripts/database.php");
echo '<link rel="stylesheet" type="text/css" href="../css/base-style.css">';
include("header.php");

// Initialize the table structure with the appropriate headers
echo "<div class='container'>";
echo "<h2 class='order-title'>Order Details</h2>";
echo "<table class='order-table' border='1'>
        <tr>
            <th>Order ID</th>
            <th>Date Issued</th>
            <th>Date Received</th>
            <th>Total Price</th>
            <th>Payment Code</th>
        </tr>";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Check if at least one order exists for the user
    $orders_sql = "SELECT 1 FROM Orders WHERE user_id = $user_id LIMIT 1";
    $orders_result = $conn->query($orders_sql);

    // If no orders exist, display message
    if ($orders_result->num_rows === 0) {
        echo "<tr><td colspan='5'>No matching orders found for Order ID</td></tr>";
    }

    if (isset($_GET['query']) && $orders_result->num_rows > 0) {
        $order_id = $_GET['query'];
        // Retrieve the headers for the table
        $sql = "SELECT order_id, date_issued, date_received, total_price, payment_code FROM Orders WHERE user_id = $user_id AND order_id = $order_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output the result rows
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["order_id"]) . "</td>
                        <td>" . htmlspecialchars($row["date_issued"]) . "</td>
                        <td>" . htmlspecialchars($row["date_received"]) . "</td>
                        <td>$" . number_format($row["total_price"], 2) . "</td>
                        <td>" . htmlspecialchars($row["payment_code"]) . "</td>
                    </tr>";
            }
        }
    }
} else {
    // User has to be signed in to view their orders
    echo "<tr><td colspan='5'>You must be logged in to view orders. Please <a href='signin.php'>sign in</a>.</td></tr>";
}

echo "</table>";
echo "</div>";

$conn->close();
?>

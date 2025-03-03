<?php
session_start();
include("../external-php-scripts/database.php");
echo '<link rel="stylesheet" type="text/css" href="../css/base-style.css">';
include("header.php");

// Initialize the table structure with headers
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

// Get search query
$searchQuery = $_GET['query'] ?? '';
$queryElement = preg_split('/,/', $searchQuery);
// Initialize search parameters
$user_id = null;
$order_id = null;

if (isset($queryElement[0])) {
    $user_id = (int) ($queryElement[0]);
}

if (isset($queryElement[1])) {
    $order_id = (int) ($queryElement[1]);
}

// User must be signed in to search
if (isset($_SESSION['user_id'])) {
    $session_user_id = $_SESSION['user_id'];
    $account_type = $_SESSION['account_type'];

    // Only run the query if user_id is set
    if ($user_id !== null) {
        $orders_sql = "SELECT 1 FROM Orders WHERE user_id = $user_id LIMIT 1";
        $orders_result = $conn->query($orders_sql);

        if ($orders_result && $orders_result->num_rows > 0) {
            // Query to retrieve order details
            // User can only view their own orders
            if ($account_type == 1 && $user_id == $session_user_id) {
                $sql = "SELECT order_id, date_issued, date_received, total_price, payment_code FROM Orders WHERE user_id = $user_id AND order_id = $order_id";
            } elseif ($account_type == 0) {
                // Admin can view any order
                $sql = "SELECT order_id, date_issued, date_received, total_price, payment_code FROM Orders WHERE user_id = $user_id";
            }

            // Run query only if $sql is set
            if (isset($sql)) {
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row["order_id"]) . "</td>
                                <td>" . htmlspecialchars($row["date_issued"]) . "</td>
                                <td>" . htmlspecialchars($row["date_received"]) . "</td>
                                <td>$" . number_format($row["total_price"], 2) . "</td>
                                <td>" . htmlspecialchars($row["payment_code"]) . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No matching orders found.</td></tr>";
                }
            }
        } else {
            echo "<tr><td colspan='5'>No orders found for this user.</td></tr>";
        }
    }
} else {
    echo "<tr><td colspan='5'>You must be logged in to view orders. Please <a href='signin.php'>sign in</a>.</td></tr>";
}

echo "</table>";
echo "</div>";

$conn->close();
?>

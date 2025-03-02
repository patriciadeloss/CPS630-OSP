<?php 
    session_start();
    include("header.php"); 
    include("../external-php-scripts/database.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base-style.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <?php if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 1) { ?>
        <!-- User Home Address & Branch Selection (Only for User) -->
        <div class="branch-container">
            <div class="user-home">
                <label>Home Address:</label>
                <span id="userAddress">123 Main St, City, Country</span>
            </div>

            <div class="branch-location">
                <form id="locationForm" action="" method="POST">
                    <label for="locations">Branch Location:</label>
                    <select name="location" id="locations" onchange="this.form.submit()">
                        <option value="">Select a location</option>
                        <option value="Location 1">Location 1</option>
                        <option value="Location 2">Location 2</option>
                        <option value="Location 3">Location 3</option>
                    </select>
                </form>
            
                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['location'])) {
                        // Store the selected location in a variable
                        $selected_location = $_POST['location'];
                    }
                ?>
            </div>
        </div>
    <?php } ?>
    
    <?php

        // Fetch items from the database
        try {
            $sql = "SELECT item_id, item_name, price, made_in, department_code, image_url FROM Item";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                echo '<div class="product-grid">';
        
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card" draggable="true" ondragstart="drag(event)" id="' . htmlspecialchars($row["item_id"]) . '">'
                            . '<img src="../img/' . htmlspecialchars($row["image_url"]) . '" alt="Product Image" ' . 'draggable="true" ondragstart="drag(event)" id="' . htmlspecialchars($row["item_id"]) . '">'
                            . '<h3>' . htmlspecialchars($row["item_name"]) . '</h3>'
                            . '<p class="price">$' . number_format($row["price"], 2) . '</p>'
                            . '<p class="details">Made in: ' . htmlspecialchars($row["made_in"]) . '</p>'
                            . '<p class="details">Dept code: ' . htmlspecialchars($row["department_code"]) . '</p>'
                        . '</div>';
                }
        
                echo '</div>';
            } else {
                echo '<p>No products found.</p>';
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }        
    ?>

</body>
</html>

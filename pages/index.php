<?php 
    session_start();
    include("header.php"); 
    include("../external-php-scripts/database.php"); 

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['home_address']) && isset($_POST['branch_location'])) {
            $home_address = $_POST['home_address']; // retrieve from input form
            $branch_location = $_POST['branch_location']; // retrieve branch location
            $_SESSION['home_address'] = $home_address; // Store in session
            $_SESSION['branch_location'] = $branch_location; // Store branch location in session

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                // Update the database with the new address
                $sql = "UPDATE Users SET address = '$home_address' WHERE user_id = $user_id";
                $result = $conn->query($sql);

                // Optionally, update the branch location in the database
                // $sql = "UPDATE Users SET branch_location = '$branch_location' WHERE user_id = $user_id";
                // $conn->query($sql);
            }
        }
    }
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
            <form id="addressForm" method="POST">
                <label for="home_address">Home Address:</label>
                <input type="text" id="home_address" name="home_address" placeholder="Enter your address"
                    value="<?php echo isset($_SESSION['home_address']) ? htmlspecialchars($_SESSION['home_address']) : ''; ?>" required>
                
                    <label for="branch_location">Branch Location:</label>
                    <select name="branch_location" id="branch_location">
                        <option value="">Select a location</option>
                        <?php 
                            // An array with the store's branch locations
                            $branches = ["300 Borough Dr, Scarborough, ON", "900 Dufferin St, Toronto, ON", "100 City Centre Dr, Mississauga, ON"];
                            // Loop through each branch and add the selected attribute to option
                            foreach ($branches as $branch) {  // Use $branches to loop
                                $selected = "";
                                // If a branch has been selected by the user and stored in the session,
                                // then add the selected attribute to that branch option
                                if (isset($_SESSION['branch_location']) && $_SESSION['branch_location'] == $branch) {
                                    $selected = "selected";
                                }
                                echo "<option value=\"$branch\" $selected>$branch</option>";
                            }
                        ?>
                    </select>

                <!-- This button submits both the home address and branch location in the form-->
                <button class="save-btn" type="submit">Save Address</button>
            </form>
        </div>
        <!-- Added a temporary link here to test the map functionality -->
        <a href="map.php" class="map-btn">View Map</a>
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

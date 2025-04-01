<?php 
    session_start();
    include("header.php"); 
    include("../external-php-scripts/database.php"); 

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['home_address']) && isset($_POST['branch_location'])) {
            $home_address = $_POST['home_address']; // retrieve from input form
            $branch_arr = $_POST['branch_location']; // retrieve branch info
            $branch_info = explode("//", $branch_arr); //explode string to revert back to array
            $branch_code = $branch_info[0]; //retrieve store code
            $branch_location = $branch_info[1]; // retrieve branch address
            $_SESSION['home_address'] = $home_address; // Store in session
            $_SESSION['branch_location'] = $branch_location; // Store branch location in session
            $_SESSION['branch_code'] = $branch_code; // Store branch code in session

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                // Update the database with the new address
                $sql = "UPDATE Users SET address = '$home_address' WHERE user_id = $user_id";
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
                    
                    <?php
                        //create an array of branch addresses based on database
                        $sql = "SELECT * FROM Branch";
                        $branch_res = $conn->query($sql);
                        $branches = array();

                        if ($branch_res->num_rows > 0) {
                            while ($branch_row = $branch_res->fetch_assoc()) {
                                //append information to branches array
                                $branches[] = array($branch_row['store_code'], $branch_row['branch_address']);
                            }
                        }
                    ?>
                    
                    <label for="branch_location">Branch Location:</label>
                    <select name="branch_location" id="branch_location">
                        <option value="">Select a location</option>
                        <?php 
                            // Loop through each branch and add the selected attribute to option
                            foreach ($branches as $branch) {  // Use $branches to loop
                                $selected = "";
                                // If a branch has been selected by the user and stored in the session,
                                // then add the selected attribute to that branch option
                                if (isset($_SESSION['branch_location']) && $_SESSION['branch_location'] == $branch[1]) {
                                    $selected = "selected";
                                }
                                
                                //Array() does not get posted properly 
                                //implode *array to string* to post its value through the form
                                $imp_branch = implode('//' , $branch);
                                echo "<option value=\"$imp_branch\" $selected>$branch[1]</option>";
                            }
                        ?>
                    </select>

                <!-- This button submits both the home address and branch location in the form-->
                <button class="save-btn" type="submit">Save Address</button>
                <a href="map.php" class="map-btn">
                    <img src="../img/map.png" alt="Map Image" class="map-image">
                </a>
            </form>
        </div>
    <?php } ?>

    <?php
    // Fetch items from the database
    try {
        $sql = "SELECT item_id, item_name, price, sales_price, percent_off, made_in, department_code, image_url FROM Item";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            echo '<div class="product-grid">';
    
            while ($row = $result->fetch_assoc()) {
                if (isset($row["sales_price"])) {
                    $display_price = "<div style='display: flex; flew-flow: row; align-items: center; gap: 8px;'>" .
                                        "<p class='curr-price'>$" . number_format($row["sales_price"], 2) . "</p>" . 
                                        "<p class='percent-off'>" . intval(htmlspecialchars($row["percent_off"])*100) . "% off!</p>" .
                                        "<p class='og-price'>$" . number_format($row["price"], 2) . "</p>" .
                                     "</div>";
                } else {
                    $display_price = '<p class="curr-price">$' . number_format($row["price"], 2) . '</p>';
                }
            
                echo '<div class="card" draggable="true" ondragstart="drag(event)" id="' . htmlspecialchars($row["item_id"]) . '">' .
                        '<img src="../img/' . htmlspecialchars($row["image_url"]) . '" alt="Product Image" ' . 'draggable="true" ondragstart="drag(event)" id="' . htmlspecialchars($row["item_id"]) . '">' .
                        '<h3>' . htmlspecialchars($row["item_name"]) . '</h3>' . 
                        $display_price . '<p class="details">Made in: ' . htmlspecialchars($row["made_in"]) . '</p>' .
                        '<p class="details">Dept code: ' . htmlspecialchars($row["department_code"]) . '</p>' .
                     '</div>';
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

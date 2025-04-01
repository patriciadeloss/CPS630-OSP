<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Web Service Platform</title>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"> </script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/validate.js"></script>
    <link rel="stylesheet" href="../css/base-style.css">
</head>
<body ng-app="myApp">

    <?php
        session_start();
        include("spa_header.php"); 
        include("../external-php-scripts/database.php");
        include("../external-php-scripts/security.php"); 
        include("../external-php-scripts/updateCart.php");
    ?>
    

    <!-- Render view -->
    <div ng-view></div>





    <!-- HOME -->
    <script type="text/ng-template" id="home">
            
        <head>
            <link rel="stylesheet" href="../css/index.css">
            <style>
                .map-image {    
                    margin: auto;
                    width: 45px;
                    height: auto;
                }
            </style>
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
                        
                    </form>
                        <a href="#!map" class="map-btn">
                            <img src="../img/map.png" alt="Map Image" class="map-image">
                        </a>
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

        <iframe src="scripts/address.php" frameborder="0" name="address-frame" style="width: 100%; height: 50px;"></iframe>

        </body>

    </script>










    <!-- ABOUT US -->
    <script type="text/ng-template" id="aboutus">
        <head>
            <link rel="stylesheet" href="../css/about.css">
            <style>
                .profile-card-container {
                    margin: auto;
                    margin-top: 16vh;
                }
            </style>
        </head>

        <body>
            <section class="profile-card-container">
                <article class="profile-card">
                    <section class="profile-card-header">
                        <img src="../img/pfp.svg" alt="" style="width:auto; height:50%;">
                    </section>
                    <section class="profile-card-body">
                        <h2>Patricia Delos Santos</h2>
                        <hr>
                        <p>"Blah blah blah"<br>pdelos@torontomu.ca</p>
                    </section>
                </article>
                
                <article class="profile-card">
                    <section class="profile-card-header">
                        <img src="../img/pfp.svg" alt="" style="width:auto; height:50%;">
                    </section>
                    <section class="profile-card-body">
                        <h2>Genevive Sanchez</h2>
                        <hr>
                        <p>"Blah blah blah"<br>g1sanchez@torontomu.ca</p>
                    </section>
                </article>

                <article class="profile-card">
                    <section class="profile-card-header">
                        <img src="../img/pfp.svg" alt="" style="width:auto; height:50%;">
                    </section>
                    <section class="profile-card-body">
                        <h2>Suboohi Sayeed</h2>
                        <hr>
                        <p>"Blah blah blah"<br>suboohi.sayeed@torontomu.ca</p>
                    </section>
                </article>
            </section>
        </body>
        
    </script>











    <!-- REVIEWS -->
    <script type="text/ng-template" id="reviews">
        <?php 
            $error_message = '';
            $item_to_filter = 'None';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Form data will only be processed if the Reviews form was submitted
                if (isset($_POST['item-to-review']) && !empty($_POST['item-to-review']) && 
                    isset($_POST['rating']) && !empty($_POST['rating']) &&
                    isset($_POST['item_review']) && !empty($_POST['item_review'])) {
            
                    // Check if user is signed in
                    if (!isset($_SESSION['user_id'])) {
                        $error_message = 'You are currently not signed in. <a href="#!signin">Sign in</a> to add a review.';
                    }
                    // Check if a signed in user submitted a non-empty form
                    else {
                        $userID = $_SESSION['user_id'];
                        $itemID = $_POST['item-to-review'];
                        $rating = $_POST['rating'];
                        $itemReview = $_POST['item_review'];
                
                        try {
                            $sql = "INSERT INTO Reviews (user_id, item_id, rating, review_text) VALUES ($userID, $itemID, $rating, '$itemReview')";
                            $conn->query($sql);
                        } catch (Exception $e) {
                            $error_message = "Error: " . $e->getMessage();
                        }  
                    }
                }
            }
        ?>
    
        <head>
            <link rel="stylesheet" href="../css/reviews.css">
        </head>

        <body>
            <section>
                <article>
                    <!-- REVIEWS FORM -->
                    <form action="" method="POST" class="user-form">
                        <legend>Write a review</legend>

                        <div class="form-container">
                            <label for="item">For:</label>
                            <select name="item-to-review" id="item_id">
                                <?php
                                // Fetch items from the database
                                try {
                                    $sql = "SELECT item_id, item_name FROM Item";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["item_id"] . "'>" . htmlspecialchars($row["item_name"]) . "</option>";
                                        }
                                    } else {
                                        echo '<p>No products found.</p>';
                                    }
                                } catch (Exception $e) {
                                    echo "Error: " . $e->getMessage();
                                } 
                                ?>
                            </select>
                        </div>
                        <div class="form-container">
                            <label for="rating">Rating:</label>
                            <input type="number" id="rating" name="rating" min="1" max="5" step=".1" required>
                        </div>
                        <div class="form-container">
                            <textarea name="item_review" id="item_review" rows="5" maxlength="60" required></textarea>
                        </div>

                        <button type="submit" class="enable">Submit</button>

                        <!-- Display error message if user is not signed in -->
                        <?php if (!empty($error_message)): ?>
                            <p style="font-size: 12px; color: red; text-align: center;"><?php echo $error_message; ?></p>
                        <?php endif; ?>
                    </form>

                    <br>

                    <!-- FILTERS FORM -->
                    <form action="" method="POST" class="user-form" id="filter-by-product">
                        <?php 
                            if (isset($_POST['item-to-filter']) && !empty($_POST['item-to-filter'])) {
                                $item_to_filter = $_POST['item-to-filter']; 
                            }
                            echo "<legend>Filter by: " . $item_to_filter . "</legend>";
                        ?>

                        <input type="radio" name="item-to-filter" value="None">
                        <label>None</label><br>

                        <?php
                        // Fetch items from the database
                        try { 
                            $sql = "SELECT item_id, item_name FROM Item";
                            $result = $conn->query($sql);
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<input type='radio' name='item-to-filter' value='" . htmlspecialchars($row["item_name"]) . "''>";
                                    echo "<label> " . htmlspecialchars($row["item_name"]) . "</label><br>";
                                }
                            } else {
                                echo '<p>No products found.</p>';
                            }
                        } catch (Exception $e) {
                            echo "Error: " . $e->getMessage();
                        } 
                        ?>

                        <input type="submit" style="display: none;">
                    </form>
                </article>

                
                <article>
                    <!-- Reviews display -->
                    <?php
                    try {
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item-to-filter']) && !empty($_POST['item-to-filter'])) {
                            $item_to_filter = $_POST['item-to-filter'];
                        }
                        
                        // Fetch reviews from the database
                        // Depending on the filter (radio button clicked),
                        // define & execute a query to fetch item entries
                        if ($item_to_filter == "None") {
                            $sql = "SELECT user_id, item_id, rating, review_text, time_stamp FROM Reviews";
                            $review_result = $conn->query($sql);
                        }
                        else {
                            $sql = "SELECT item_id FROM Item WHERE item_name = '" . $_POST['item-to-filter'] . "'";
                            $item_result = $conn->query($sql);
                            $item_row = $item_result->fetch_assoc();

                            $sql = "SELECT user_id, item_id, rating, review_text, time_stamp FROM Reviews WHERE item_id=" . $item_row['item_id'];
                            $review_result = $conn->query($sql);
                        }
                    
                        if ($review_result->num_rows > 0) {
                    
                            // For each item entry fetches/retrieved,
                            while ($review_row = $review_result->fetch_assoc()) {
                                // Fetch username of user who made the review from the database
                                $sql = "SELECT login_id FROM Users WHERE user_id=" . $review_row['user_id'];
                                $user_result = $conn->query($sql);
                                $user_row = $user_result->fetch_assoc();

                                // Fetch name of item that was reviewed from the database
                                $sql = "SELECT item_name FROM Item WHERE item_id=" . $review_row['item_id'];
                                $item_result = $conn->query($sql);
                                $item_row = $item_result->fetch_assoc();

                                echo "<div class='review-card'>" .
                                        "<div class='review-card-top'>" .
                                            "<h3>" . $user_row['login_id'] . "</h3>" . 
                                            "<p>" . $review_row['time_stamp'] . "</p>" .
                                        "</div>" . 

                                        "<div class='review-card-bottom'>" .
                                            "<p>For: " . $item_row['item_name'] . " | " . "Rating: " . $review_row['rating'] . "</p>" .
                                            "<br>" .
                                            "<p>" . $review_row['review_text'] . "</p>" . 
                                        "</div>" .
                                    "</div>";
                            }
                        } else {
                            echo "<p style='grid-column: 1/-1; text-align: center;'>No reviews found.</p>";
                        }
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }   
                    ?>
                </article>
            </section>

            <script>
                $("#filter-by-product input").on("click", function(event) {
                    $("#filter-by-product").submit();   
                });
            </script>
        </body>
    </script>













    <!-- TYPES OF SERVICES -->
    <script type="text/ng-template" id="services">
        <head>
            <style>
                .services-page .container {
                    width: 90%;
                    padding: 20px;
                    text-align: center;
                    margin: 0 auto;
                } 
                /* Updated Styling of Services */
                .services-page .services-container {
                    display: grid;
                    grid-template-columns: auto auto auto;
                    column-gap: 20px;
                    padding: 20px;
                    text-align: center;
                    margin: 0 auto;
                } 

                .services-page .title {
                    font-size: 2.5rem;
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 20px;
                }

                .services-page p {
                    font-size: 1rem;
                    color: #777;
                }

                .services-page .service {
                    background-color: white;
                    margin: 20px 0;
                    padding: 20px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    border-radius: 8px;
                }

                .services-page .service h2 {
                    font-size: 1.5rem;
                    color: #7FA06D;
                    margin-bottom: 10px;
                    width: 100%;
                    max-width: 100%;
                }
            </style>
        </head>
        <body>
            <div class="services-page">
                <div class="container">
                    <h1 class="title">Our Services</h1>
                    <p>Don't want to leave the house? No problem! We offer a variety of services to meet your needs. Now you can get your grocery shopping needs in one place, all from the convenience of a web browser. From browsing and shopping, to payments and delivery, we'll handle it all. </p>
                    <div class="services-container">
                        <div class="service">
                            <h2>Order Management</h2>
                            <p>Manage and track your orders efficiently with features like placing, updating, and confirming your orders. Stay informed at every stage of your order's journey.</p>
                        </div>

                        <div class="service">
                            <h2>Inventory Management</h2>
                            <p>Efficiently track your products and manage stock levels. Our platform ensures that you always have accurate and real-time information about your inventory.</p>
                        </div>

                        <div class="service">
                            <h2>Delivery to Your Destination</h2>
                            <p>We offer convenient delivery services from your selected branch to your preferred destination, ensuring that your items reach you on time and in perfect condition.</p>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </script>

    









    




    <!-- SIGN UP -->
    <script type="text/ng-template" id="signup">

        <head>
            <link rel="stylesheet" href="../css/forms.css">
            <style>
                .container-fix {
                    margin: auto;
                    width: 450px;
                }
            </style>
        </head>

        <body>
            <div class="container-fix">
                <form action="scripts/signup.php" method="POST" id="user-form" target="signup-message">
                    <legend>Sign Up</legend>
                    
                    <div class="form-container">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                        <p class="warning" id="username_msg"></p>
                    </div>
                    <div class="form-container">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                        <p class="warning" id="username_msg"></p>
                    </div>
                    <div class="form-container">
                        <label for="">Email</label>
                        <input type="text" id="email" name="email" required>
                        <p class="warning" id="email_msg"></p>
                    </div>
                    <div class="form-container">
                        <label for="">Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number">
                        <p class="warning" id="phone_msg"></p>
                    </div>
                    <div class="form-container">
                        <label for="">Password</label>
                        <input type="password" id="password" name="password" required>
                        <p class="warning" id="password_msg"></p>
                    </div>
                    <div class="form-container">
                        <label for="">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <p class="warning" id="confirmpass_msg"></p>
                    </div>
                    <div class="form-container">
                        <label for="account_type">Account Type</label>
                        <select name="account_type" id="account_type">
                            <option value="1">User</option>
                            <option value="0">Administrator</option>
                        </select>
                    </div>
                    <div class="form-container">
                        <iframe src="scripts/signup.php" frameborder="0" name="signup-message" style="height: 20px; width: 100%;" scrolling="no"></iframe>
                    </div>
                    <div class="form-container"> 
                        <p style="display: inline;">Already have an account?</p>
                        <a href="#!signin">Sign in here</a>
                    </div>

                    <button type="submit" class="enable">Sign Up</button>
                </form>
                
            </div>
            


            <script>
                document.getElementById("name").addEventListener("input", chk_user);
                document.getElementById("name").addEventListener("blur", chk_user);
                document.getElementById("email").addEventListener("blur", chk_email);
                document.getElementById("phone_number").addEventListener("blur", chk_phone);
                document.getElementById("password").addEventListener("input", chk_pass);
                document.getElementById("confirm_password").addEventListener("input", match_pass);
            </script>
        </body>
    </script>
















    <!-- SIGN IN -->
    <script type="text/ng-template" id="signin">

        <head>
            <link rel="stylesheet" href="../css/forms.css">
            <style>
                .container-fix {
                    margin: auto;
                    margin-top: 16vh;
                    width: 380px;
                }
            </style> 
        </head>

        <body>
            <div class="container-fix">
                <form action="scripts/signin.php" method="POST" id="user-form" target="_parent">
                    <legend>Sign In</legend>

                    <div class="form-container">
                        <label for="">Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-container">
                        <label for="">Password</label>
                        <input type="password" name="password" required>
                    </div> 

                    <div class="form-container">
                        <iframe src="scripts/signup.php" frameborder="0" name="signup-message" style="margin:0; height: 20px; width: 100%;" scrolling="no"></iframe>
                    </div>
                    
                    <div class="form-container"> 
                        <p style="display: inline;">Don't have an account?</p>
                        <a href="#!signup">Sign up here</a>
                    </div>

                    <button type="submit" class="enable">Sign In</button>
                    
                </form>
            </div>
        </body>
    </script>















    <!-- CART -->
    <script type="text/ng-template" id="cart">
        <head>
            <link rel="stylesheet" href="../css/cart-style.css">
        </head>
        <div class="container">
            <a href="#!home">Back</a>
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
                    <?php $grandTotal = 0.00; $grandQty = 0; ?>
                    
                    <!-- If user is logged in, display cart -->
                    <?php if (isset($_SESSION['account_type'])) { ?>
                        <!-- display user's shopping cart items -->
                        <?php
                            // retrieve a user's entries from the Shopping Cart Table
                            $sql = "SELECT * FROM ShoppingCart WHERE user_id = $userID";
                            $result = $conn->query($sql);

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
                                                <form action='cart.php' method='POST'>
                                                    <input type='text' name='updateItemID' id='updateItemID' value='" . $cartRow['item_id'] . "' style='display:none;'>
                                                    <button type='submit' name='updateQty' value='decrease'>-</button>
                                                    <span id='amount'>" . htmlspecialchars($cartRow['quantity']) . "</span>
                                                    <button type='submit' name='updateQty' value='increase'>+</button>
                                                </form>
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
                            <p style="text-align: center;"> You are currently not signed in. <a href="#!signin">Sign in</a> to view your Shopping Cart </p> 
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
                <a href="../pages/payments.php"><button>Check Out</button></a>
            </div>
        </footer>
    </script>












    
    <!-- SEARCH -->
    <script type="text/ng-template" id="search">
        <?php
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
                echo "<tr><td colspan='5'>You must be logged in to view orders. Please <a href='#!signin'>sign in</a>.</td></tr>";
            }

            echo "</table>";
            echo "</div>";

            $conn->close();
        ?>

    </script>








    <!-- MAP -->
    <script type="text/ng-template" id="map">
        <?php 
            print_r($_SESSION);
            if (isset($_SESSION['home_address']) && isset($_SESSION['branch_location'])) {
                $home_address = $_SESSION['home_address'];
                $branch_location = $_SESSION['branch_location'];
            }
        ?>
        <head>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                }
                #map {
                    height: 500px;
                    width: 100%;
                }
            </style>
        </head>
        <body>

        <div id="map"></div>

        <script>
            function initMap() {
                var map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 12,
                    center: { lat: 43.7, lng: -79.4 } // Default center (Toronto area)
                });
                

                var directionsService = new google.maps.DirectionsService();
                var directionsRenderer = new google.maps.DirectionsRenderer({map: map});

                // Use address names instead of coordinates
                var request = {
                    origin: '<?php echo $branch_location; ?>', // branch address from session
                    destination: '<?php echo $home_address; ?>', // home address from session
                    travelMode: google.maps.TravelMode.DRIVING
                };

                directionsService.route(request, function (result, status) {
                    if (status === 'OK') {
                        directionsRenderer.setDirections(result);
                    } else {
                        alert('Directions request failed due to ' + status);
                    }
                });
            }
        </script>

        <!-- Load Google Maps API -->
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrIBP9HXZrhsDUA7bCPqn9S-33AwSiR5U&callback=initMap"></script>

        </body>
    </script>












    <!-- PAYMENTS -->
    <script type="text/ng-template" id="payments">

    </script>



    <!-- CONFIRMATION -->
    <script type="text/ng-template" id="confirmation">

    </script>


    <!-- DB ADMIN -->
    <script type="text/ng-template" id="db-admin">

    </script>














    <script>    
        var app = angular.module('myApp', ['ngRoute']);
        app.config(function($routeProvider) {
            //routeProvider: used to provide routes to services
            $routeProvider
            //change controller and template based on route
            .when('/home', {
            //templateUrl to link to ID of template
            templateUrl : 'home'})

            .when('/aboutus', {
            templateUrl : 'aboutus'})

            .when('/reviews', {
            templateUrl : 'reviews'})

            .when('/services', {
            templateUrl : 'services'})

            .when('/signup', {
            templateUrl : 'signup'})

            .when('/signin', {
            templateUrl : 'signin'})

            .when('/logout', {
            templateUrl : 'logout'})

            .when('/search', {
            templateUrl : 'search'})

            .when('/cart', {
            templateUrl : 'cart'})

            .when('/map', {
            templateUrl : 'map'})

            .when('/payments', {
            templateUrl : 'payments'})

            .when('/confirmation', {
            templateUrl : 'confirmation'})

            .otherwise({redirectTo: '/home'});
        });
        
        /*
        app.controller('HomeController', function($scope) {
            $scope.message = '<h1>Hello World!</h1>';
            //$scope.message = 'Hello from HomeController!';
            console.log($scope.message);
        });
        */

    </script>

</body>
</html>
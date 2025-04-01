<?php 
    session_start();
    include("../external-php-scripts/database.php");
    $error_message = '';
    $item_to_filter = 'None';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Form data will only be processed if the Reviews form was submitted
        if (isset($_POST['item-to-review']) && !empty($_POST['item-to-review']) && 
            isset($_POST['rating']) && !empty($_POST['rating']) &&
            isset($_POST['item_review']) && !empty($_POST['item_review'])) {
    
            // Check if user is signed in
            if (!isset($_SESSION['user_id'])) {
                $error_message = 'You are currently not signed in. <a href="signin.php">Sign in</a> to add a review.';
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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online Web Service Platform</title>
        <link rel="stylesheet" href="../css/base-style.css">
        <link rel="stylesheet" href="../css/reviews.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>

    <body>
        <?php include("header.php"); ?>

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
</html>
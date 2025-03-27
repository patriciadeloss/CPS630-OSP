<?php 
    session_start();
    include("../external-php-scripts/database.php");
    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if user is signed in
        if (!isset($_SESSION['user_id'])) {
            $error_message = 'You are currently not signed in. <a href="signin.php">Sign in</a> to add a review.';
        }
        // Check if a signed in user submitted a non-empty form
        else if (isset($_POST['item_id']) && !empty($_POST['item_id']) && 
                 isset($_POST['rating']) && !empty($_POST['rating']) &&
                 isset($_POST['item_review']) && !empty($_POST['item_review'])) {
    
            $userID = $_SESSION['user_id'];
            $itemID = $_POST['item_id'];
            $rating = $_POST['rating'];
            $itemReview = $_POST['item_review'];
    
            try {
                $sql = "INSERT INTO Reviews (user_id, item_id, rating, review_text) VALUES ($userID, $itemID, $rating, '$itemReview')";
                $conn->query($sql);
            } catch (Exception $e) {
                $error_message = "Error: " . $e->getMessage();
            }  
        }
        else {
            $error_message = 'Invalid form.';
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
    </head>

    <body>
        <?php include("header.php"); ?>

        <section>
            <article>
                <!-- Reviews form -->
                <form action="" method="POST" id="user-form">
                    <legend>Write a review</legend>

                    <div class="form-container">
                        <label for="item">For:</label>
                        <select name="item_id" id="item_id">
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
                        <input type="number" id="rating" name="rating" min="1" max="5" step=".1">
                    </div>
                    <div class="form-container">
                        <textarea name="item_review" id="item_review" rows="5" maxlength="60"></textarea>
                    </div>

                    <button type="submit" class="enable">Submit</button>

                    <!-- Display error message if user is not signed in -->
                    <?php if (!empty($error_message)): ?>
                        <p style="font-size: 12px; color: red; text-align: center;"><?php echo $error_message; ?></p>
                    <?php endif; ?>
                </form>

                <br>

                <!-- Filters form -->
                <form action="" method="POST" id="user-form">
                    <legend>Filter by Product</legend>

                    <?php
                    // Fetch items from the database
                    try {
                        $sql = "SELECT item_name FROM Item";
                        $result = $conn->query($sql);
                    
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='form-container'>" .
                                        "<input type='checkbox' id='rating' name='rating'>" .
                                        "<label for='quantity'>" . htmlspecialchars($row["item_name"]) . "</label>" .
                                    "</div>";
                            }
                        } else {
                            echo "<p>No products found.</p>";
                        }
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    } 
                    ?>
                </form>
            </article>


            <article>
                <?php
                try {
                    // Fetch reviews from the database
                    $sql = "SELECT user_id, item_id, rating, review_text, time_stamp FROM Reviews";
                    $review_result = $conn->query($sql);
                
                    if ($review_result->num_rows > 0) {
                
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
    </body>
</html>
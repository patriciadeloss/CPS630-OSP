<?php include("header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base-style.css">
    <link rel="stylesheet" href="../css/index.css">
    
    <script>
        function allowDrop(ev) {
            ev.preventDefault(); // Enables dropping
        }

        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.id); // Stores the dragged item's ID
        }

        function drop(ev) {
            ev.preventDefault();
            var data = ev.dataTransfer.getData("text"); // Retrieves the dragged item's ID
            var draggedElement = document.getElementById(data); // Gets the dragged element
            alert("Added to cart: " + draggedElement.querySelector("h3").innerText);
        }

    </script>
</head>
<body>
    <!-- User Home Address & Branch Selection -->
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

    <?php
        include("database.php"); // Establishes a connection to the database

        // Fetch items from the database
        try {
            $sql = "SELECT item_id, item_name, price, made_in, department_code, image_url FROM Item";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                echo '<div class="product-grid">';
        
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card" draggable="true" ondragstart="drag(event)" id="' . htmlspecialchars($row["item_id"]) . '">'
                            . '<img src="' . htmlspecialchars($row["image_url"]) . '" alt="Product Image">'
                            . '<h3>' . htmlspecialchars($row["item_name"]) . '</h3>'
                            . '<p class="price">$' . number_format($row["price"], 2) . '</p>'
                            . '<p class="details">Made in: ' . htmlspecialchars($row["made_in"]) . '</p>'
                            . '<p class="details">Dept code: ' . htmlspecialchars($row["department_code"]) . '</p>'
                        . '</div>';
                }
        
                echo '</div>';
            } else {
                echo "<p>No products found.</p>";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }        
    ?>

</body>
</html>

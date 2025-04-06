<?php include("../external-php-scripts/database.php"); ?>

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
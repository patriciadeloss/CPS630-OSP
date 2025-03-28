<?php 
    session_start();
    include("header.php"); 
    include("../external-php-scripts/database.php"); 

    $apply_sale_message = '';
    $remove_sale_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['apply_sale']) && isset($_POST['percent_off'])) { applySale(); }
        else if (isset($_POST['remove_sale'])) { removeSale(); }
    }

    /* Function defns */

    function applySale() {
        $itemID = $_POST['item_id'];
        $percentOff = $_POST['percent_off'];

        // Handle invalid sales
        if (str_contains(strval($percentOff),'.') || $percentOff <= 0 || $percentOff > 100) {
            $GLOBALS['apply_sale_message'] = '<p style="text-align: center; color: red;">Invalid sale.</p>';
        }
        else {
            if ($itemID == "*") {
                $sql = "UPDATE Item SET percent_off = " . $percentOff/100;
                $GLOBALS['conn']->query($sql);

                $sql = "UPDATE Item SET sales_price = price * (1 -" . $percentOff ."/ 100)";
                $GLOBALS['conn']->query($sql);
            }
            else {
                $sql = "UPDATE Item SET percent_off = " . $percentOff/100 . " WHERE item_id = " . $itemID;
                $GLOBALS['conn']->query($sql);

                $sql = "UPDATE Item SET sales_price = price * (1 -" . $percentOff ."/ 100) WHERE item_id = " . $itemID;
                $GLOBALS['conn']->query($sql);
            }
            $GLOBALS['apply_sale_message'] = '<p style="text-align: center; color: green;">Sale applied!</p>';
        }
    }

    function removeSale() {
        $itemID = $_POST['item_id'];

        if ($itemID == "*") {
            $sql = "UPDATE Item SET percent_off = 0";
            $GLOBALS['conn']->query($sql);

            $sql = "UPDATE Item SET sales_price = NULL";
            $GLOBALS['conn']->query($sql);
        }
        else {
            $sql = "UPDATE Item SET percent_off = " . 0 . " WHERE item_id = " . $itemID;
            $GLOBALS['conn']->query($sql);

            $sql = "UPDATE Item SET sales_price = NULL WHERE item_id = " . $itemID;
            $GLOBALS['conn']->query($sql);
        }
        $GLOBALS['remove_sale_message'] = '<p style="text-align: center; color: green;">Sale removed!</p>';
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Product Sales</title>
        <link rel="stylesheet" type="text/css" href="../css/base-style.css">
        <link rel="stylesheet" type="text/css" href="../css/db_maintain.css">
        <link rel="stylesheet" type="text/css" href="../css/db_sales.css">
    </head>

    <body id="db-page">
        <section>
            <article>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Sale</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $sql = "SELECT item_name, percent_off FROM Item";
                            $result = $conn->query($sql);
                        
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>" .
                                            "<td>" . htmlspecialchars($row["item_name"]) . "</td>" .
                                            "<td>" . intval(htmlspecialchars($row["percent_off"])*100) . "%</td>" .
                                         "</tr>";
                                }
                            } else {
                                echo "<p>No products found.</p>";
                            }
                        } catch (Exception $e) {
                            echo "Error: " . $e->getMessage();
                        } 
                        ?>  
                    </tbody>
                </table>
            </article>

            <article>
                <h2>Apply Product Sales</h2>
                <form method="POST" id="db-form">
                    <!-- Dropdown Menu for Products-->
                    <label for="table">Select Product:</label>
                    <select name="item_id" id="item_id">
                        <option value="*">All</option>

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
                                
                    <br><br>

                    <label for="field">Percent Off:</label>
                    <input type="text" name="percent_off" id="percent_off" required placeholder="0 < integer <= 100"><br><br>

                    <button type="submit" name="apply_sale">Apply</button>
                </form>

                <?php echo $apply_sale_message; ?>

                <h2>Remove Product Sales</h2>
                <form method="POST" id="db-form">
                    <!-- Dropdown Menu for Products-->
                    <label for="table">Select Product:</label>
                    <select name="item_id" id="item_id">
                        <option value="*">All</option>

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

                    <button type="submit" name="remove_sale">Remove</button>
                </form>

                <?php echo $remove_sale_message; ?>
            </article>
        </section>
    
        <?php $conn->close(); ?>
    </body>
</html>
<?php
session_start();
include("../external-php-scripts/database.php");
//echo '<link rel="stylesheet" type="text/css" href="../css/base-style.css">';
include("header.php");

$tables = ["Item", "Users", "Truck", "Trips", "Shopping", "Orders", "ShoppingCart", "Reviews"];

// Initialize variables
$sql = "";
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['table']) && !empty($_POST['fields'])) {
        $table = $_POST['table'];
        $fields = $_POST['fields'];
        // If conditions are not provided, then set it to empty string
        $conditions = isset($_POST['conditions']) ? $_POST['conditions'] : '';

        // If condition is provided
        if (!empty($conditions)) {
            // Split the condition into field and value
            list($field, $value) = explode('=', $conditions);

            // Check if the value is numeric or a string
            if (is_numeric($value)) {
                // If it's numeric, then no quotes needed
                $value = $value;
            } else {
                // But if it's a string, quote it
                $value = "'" . $value . "'";
            }

            $conditions = $field . "=" . $value;
        }

        // SELECT Statement
        $sql = "SELECT $fields FROM $table";
        if (!empty($conditions)) {
            $sql = $sql . " WHERE $conditions";
        }

        // Execute the query
        $result = $conn->query($sql);
    } else {
        echo '<p style="text-align: center; color: red;">Please select a table and specify fields.</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Data</title>
    <link rel="stylesheet" type="text/css" href="../css/base-style.css">
    <link rel="stylesheet" type="text/css" href="../css/db_maintain.css">
</head>
<body id="db-page">
    <h2>Select Data from Database</h2>
    <form method="post" id="db-form">
        <!-- Dropdown Menu for Database Tables-->
        <label for="table">Select Table:</label>
        <select name="table" id="table" required>
            <option value="">-- Select Table --</option>
            <?php
            foreach ($tables as $table) {
                echo "<option value='$table'>$table</option>";
            }
            ?>
        </select><br><br>

        <!-- Fields Input -->
        <label for="fields">Fields:</label>
        <input type="text" name="fields" id="fields" required placeholder="e.g., user_id, name, email, balance"><br><br>

        <!-- Conditions Input -->
        <label for="conditions">Conditions (optional):</label>
        <input type="text" name="conditions" id="conditions" placeholder="e.g., user_id=5, name=Mary Smith, balance>10"><br><br>

        <button type="submit">Select Data</button>
    </form>

    <?php
    // Display results if available
    if ($result && $result->num_rows > 0) {
        echo "<h3 class='order-title'>Results:</h3>";
        echo "<table class='order-table'>";
        echo "<tr>";

        // Determine column names
        // Transform the fields input into an array separated by ','
        $fields_array = explode(',', $_POST['fields']);
        // Put each field provided as the table header
        foreach ($fields_array as $field) {
            echo "<th>" . trim($field) . "</th>";
        }
        echo "</tr>";

        // Determine rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($fields_array as $field) {
                echo "<td>" . htmlspecialchars($row[trim($field)]) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo '<p style="text-align: center; color: red;">No records found.</p>';
    }

    $conn->close();
    ?>
</body>
</html>
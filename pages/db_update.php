<?php
session_start();
include("../external-php-scripts/database.php");
echo '<link rel="stylesheet" type="text/css" href="../css/base-style.css">';
include("header.php");

$tables = ["Item", "Users", "Truck", "Trips", "Shopping", "Orders", "ShoppingCart"];

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // All fields must have input values
    if (!empty($_POST['table']) && !empty($_POST['field']) && !empty($_POST['new_value']) && !empty($_POST['conditions'])) {
        $table = $_POST['table'];
        $field = $_POST['field'];
        $new_value = $_POST['new_value'];
        $conditions = $_POST['conditions'];

        // Add quotes to the new value if it's a string
        if (!is_numeric($new_value)) {
            $new_value = "'" . $new_value . "'";
        }

        // Divide the conditions input into two parts: condition_field and condition_value
        list($condition_field, $condition_value) = explode('=', $conditions, 2);
        // Removes white spaces in the input
        $condition_field = trim($condition_field);
        $condition_value = trim($condition_value);

        // Add quotes to the condition value if it's a string
        if (!is_numeric($condition_value)) {
            $condition_value = "'" . $condition_value . "'";
        }

        // SQL query
        $sql = "UPDATE $table SET $field = $new_value WHERE $condition_field = $condition_value";

        // Execute query and print out message
        if ($conn->query($sql) === TRUE) {
            $message = '<p style="text-align: center; color: green;">Record updated successfully.</p>';
        } else {
            $message = '<p style="text-align: center; color: red;">Error updating record: ' . $conn->error . '</p>';
        }
    } else {
        $message = '<p style="text-align: center; color: red;">Please fill out all fields.</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Data</title>
    <link rel="stylesheet" type="text/css" href="../css/db_maintain.css">
</head>
<body id="db-page">
    <h2>Update Data in Database</h2>
    <?php
    echo $message; // Display messages
    ?>
    <form method="post" id="db-form">
        <label for="table">Select Table:</label>
        <select name="table" id="table" required>
            <option value="">-- Select Table --</option>
            <?php
            foreach ($tables as $table) {
                echo "<option value='$table'>$table</option>";
            }
            ?>
        </select><br><br>

        <!-- Field to Update -->
        <label for="field">Field to Update:</label>
        <input type="text" name="field" id="field" required placeholder="e.g., name"><br><br>

        <!-- New Value -->
        <label for="new_value">New Value:</label>
        <input type="text" name="new_value" id="new_value" required placeholder="e.g., Jane Doe"><br><br>

        <!-- Conditions Input -->
        <label for="conditions">Conditions:</label>
        <input type="text" name="conditions" id="conditions" required placeholder="e.g., user_id=1"><br><br>

        <button type="submit">Update</button>
    </form>

    <?php $conn->close();?>
</body>
</html>

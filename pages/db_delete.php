<?php
session_start();
include("../external-php-scripts/database.php");
echo '<link rel="stylesheet" type="text/css" href="../css/base-style.css">';
include("header.php");

$tables = ["Item", "Users", "Truck", "Trips", "Shopping", "Orders", "ShoppingCart", "Reviews"];

// Initialize variables
$sql = ""; 
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['table']) && !empty($_POST['field']) && !empty($_POST['conditions'])) {
        $table = $_POST['table'];
        $field = $_POST['field'];
        $conditions = $_POST['conditions'];

        // Determine if conditions are numeric or string and properly quote the condition
        if (is_numeric($conditions)) {
            $value = $conditions;
        } else {
            $value = "'" . $conditions . "'"; // Quote strings
        }

        if (!empty($field) && !empty($conditions)) {
            $sql = "DELETE FROM $table WHERE $field=$value";
        }

        // Display messages
        try {
            $conn->query($sql);

            if (!empty($sql) && $conn->query($sql) === TRUE) {
                $message = '<p style="text-align: center; color: green;">Record deleted successfully.</p>';
            } elseif (!empty($sql)) {
                $message = '<p style="text-align: center; color: red;">Error deleting record: ' . $conn->error . '</p>';
            }
        } catch (Exception $e) {
            $message = "<p style='text-align: center; color: red;'>Invalid query: " . $e->getMessage() . "</p>";
        }

    } else {
        $message = '<p style="text-align: center; color: red;">Please select a table, specify a field, and set a condition.</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Data</title>
    <link rel="stylesheet" type="text/css" href="../css/db_maintain.css">
</head>
<body id="db-page">
    <h2>Delete Data from Database</h2>
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

        <!-- Field Input -->
        <label for="field">Field:</label>
        <input type="text" name="field" id="field" required placeholder="e.g., truck_code, price"><br><br>

        <!-- Conditions Input -->
        <label for="conditions">Conditions:</label>
        <input type="text" name="conditions" id="conditions" required placeholder="e.g., 'TRK005', 10"><br><br>

        <button type="submit">Delete</button>
    </form>

    <?php
    echo $message; // Display messages
    $conn->close();
    ?>
</body>
</html>

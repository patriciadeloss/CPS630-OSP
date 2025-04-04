<?php
session_start();
include("../external-php-scripts/database.php");
echo '<link rel="stylesheet" type="text/css" href="../css/base-style.css">';
include("header.php");

$tables = ["Item", "Users", "Truck", "Trips", "Shopping", "Orders", "ShoppingCart", "Reviews"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table = $_POST['table'];
    $fields = $_POST['fields'];
    $values = $_POST['values'];

    // Split fields and values into arrays
    $fields_array = explode(',', $fields);  // Split fields by commas
    $values_array = explode(',', $values);    // Split values by commas
    
    // Remove whitespaces from each value and reassign variables
    $fields_array = array_map('trim', $fields_array);
    $values_array = array_map('trim', $values_array);

    // Add quotes to values if they are strings
    $values_quoted = [];
    foreach ($values_array as $value) {
        $value = trim($value); // Remove whitespaces
        
        // Check if the value is numeric or a string
        if (is_numeric($value)) {
            $values_quoted[] = $value;
        } else {
            $values_quoted[] = "'" . $value . "'"; // Add quotes to strings
        }
    }

    $fields_str = implode(',', $fields_array);        // Join fields into a string
    $values_quoted_str = implode(',', $values_quoted);  // Join values into a string

    try {
        // SQL query to Insert
        $sql = "INSERT INTO $table ($fields_str) VALUES ($values_quoted_str)";

        // Execute the query and display messages accordingly
        if ($conn->query($sql) === TRUE) {
            echo '<p style="text-align: center; color: green;">Record inserted successfully.</p>';
        } 
    } catch (Exception $e) {
        echo '<p style="text-align: center; color: red;">' . $e->getMessage() . '</p>';
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Data</title>
    <link rel="stylesheet" type="text/css" href="../css/db_maintain.css">
</head>
<body id="db-page">
    <h2>Insert Data into Database</h2>
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
        <input type="text" name="fields" id="fields" required placeholder="e.g., field1, field2, field3"><br><br>

        <!-- Values Input -->
        <label for="values">Values:</label>
        <input type="text" name="values" id="values" required placeholder="e.g., value1, value2, value3"><br><br>

        <button type="submit">Insert</button>
    </form>
</body>
</html>

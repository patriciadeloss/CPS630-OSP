<?php
session_start();
include("../external-php-scripts/database.php");
//echo '<link rel="stylesheet" type="text/css" href="../css/base-style.css">';
include("header.php");

$tables = ["Item", "Users", "Truck", "Trips", "Shopping", "Orders", "ShoppingCart", "Reviews"];

// Initialize variables
$sql = "";
$result = null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['table']) && !empty($_POST['fields'])) {
        $table = $_POST['table'];
        $fields = $_POST['fields'];

        // If condition isn't provided, set it to the empty string
        $conditions = isset($_POST['conditions']) ? $_POST['conditions'] : '';

        // If condition is provided
        if (!empty($conditions)) {
            // Determine the operator and make a split according to this operator 
            if (preg_match('/(=|>=|>|<=|<)/',$conditions, $matches)) {

                $operator = $matches[0]; // Get the matched operator
                list($field,$value) = explode($operator,$conditions);

                // Check if the value is numeric or a string
                // If it's numeric, no quotes are needed
                if (is_numeric(trim($value))) { $value = trim($value); }
                // Otherwise quotes are needed
                else { $value = "'" . trim($value) . "'"; }

                // Construct the condition
                switch ($operator) {
                    case '=':
                        $conditions = $field . "=" . $value;
                        break;
                    case '>=':
                        $conditions = $field . ">=" . $value;
                        break;
                    case '>':
                        $conditions = $field . ">" . $value;
                        break;
                    case '<=':
                        $conditions = $field . "<=" . $value;
                        break;
                    case '<':
                        $conditions = $field . "<" . $value;
                        break;
                    default:
                        break;
                }
            }
        }

        // SELECT Statement
        $sql = "SELECT $fields FROM $table";
        if (!empty($conditions)) {
            $sql = $sql . " WHERE $conditions";
        }

        // Execute the query
        try {
            $result = $conn->query($sql);
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
        $message = '<p style="text-align: center; color: red;">No records found.</p>';
    }

    echo $message;
    $conn->close();
    ?>
</body>
</html>
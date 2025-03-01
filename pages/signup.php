<?php
include("../external-php-scripts/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel_no = $_POST['phone_number'];
    $login_id = $_POST['username'];
    $password = $_POST['password'];
    $user_role = isset($_POST['account_type']) ? (int) $_POST['account_type'] : 1;  // 0 = Admin, 1 = User, Default = 1

    // Check if the username already exists
    $sql_check = "SELECT * FROM Users WHERE login_id = '$login_id'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Display message
        echo '<p style="color: red; text-align: center;">You already have an account. Please <a href="signin.php">sign in</a>.</p>';
    } else {
        // Inserts the new user
        $sql = "INSERT INTO Users (name, email, tel_no, login_id, password, account_type) 
                VALUES ('$name', '$email', '$tel_no', '$login_id', '$password', $user_role)";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: signin.php"); // redirects to sign up page
            exit();
        } else {
            echo "Error: " . $conn->error;
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
        <link rel="stylesheet" href="../css/forms.css">
        <script src="../js/validate.js"></script>
    </head>

    <body>
        <?php include("header.php"); ?>
        <form action="" method="POST" id="user-form">
            <legend>Sign Up</legend>
            
            <div class="form-container">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <p class="warning" id="username_msg"></p>
            </div>
            <div class="form-container">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
                <p class="warning" id="username_msg"></p>
            </div>
            <div class="form-container">
                <label for="">Email</label>
                <input type="text" id="email" name="email" required>
                <p class="warning" id="email_msg"></p>
            </div>
            <div class="form-container">
                <label for="">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number">
                <p class="warning" id="phone_msg"></p>
            </div>
            <div class="form-container">
                <label for="">Password</label>
                <input type="password" id="password" name="password" required>
                <p class="warning" id="password_msg"></p>
            </div>
            <div class="form-container">
                <label for="">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <p class="warning" id="confirmpass_msg"></p>
            </div>
            <div class="form-container">
                <label for="account_type">Account Type</label>
                <select name="account_type" id="account_type">
                    <option value="1">User</option>
                    <option value="0">Administrator</option>
                </select>
            </div>
            <div class="form-container"> 
                <p style="display: inline;">Already have an account?</p>
                <a href="signin.php">Sign in here</a>
            </div>

            <div id="submit-btn">
                <button type="submit" class="disable" disabled>Sign Up</button>
            </div>
        </form>


        <script>
            document.getElementById("name").addEventListener("input", chk_user);
            document.getElementById("name").addEventListener("blur", chk_user);
            document.getElementById("email").addEventListener("blur", chk_email);
            document.getElementById("phone_number").addEventListener("blur", chk_phone);
            document.getElementById("password").addEventListener("input", chk_pass);
            document.getElementById("confirm_password").addEventListener("input", match_pass);
        </script>
    </body>
</html>

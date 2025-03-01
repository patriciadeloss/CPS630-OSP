<?php
session_start();
include("../external-php-scripts/database.php");

// If the user is already logged in, redirect them to the appropriate page
if (isset($_SESSION['user_id'])) {
    $user_role = $_SESSION['account_type'];
    if ($user_role == 0) {  // Admin
        header("Location: admin.php");
        exit();
    } else if ($user_role == 1) {  // User
        header("Location: index.php");
        exit();
    }
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_id = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $sql = "SELECT * FROM Users WHERE login_id = '$login_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if ($password === $user['password'] && $confirm_password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['account_type'] = $user['account_type'];

            if ($user['account_type'] == 0) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error_message = 'Invalid login credentials.';
        }
    } else {
        $error_message = 'User not found.';
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
    </head>

    <body>
        <?php include("header.php"); ?>
        <form action="" method="POST" id="user-form">
            <legend>Sign In</legend>

            <div class="form-container">
                <label for="">Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-container">
                <label for="">Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-container">
                <label for="">Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <div class="form-container">
                <label for="">Account Type</label>
                <select name="account_type" id="">
                    <option value="1">User</option>
                    <option value="0">Administrator</option>
                </select>
            </div>
            <div class="form-container"> 
                <p style="display: inline;">Don't have an account?</p>
                <a href="signup.php">Sign up here</a>
            </div>

            <button type="submit" class="enable">Sign In</button>
            <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
        </form>
    </body>
</html>

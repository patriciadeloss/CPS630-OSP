<?php
    session_start();
    include("../external-php-scripts/database.php");
    include("../external-php-scripts/security.php");

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

    // Handle form submission accordingly
    if (isset($_POST['username']) && !empty($_POST['username']) && 
        isset($_POST['password']) && !empty($_POST['password'])) {

        $login_id = $_POST['username'];
        $password = $_POST['password'];

        $validateUser = validateUser($login_id,$password);

        if (!$validateUser) { $error_message = 'User not found.'; }
        else if($validateUser === -1) { $error_message = 'Invalid login credentials.'; }
        else {
            $sql = "SELECT user_id, account_type, name FROM Users WHERE login_id = ?"; 
            $result = $GLOBALS['conn']->prepare($sql);
            $result->bind_param("s", $login_id);
            $result->execute();
            $result->store_result();
            $result->bind_result($user_id, $account_type, $name);
            $result->fetch();

            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            $_SESSION['account_type'] = $account_type;
            session_write_close();
            ob_end_flush();

            if ($account_type == 0) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }
    }
?>

<form action="signin.php" method="POST" id="user-form">
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
        <p style="display: inline;">Don't have an account?</p>
        <a href="signup.php">Sign up here</a>
    </div>

    <button type="submit" class="enable">Sign In</button>
    <p style="font-size: 12px; color: red; text-align: center;"><?php echo $error_message; ?></p>
</form>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Online Web Service Platform</title>
        <link rel="stylesheet" href="../css/base-style.css">
        <link rel="stylesheet" href="../css/signin.css">
    </head>

    <body>
        <?php include("header.php"); ?>
        <form action="" method="POST" id="sign-in">
            <legend>Sign In</legend>
            <div class="form-container">
                <label for="">Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-container">
                <label for="">Email</label>
                <input type="text" name="email" required>
            </div>
            <div class="form-container">
                <label for="">Phone Number</label>
                <input type="text" name="phone_number">
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
                    <option value="2">Administrator</option>
                </select>
            </div>
            <button type="button">Sign In</button>
        </form>
    </body>
</html>
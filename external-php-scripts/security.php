<?php
include("database.php");

// Generate a cryptographically secure random salt
function generateRandomSalt($length = 16) {
    return bin2hex(random_bytes($length));
}

function validateUser($entered_login_id, $entered_pw) {
    $sql = "SELECT password, salt FROM Users WHERE login_id = ?"; 
    $result = $GLOBALS['conn']->prepare($sql);
    $result->bind_param("s", $entered_login_id);
    $result->execute();
    $result->store_result();

    // Usernames are defined to be unique. Therefore, only one entry should be found
    if ($result->num_rows == 1) {
        $result->bind_result($login_id,$salt);
        $result->fetch();

        // Validate the pw by comparing the MD5 hash of (the pw + salt) w the stored hash 
        if (md5($entered_pw . $salt) == $login_id) { return true; } // Correct pw
        else { return -1; } // User exists, but the pw entered is incorrect
    } 
    else { return false; } // User doesn't exist
}

function insertUser($name, $email, $tel_no, $entered_login_id, $entered_pw, $user_role) {
    $salt = generateRandomSalt();  // Generate a secure random salt

    try {
        $hashed_pw = md5($entered_pw . $salt);

        // Insert username, hashed password & salt into the database
        $sql = "INSERT INTO Users (account_type, name, tel_no, email, login_id, password, salt) VALUES (?,?,?,?,?,?,?)";
        $result = $GLOBALS['conn']->prepare($sql);
        $result->bind_param("issssss", $user_role, $name, $tel_no, $email, $entered_login_id, $hashed_pw, $salt);
        $result->execute();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
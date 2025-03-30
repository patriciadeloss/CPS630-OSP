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

    function insertOrder($date_issued, $date_received, $grand_total, $user_id, $receipt_id, $payment_info) {
        try {
            if ($payment_info === "Cash Payment") {
                // Insert without salt
                $order_sql = "INSERT INTO Orders (date_issued, date_received, total_price, user_id, receipt_id, payment_info, payment_salt) VALUES (?, ?, ?, ?, ?, ?, NULL)";
        
                $order_result = $GLOBALS['conn']->prepare($order_sql);
                $order_result->bind_param("ssdiis", $date_issued, $date_received, $grand_total, $user_id, $receipt_id, $payment_info);
            } else {
                // Insert with salt
                $card_salt = generateRandomSalt();
                $hashed_payment_info = md5($payment_info . $card_salt);
        
                $order_sql = "INSERT INTO Orders (date_issued, date_received, total_price, user_id, receipt_id, payment_info, payment_salt) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
                $order_result = $GLOBALS['conn']->prepare($order_sql);
                $order_result->bind_param("ssdiiss", $date_issued, $date_received, $grand_total, $user_id, $receipt_id, $hashed_payment_info, $card_salt);
            }
    
            $order_result->execute();
            $order_id = $GLOBALS['conn']->insert_id; //Get the inserted order_id

            //Select an available truck for delivery
            $truck_sql = "SELECT truck_id FROM Truck WHERE avail_code = 'Available' LIMIT 1";
            $truck_result = $GLOBALS['conn']->query($truck_sql);
            $truck_row = $truck_result->fetch_assoc();
            $truck_id = $truck_row['truck_id'];

            // Get the addresses from the session
            if (isset($_SESSION['home_address']) && isset($_SESSION['branch_location'])) {
                $home_address = $_SESSION['home_address'];
                $branch_location = $_SESSION['branch_location'];
            }
        
            // Create an entry for the Trips table
            $trip_sql = "INSERT INTO Trips (source_code, dest_code, truck_id, price) VALUES (?, ?, ?, ?)";
            $trip_result = $GLOBALS['conn']->prepare($trip_sql);
            $trip_result->bind_param("ssid", $home_address, $branch_location, $truck_id, $grand_total);
            $trip_result->execute();
            $trip_id = $GLOBALS['conn']->insert_id;  //Get the inserted trip_id

            // Update the Orders table with the trip_id
            $update_sql = "UPDATE Orders SET trip_id = ? WHERE order_id = ?";
            $update_result = $GLOBALS['conn']->prepare($update_sql);
            $update_result->bind_param("ii", $trip_id, $order_id);
            $update_result->execute();

            return $order_id;  // Return the order_id
    
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    
?>

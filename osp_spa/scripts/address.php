<?php 
    // Check if the form is submitted
    if (isset($_POST['home_address']) && isset($_POST['branch_location'])) {
        $home_address = $_POST['home_address']; // retrieve from input form
        $branch_arr = $_POST['branch_location']; // retrieve branch info
        $branch_info = explode("//", $branch_arr); //explode string to revert back to array
        $branch_code = $branch_info[0]; //retrieve store code
        $branch_location = $branch_info[1]; // retrieve branch address
        $_SESSION['home_address'] = $home_address; // Store in session
        $_SESSION['branch_location'] = $branch_location; // Store branch location in session
        $_SESSION['branch_code'] = $branch_code; // Store branch location in session

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // Update the database with the new address
            $sql = "UPDATE Users SET address = '$home_address' WHERE user_id = $user_id";
            $result = $conn->query($sql);
        }
    }
?>
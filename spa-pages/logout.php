<?php
    session_start();
    session_unset(); // unset session variables
    session_destroy(); // delete session

    // Output a message for debugging (optional)
    echo "Session destroyed successfully!";
?>



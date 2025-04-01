<?php
session_start();
session_unset(); // unset session variables
session_destroy(); // delete session
header("Location: ../index.php#!/signin"); // after logging out, redirects to sign in page
exit();
?>

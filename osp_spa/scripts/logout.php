<?php
session_start();
session_unset(); // unset session variables
session_destroy(); // delete session
echo '<script>window.location.href = "../index.php#!/signin";</script>'; // after logging out, redirects to sign in page
exit();
?>

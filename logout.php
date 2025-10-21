<?php
session_start();

// Destroy session data
session_unset();
session_destroy();

// Remove the "mbox_id" cookie if it exists
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/'); // Set the cookie expiration date to the past to delete it
}
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/'); // Set the cookie expiration date to the past to delete it
}


// Redirect the user to the login page after logging out
header("Location:common/home.php");
exit();
?>

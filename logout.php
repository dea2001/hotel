<?php
// Start the session if it hasn't been started already
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login or home page
header("location: login.php");
exit;
?>

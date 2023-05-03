<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["is_staff"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch data or handle actions for managing bookings, users, and services here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management - Fictitious Hotel</title>
    <link rel="stylesheet" href="../css/styles.css">

</head>
<body>
    <header>
        <h1>Management</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="user_bookings.php">My Bookings</a></li>
                <?php if (isset($_SESSION["is_staff"]) && $_SESSION["is_staff"] === true): ?>
                    <li><a href="management.php">Management</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <li><a href="../logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="../login.php">Login</a></li>
                    <li><a href="../register.php">Register</a></li>
                <?php endif; ?>
            </ul>
    </nav>
    </header>

    <main>
        <!-- Add tables, forms, or other elements for managing bookings, users, and services -->
    </main>

    <footer>
    <div class="container">
        <p>&copy; 2023 Fictitious Hotel. All rights reserved.</p>
        <nav>
            <ul>
                <li><a href="privacy_policy.php">Privacy Policy</a></li>
                <li><a href="terms_of_service.php">Terms of Service</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </nav>
    </div>
    </footer>
</body>
</html>

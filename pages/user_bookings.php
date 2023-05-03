<?php
session_start();
require_once "../includes/db_connect.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // User is not logged in, display login form
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Fictitious Hotel</title>
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    <body>
        <header>
            <h1>Your Bookings</h1>
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
            <p>Please log in to access your bookings.</p>
            <div class="form-container">
                <form action="../login.php" method="post">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
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

    <?php
    exit; // Exit the script
}

// User is logged in, proceed with displaying the bookings
$user_id = $_SESSION["id"];
$sql = "SELECT bookings.id, room_name, start_date, end_date FROM bookings JOIN rooms ON bookings.room_id = rooms.id WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No bookings found for the user
    $message = "You have no bookings.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Bookings - Fictitious Hotel</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Your Bookings</h1>
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
        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php else: ?>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo $row["room_name"]; ?></td>
                            <td><?php echo $row["start_date"]; ?></td>
                            <td><?php echo $row["end_date"]; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
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



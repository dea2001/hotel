<?php
session_start();
require_once "../includes/db_connect.php";

$user_id = $_SESSION["id"] ?? null;
if (!$user_id) {
    // User is not logged in, redirect or display an error message
    header("location: login.php");
    exit;
}

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
  <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["id"])): ?>
    <p>Please log in to access your bookings.</p>
    <div class="form-container">
        <form action="" method="post" class="login-form">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-input" required>
            
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-input" required>
            
            <button type="submit" class="form-button">Login</button>
        </form>
    </div>
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

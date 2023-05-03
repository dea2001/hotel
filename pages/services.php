<?php
session_start();
require_once "../includes/db_connect.php";

// Fetch the list of rooms from the database
$query = "SELECT * FROM rooms";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services - Fictitious Hotel</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <header>
    <h1>Services</h1>
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
    <h2>Rooms</h2>
    <div class="room-container">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="room">
          <img src="placeholder.jpg" alt="Room Image">
          <h3><?php echo $row["room_name"]; ?></h3>
          <p>Capacity: <?php echo $row["capacity"]; ?> guests</p>
          <p>Price: $<?php echo $row["price"]; ?> per night</p>
        </div>
      <?php endwhile; ?>
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

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
$sql = "SELECT bookings.id, rooms.room_name, bookings.start_date, bookings.end_date, bookings.total_price FROM bookings JOIN rooms ON bookings.room_id = rooms.id WHERE bookings.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
  $bookings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Bookings - Fictitious Hotel</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
function confirmCancelBooking(bookingId) {
  if (confirm("Are you sure you want to cancel this booking?")) {
    cancelBooking(bookingId);
  }
}

function cancelBooking(bookingId) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "cancel_booking.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // Booking cancellation successful
        alert("Booking canceled successfully.");
        // Remove the booking row from the table
        var row = document.getElementById("booking-row-" + bookingId);
        if (row) {
          row.parentNode.removeChild(row);
        }
      } else {
        // Booking cancellation failed
        alert("An error occurred while canceling the booking. Please try again.");
      }
    }
  };
  xhr.send("booking_id=" + encodeURIComponent(bookingId));
}

    </script>
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
    <?php if (count($bookings) === 0): ?>
        <p class="no-bookings">You have no bookings.</p>
    <?php else: ?>
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Room Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                  <tr id="booking-row-<?php echo $booking["id"]; ?>">
                    <td><?php echo $booking["id"]; ?></td>
                    <td><?php echo $booking["room_name"]; ?></td>
                    <td><?php echo $booking["start_date"]; ?></td>
                    <td><?php echo $booking["end_date"]; ?></td>
                    <td><?php echo $booking["total_price"]; ?></td>
                    <td>
                      <button onclick="confirmCancelBooking(<?php echo $booking['id']; ?>)">Cancel</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
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

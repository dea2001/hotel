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
          <button class="book-now-btn">Book Now</button>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <!-- Popup form for booking -->
  <div id="booking-form-popup" style="display: none;">
    <form id="booking-form">
      <h2>Book Now</h2>
      <p>Room: <span id="room-name"></span></p>
      <p>Price per night: <span id="price-per-night"></span></p>
      <label for="start-date">Start Date:</label>
      <input type="date" name="start-date" id="start-date" required>
      <label for="end-date">End Date:</label>
      <input type="date" name="end-date" id="end-date" required>
      <label for="num-people">Number of People:</label>
      <input type="number" name="num-people" id="num-people" min="1" required>
      <p>Total Price: $<span id="total-price"></span></p>
      <button type="submit">Book</button>
    </form>
  </div>

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


  <script>
// Function to handle the "Book Now" button click event
function handleBookNow() {
    var room = this.parentNode;
    var roomName = room.querySelector("h3").textContent;
    var pricePerNight = parseFloat(room.querySelector("p:nth-of-type(2)").textContent.split("$")[1]);

    document.getElementById("room-name").textContent = roomName;
    document.getElementById("price-per-night").textContent = pricePerNight.toFixed(2);
    document.getElementById("total-price").textContent = "0.00";
    document.getElementById("booking-form-popup").style.display = "block";

    // Scroll to the bottom of the page
    window.scrollTo(0, document.body.scrollHeight);
}

// Calculate the total price based on the selected date range and number of people
function calculatePrice() {
    var startDate = new Date(document.getElementById("start-date").value);
    var endDate = new Date(document.getElementById("end-date").value);
    var pricePerNight = parseFloat(document.getElementById("price-per-night").textContent);

    // Calculate the number of nights staying
    var numNights = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));

    // Perform the price calculation
    var totalPrice = pricePerNight * numNights;

    document.getElementById("total-price").textContent = totalPrice.toFixed(2);
}

// Handle the form submission
function handleFormSubmit(event) {
    event.preventDefault();

    var startDate = document.getElementById("start-date").value;
    var endDate = document.getElementById("end-date").value;
    var numPeople = parseInt(document.getElementById("num-people").value);
    var roomName = document.getElementById("room-name").textContent;
    var totalPrice = parseFloat(document.getElementById("total-price").textContent);

    // Create an object to send to the server
    var bookingData = {
        startDate: startDate,
        endDate: endDate,
        numPeople: numPeople,
        roomName: roomName,
        totalPrice: totalPrice
    };

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save_booking.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Booking saved!");
            document.getElementById("booking-form-popup").style.display = "none";
            document.getElementById("booking-form").reset();
        } else {
            alert("An error occurred while saving the booking. Please try again.");
        }
    };
    xhr.send(JSON.stringify(bookingData));
}

var bookNowBtns = document.querySelectorAll(".book-now-btn");
bookNowBtns.forEach(function (btn) {
    btn.addEventListener("click", handleBookNow);
});

document.getElementById("start-date").addEventListener("change", calculatePrice);
document.getElementById("end-date").addEventListener("change", calculatePrice);
document.getElementById("num-people").addEventListener("input", calculatePrice);

document.getElementById("booking-form").addEventListener("submit", handleFormSubmit);



</script>
</body>
</html>

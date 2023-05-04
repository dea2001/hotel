<?php
session_start();
require_once "../includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the booking data from the request
  $startDate = $_POST["start-date"];
  $endDate = $_POST["end-date"];
  $roomName = $_POST["room-name"];
  $totalPrice = $_POST["total-price"];

  // Get the user ID from the session
  $user_id = $_SESSION["id"];

  // Get the room ID based on the room name
  $stmt = $conn->prepare("SELECT id FROM rooms WHERE room_name = ?");
  $stmt->bind_param("s", $roomName);
  $stmt->execute();
  $result = $stmt->get_result();
  $room = $result->fetch_assoc();
  $room_id = $room["id"];

  // Save the booking in the database
  $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("iisss", $user_id, $room_id, $startDate, $endDate, $totalPrice);
  $stmt->execute();
  $stmt->close();

  // Return a success response
  http_response_code(200);
  echo "Booking saved!";
} else {
  // Return an error response
  http_response_code(400);
  echo "Invalid request.";
}
?>

<?php
session_start();
require_once "../includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the booking data from the form submission
    $roomName = $_POST['room_name'];
    $date = $_POST['date'];
    $numPeople = $_POST['num_people'];
    $totalPrice = $_POST['total_price'];

    // Validate and sanitize the data (perform necessary checks)

    // Prepare the SQL statement to insert the booking into the database
    $stmt = $conn->prepare("INSERT INTO bookings (room_name, start_date, num_people, total_price, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidi", $roomName, $date, $numPeople, $totalPrice, $_SESSION['id']);
    
    // Execute the SQL statement
    if ($stmt->execute()) {
        // Booking saved successfully
        header("location: user_bookings.php");
        exit;
    } else {
        // Error occurred while saving the booking
        echo "Error: " . $stmt->error;
    }
} else {
    // Invalid request method
    http_response_code(405);
}
?>

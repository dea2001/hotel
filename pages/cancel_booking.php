<?php

session_start();
require_once "../includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        // User is not logged in
        http_response_code(401);
        echo "You are not authorized to cancel this booking.";
        exit();
    }

    $bookingId = $_POST["booking_id"];
    $userId = $_SESSION["id"];

    // Check if the booking belongs to the logged-in user
    $stmt = $conn->prepare("SELECT id FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $bookingId, $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Booking does not exist or does not belong to the user
        http_response_code(404);
        echo "Booking not found or does not belong to you.";
        exit();
    }

    // Delete the booking from the database
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows === 0) {
        // Failed to delete the booking
        http_response_code(500);
        echo "Failed to cancel the booking. Please try again.";
        exit();
    }

    // Booking cancellation successful
    http_response_code(200);
    echo "Booking canceled successfully.";
    exit();
} else {
    // Invalid request method
    http_response_code(400);
    echo "Invalid request.";
    exit();
}

?>

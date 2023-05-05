<?php

session_start();
require_once "../includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        // User is not logged in
        http_response_code(401);
        echo "You are not authorized to submit a review.";
        exit();
    }

    $userId = $_SESSION["id"];
    $bookingId = $_POST["booking_id"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];

    // Check if the booking belongs to the logged-in user
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $bookingId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Booking does not exist or does not belong to the user
        http_response_code(404);
        echo "Booking not found or does not belong to you.";
        exit();
    }

    // Insert the review into the database
    $stmt = $conn->prepare("INSERT INTO reviews (booking_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $bookingId, $userId, $rating, $comment);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows === 0) {
        // Failed to insert the review
        http_response_code(500);
        echo "Failed to submit the review. Please try again.";
        exit();
    }

    // Redirect the user to the homepage
    header("Location: home.php");
    exit();
} else {
    // Invalid request method
    http_response_code(400);
    echo "Invalid request.";
    exit();
}

?>

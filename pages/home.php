<?php
session_start();
require_once "../includes/db_connect.php";

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // User is logged in, check if they have bookings
    $user_id = $_SESSION["id"];
    $stmt = $conn->prepare("SELECT id FROM bookings WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User has bookings, display the review form
        $displayReviewForm = true;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Form submission, process the review
            $rating = $_POST["rating"];
            $comment = $_POST["comment"];

            // Save the review in the database
            $stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $user_id, $rating, $comment);
            $stmt->execute();
        }
    }
}

// Fetch all reviews from the database
$query = "SELECT * FROM reviews";
$result = $conn->query($query);
$reviews = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fictitious Hotel</title>
  <link rel="stylesheet" href="../css/styles.css">
  <script src="../js/script.js"></script>
</head>
<body>
  <header>
    <h1>Welcome to Fictitious Hotel</h1>
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
    <h2>About Our Hotel</h2>
    <p>Provide information about your hotel here.</p>

    <h2>Services</h2>
    <p>Provide an overview of the services offered by your hotel.</p>

    <h2>Reviews</h2>
    <?php if (count($reviews) === 0): ?>
      <p>No reviews available.</p>
    <?php else: ?>
      <ul class="review-list">
        <?php foreach ($reviews as $review): ?>
          <li>
            <div class="review-info">
              <span class="rating">Rating: <?php echo $review['rating']; ?></span>
              <span class="user">User ID: <?php echo $review['user_id']; ?></span>
            </div>
            <p class="comment"><?php echo $review['comment']; ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <h2>Submit a Review</h2>
    <?php if (isset($displayReviewForm) && $displayReviewForm === true): ?>
      <form action="home.php" method="POST">
        <label for="rating">Rating:</label>
          <select name="rating" id="rating" required>
            <option value="">Select Rating</option>
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?php echo "$i/5"; ?>"><?php echo "$i/5"; ?></option>
              <?php endfor; ?>
          </select>
        <label for="comment">Comment:</label>
        <textarea name="comment" id="comment" rows="4" required></textarea>
        <button type="submit">Submit Review</button>
      </form>
    <?php else: ?>
      <p>You must have at least one booking to submit a review.</p>
    <?php endif; ?>
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

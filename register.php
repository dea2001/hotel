<?php
// Include database connection file
require_once "includes/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $first_name = trim($_POST["first_name"]);
  $last_name = trim($_POST["last_name"]);
  $email = trim($_POST["email"]);
  $password = $_POST["password"];

  // Check if the email already exists in the database
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    echo "Email already exists. Please choose a different email.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement to insert the user record
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
    $stmt->execute();

    // Redirect to the login page after successful registration
    header("location: login.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fictitious Hotel</title>
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>
    <header>
        <h1>Register</h1>
        <nav>
            <ul>
                <li><a href="pages/home.php">Home</a></li>
                <li><a href="pages/services.php">Services</a></li>
                <li><a href="pages/user_bookings.php">My Bookings</a></li>
                <?php if (isset($_SESSION["is_staff"]) && $_SESSION["is_staff"] === true): ?>
                    <li><a href="pages/management.php">Management</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
    </nav>
    </header>

    <main>
        <form action="register.php" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Register</button>
        </form>
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

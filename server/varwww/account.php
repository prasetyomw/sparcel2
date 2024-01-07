<?php
// Include the database connection file
include 'db_connection.php';

// Check if the user is logged in (you may implement user authentication)
// For simplicity, assuming user ID is stored in a session variable
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the user ID from the session
$userID = $_SESSION['user_id'];

// Retrieve user information from the database
$sql = "SELECT * FROM user_info WHERE user_id = '$userID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $phone = $row['phone'];
    $course = $row['course'];
} else {
    $name = $phone = $course = "N/A"; // Set default values if user not found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
</head>
<body>
    <h2>Account Details</h2>
    <p>Welcome, <?php echo $name; ?>!</p>

    <h3>Your Information:</h3>
    <p><strong>Name:</strong> <?php echo $name; ?></p>
    <p><strong>Phone:</strong> <?php echo $phone; ?></p>
    <p><strong>Course:</strong> <?php echo $course; ?></p>

    <!-- Add additional details as needed -->

    <p><a href="logout.php">Logout</a></p>
</body>
</html>

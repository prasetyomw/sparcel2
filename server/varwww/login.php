<?php

// Include the database connection file
require_once 'db_connection.php';

// Function to verify the password using Bcrypt
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

// Function to redirect to a specific page
function redirectTo($page) {
    header("Location: $page");
    exit();
}

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user input
    $emailOrStuID = $_POST['email_or_stu_id'];
    $password = $_POST['password'];

    // Store the intended destination in the session
    if (isset($_SESSION['intended_destination'])) {
        $intendedDestination = $_SESSION['intended_destination'];
        unset($_SESSION['intended_destination']);
    } else {
        $intendedDestination = 'dashboard.php'; // Default destination
    }

    // Query the database to check if the user exists
    $query = "SELECT * FROM user_cred WHERE email = :email OR stu_id = :stu_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $emailOrStuID, 'stu_id' => $emailOrStuID]);
    $user = $stmt->fetch();

    // Verify the password
    if ($user && verifyPassword($password, $user['password'])) {
        // Set the user_id in the session
        $_SESSION['user_id'] = $user['user_id'];

        // Redirect to the intended destination
        redirectTo($intendedDestination);
    } else {
        // Invalid credentials, show an error message
        $errorMessage = "Invalid email/Student ID or password";

        // Store the intended destination in the session for future use
        $_SESSION['intended_destination'] = $intendedDestination;

        // Debugging statement
        echo "Intended destination: $intendedDestination";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($errorMessage)) { ?>
        <p><?php echo $errorMessage; ?></p>
    <?php } ?>
    <form method="POST" action="">
        <label>Email/Student ID:</label>
        <input type="text" name="email_or_stu_id" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>

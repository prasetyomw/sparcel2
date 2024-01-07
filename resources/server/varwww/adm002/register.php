<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "P@55w0rd";
$dbname = "sparcel2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user-provided data from the form
    $username = sanitizeInput($_POST['username']);
    $userProvidedPassword = sanitizeInput($_POST['password']);

    // Hash the user-provided password
    $hashedPassword = password_hash($userProvidedPassword, PASSWORD_BCRYPT);

    // Insert the user details into the database (assuming "admin_cred" table)
    $insertQuery = "INSERT INTO admin_cred (username, password) VALUES ('$username', '$hashedPassword')";

    if ($conn->query($insertQuery) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    
    <!-- Registration Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>

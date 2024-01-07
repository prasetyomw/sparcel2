<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include 'db_connection.php';

    // Function to generate a unique 8-digit user ID
    function generateUserID() {
        return mt_rand(10000000, 99999999);
    }

    // Get user input
    $stuId = $_POST['stu_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $course = $_POST['course'];

    // Generate a unique user ID
    $userId = generateUserID();

    // Insert user information into user_cred table
    $sql_cred = "INSERT INTO user_cred (user_id, stu_id, email, password) VALUES ('$userId', '$stuId', '$email', '$password')";
    mysqli_query($conn, $sql_cred);

    // Insert user information into user_info table
    $sql_info = "INSERT INTO user_info (user_id, name, phone, course) VALUES ('$userId', '$name', '$phone', '$course')";
    mysqli_query($conn, $sql_info);

    // Redirect to account.php
    header("Location: account.php");
    exit();
}
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
    <form method="POST" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" required><br>

        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" maxlength="16" required><br>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" required><br>

        <label for="course">Course:</label>
        <input type="text" name="course" required><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>

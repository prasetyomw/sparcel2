<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <?php
    session_start();

    // Replace these values with your database credentials
    $servername = "localhost";
    $username = "root";
    $password = "P@55w0rd";
    $dbname = "sparcel2";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM admin_cred WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $hashedPassword = $row['password'];

            // Verify the password using password_verify
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['admin_id'] = $username;
                header("Location: dashboard.php");
            } else {
                echo "Invalid credentials";
            }
        } else {
            echo "Invalid credentials";
        }
    }
    ?>
    
    <h2>Admin Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>

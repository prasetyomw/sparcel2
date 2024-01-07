<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Replace these values with your database credentials
$servername = "localhost";
$username = "root";
$password = "P@55w0rd";
$dbname = "sparcel2";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from tables
$bind_query = "SELECT * FROM bind";
$locker_query = "SELECT * FROM locker";
$user_cred_query = "SELECT * FROM user_cred";
$user_info_query = "SELECT * FROM user_info";

$bind_result = mysqli_query($conn, $bind_query);
$locker_result = mysqli_query($conn, $locker_query);
$user_cred_result = mysqli_query($conn, $user_cred_query);
$user_info_result = mysqli_query($conn, $user_info_query);

$bind_data = mysqli_fetch_all($bind_result, MYSQLI_ASSOC);
$locker_data = mysqli_fetch_all($locker_result, MYSQLI_ASSOC);
$user_cred_data = mysqli_fetch_all($user_cred_result, MYSQLI_ASSOC);
$user_info_data = mysqli_fetch_all($user_info_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['admin_id']; ?>!</h2>

    <h3>Bind Table</h3>
        <table border="1">
            <tr>
                <th>tr_id</th>
                <th>user_id</th>
                <th>name</th>
                <th>phone</th>
                <!-- <th>email</th> -->
                <th>description</th>
                <th>tracknum</th>
                <th>status</th>
                <th>lockerid</th>
                <th>hostid</th>
                <th>ts_addentry</th>
                <th>ts_dropoff</th>
                <th>ts_pickup</th>
                <th>Action</th> <!-- New column for the Edit button -->
            </tr>
            <?php foreach ($bind_data as $row): ?>
                <tr>
                    <td><?php echo $row['tr_id']; ?></td>
                    <td><?php echo $row['user_id']; ?></td>

                    <!-- Fetch additional columns from related tables -->
                    <?php
                    $user_cred = $user_cred_data[array_search($row['user_id'], array_column($user_cred_data, 'user_id'))];
                    $user_info = $user_info_data[array_search($row['user_id'], array_column($user_info_data, 'user_id'))];
                    $locker = $locker_data[array_search($row['tr_id'], array_column($locker_data, 'tr_id'))];
                    ?>

                    <td><?php echo $user_info['name']; ?></td>
                    <td><?php echo $user_info['phone']; ?></td>
                    <!-- <td><?php echo $user_cred['email']; ?></td> -->
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['tracknum']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $locker['lockerid']; ?></td>
                    <td><?php echo $locker['hostid']; ?></td>
                    <td><?php echo $locker['ts_addentry']; ?></td>
                    <td><?php echo $locker['ts_dropoff']; ?></td>
                    <td><?php echo $locker['ts_pickup']; ?></td>
                    
                    <!-- Edit button -->
                    <td><a href="edit.php?tr_id=<?php echo $row['tr_id']; ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </table>


    <!-- Similar tables for locker, user_cred, and user_info -->

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>

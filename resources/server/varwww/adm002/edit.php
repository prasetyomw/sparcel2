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

// Fetch data for the selected record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission to update data
    if (isset($_POST['update'])) {
        $tr_id = mysqli_real_escape_string($conn, $_POST['tr_id']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $tracknum = mysqli_real_escape_string($conn, $_POST['tracknum']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $lockerid = mysqli_real_escape_string($conn, $_POST['lockerid']);
        $hostid = mysqli_real_escape_string($conn, $_POST['hostid']);

        // Update the bind table
        $update_query = "UPDATE bind SET description='$description', tracknum='$tracknum', status='$status' WHERE tr_id='$tr_id'";
        if (mysqli_query($conn, $update_query)) {
            // Update the locker table
            $update_locker_query = "UPDATE locker SET lockerid='$lockerid', hostid='$hostid' WHERE tr_id='$tr_id'";
            if (mysqli_query($conn, $update_locker_query)) {
                echo "Record and locker information updated successfully";

                // Redirect to the dashboard after successful update
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Error updating locker information: " . mysqli_error($conn);
            }
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }

    // Handle record deletion
    if (isset($_POST['delete'])) {
        $tr_id = mysqli_real_escape_string($conn, $_POST['tr_id']);

        // Delete associated locker information
        $delete_locker_query = "DELETE FROM locker WHERE tr_id='$tr_id'";
        if (mysqli_query($conn, $delete_locker_query)) {
            // Now, delete the record in the bind table
            $delete_query = "DELETE FROM bind WHERE tr_id='$tr_id'";
            if (mysqli_query($conn, $delete_query)) {
                echo "Record and locker information deleted successfully";

                // Redirect to the dashboard after successful deletion
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }
        } else {
            echo "Error deleting locker information: " . mysqli_error($conn);
        }
    }
}

// Fetch data for the selected record
if (isset($_GET['tr_id'])) {
    $tr_id = mysqli_real_escape_string($conn, $_GET['tr_id']);
    $select_query = "SELECT bind.*, locker.lockerid, locker.hostid 
                     FROM bind 
                     LEFT JOIN locker ON bind.tr_id = locker.tr_id 
                     WHERE bind.tr_id='$tr_id'";
    $result = mysqli_query($conn, $select_query);
    $row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Record</title>
</head>
<body>
    <h2>Edit Record</h2>

    <form method="post" action="">
        <input type="hidden" name="tr_id" value="<?php echo $row['tr_id']; ?>">
        <label for="description">Description:</label>
        <input type="text" name="description" value="<?php echo $row['description']; ?>"><br>

        <label for="tracknum">Track Number:</label>
        <input type="text" name="tracknum" value="<?php echo $row['tracknum']; ?>"><br>

        <label for="status">Status:</label>
        <input type="text" name="status" value="<?php echo $row['status']; ?>"><br>

        <label for="lockerid">Locker ID:</label>
        <input type="text" name="lockerid" value="<?php echo $row['lockerid']; ?>"><br>

        <label for="hostid">Host ID:</label>
        <input type="text" name="hostid" value="<?php echo $row['hostid']; ?>"><br>

        <input type="submit" name="update" value="Update">
    </form>

    <!-- Add a delete button with confirmation -->
    <form method="post" action="">
        <input type="hidden" name="tr_id" value="<?php echo $row['tr_id']; ?>">
        <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this record?')">
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>

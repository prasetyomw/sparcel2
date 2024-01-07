<?php
// Include the database connection file
require_once 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Retrieve "S700" transactions for the current user with the "Pick-up" timestamp
$s700Query = "SELECT b.tr_id, b.tracknum, b.description, b.status, l.ts_pickup
              FROM bind b
              JOIN locker l ON b.tr_id = l.tr_id
              WHERE b.user_id = :user_id AND b.status = 'S700'";

$s700Stmt = $pdo->prepare($s700Query);
$s700Stmt->execute(['user_id' => $_SESSION['user_id']]);
$s700Entries = $s700Stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlocked and done</title>
</head>
<body>
    <h1>Unlocked and done</h1>

    <!-- Display the S700 transaction table -->
    <table border="1">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Tracking Number</th>
                <th>Description</th>
                <th>Status</th>
                <th>Pick-up</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($s700Entries as $entry): ?>
                <tr>
                    <td><?php echo $entry['tr_id']; ?></td>
                    <td><?php echo $entry['tracknum']; ?></td>
                    <td><?php echo $entry['description']; ?></td>
                    <td><?php echo isset($entry['status']) ? ($entry['status'] === 'S700' ? 'Unlocked and done' : $entry['status']) : ''; ?></td>
                    <td><?php echo $entry['ts_pickup']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>

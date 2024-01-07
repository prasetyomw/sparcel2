<?php
// Include the database connection file
require_once 'db_connection.php';

// Start the session
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect to dashboard if transaction ID is not provided
if (!isset($_GET['tr_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Retrieve the transaction details
$tr_id = $_GET['tr_id'];
$query = "SELECT * FROM bind WHERE tr_id = :tr_id AND user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['tr_id' => $tr_id, 'user_id' => $_SESSION['user_id']]);
$transaction = $stmt->fetch();

// Redirect to dashboard if the transaction does not exist
if (!$transaction) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
</head>
<body>
    <h1>Edit Transaction</h1>
    <form action="update_entry.php" method="post">
        <!-- Include input fields for transaction details -->
        <label for="tr_id">Transaction ID:</label>
        <input type="text" name="tr_id" value="<?= htmlspecialchars($transaction['tr_id']); ?>" readonly>
        <br>

        <label for="tracknum">Tracking Number:</label>
        <input type="text" name="tracknum" value="<?= htmlspecialchars($transaction['tracknum']); ?>" readonly>
        <br>

        <label for="description">Description:</label>
        <input type="text" name="description" value="<?= htmlspecialchars($transaction['description']); ?>" required>
        <br>

        <label for="status">Status:</label>
        <input type="text" name="status" value="<?= htmlspecialchars($transaction['status']); ?>" readonly>
        <br>
        <!-- Include other input fields as needed -->

        <input type="submit" value="Update Transaction">

        <!-- Add the delete button with a confirmation message -->
        <a href="delete_entry.php?tr_id=<?= htmlspecialchars($transaction['tr_id']); ?>" onclick="return confirm('Are you sure you want to delete this transaction?')">Delete Transaction</a>
    </form>
</body>
</html>

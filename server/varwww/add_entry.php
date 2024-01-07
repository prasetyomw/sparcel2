<?php
// FILEPATH: /V:/html/sparcel2/add_entry.php

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

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user inputs
    $trackingNumber = filter_input(INPUT_POST, 'tracking_number', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // Validate inputs (you may add more specific validation as needed)
    if (empty($trackingNumber) || empty($description)) {
        $error = "Please fill in all fields.";
    } else {
        // Generate a unique transaction ID
        $transactionID = date("YmdHis") . sprintf('%04d', mt_rand(1, 9999));

        // Insert the new entry into the "bind" table with 'S500' as the default status
        $insertQuery = "INSERT INTO bind (user_id, tr_id, tracknum, description, status) 
                        VALUES (:user_id, :tr_id, :tracknum, :description, 'S500')";

        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([
            'user_id' => $_SESSION['user_id'],
            'tr_id' => $transactionID,
            'tracknum' => $trackingNumber,
            'description' => $description,
        ]);

        // Insert into the "locker" table
        $insertQueryLocker = "INSERT INTO locker (tr_id, tracknum, ts_addentry, ts_dropoff, ts_pickup) 
        VALUES (:tr_id, :tracknum, :ts_addentry, :ts_dropoff, :ts_pickup)";
        
        $insertStmtLocker = $pdo->prepare($insertQueryLocker);
        $insertStmtLocker->execute([
        'tr_id' => $transactionID,
        'tracknum' => $trackingNumber,
        'ts_addentry' => date('Y-m-d H:i:s'), // current date and time
        'ts_dropoff' => null, // or provide a specific value
        'ts_pickup' => null // or provide a specific value
        ]);


        // Redirect to the dashboard on success
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Entry</title>
</head>
<body>
    <h1>Add Entry</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="tracking_number">Tracking Number:</label>
        <input type="text" id="tracking_number" name="tracking_number" required><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required><br>

        <input type="submit" value="Submit">
    </form>

    <p>Review the information before submitting.</p>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>

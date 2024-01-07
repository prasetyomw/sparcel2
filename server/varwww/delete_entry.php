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

// Check if transaction ID is provided
if (!isset($_GET['tr_id'])) {
    // Redirect to the dashboard or display an error message
    header("Location: dashboard.php");
    exit();
}

// Retrieve the transaction ID from the URL parameter
$tr_id = $_GET['tr_id'];

// Delete related records from the locker table
$queryLocker = "DELETE FROM locker WHERE tr_id = :tr_id";
$stmtLocker = $pdo->prepare($queryLocker);
$stmtLocker->execute(['tr_id' => $tr_id]);

// Perform the deletion
$queryBind = "DELETE FROM bind WHERE tr_id = :tr_id AND user_id = :user_id";
$stmtBind = $pdo->prepare($queryBind);
$stmtBind->execute(['tr_id' => $tr_id, 'user_id' => $_SESSION['user_id']]);

// Check if the deletion was successful
if ($stmtBind->rowCount() > 0) {
    // Deletion successful
    $_SESSION['success_message'] = "Transaction deleted successfully.";
} else {
    // Deletion failed
    $_SESSION['error_message'] = "Failed to delete transaction.";
}

// Redirect to the dashboard or another appropriate page
header("Location: dashboard.php");
exit();
?>

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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $tr_id = $_POST['tr_id'];
    $tracknum = $_POST['tracknum'];
    $description = $_POST['description']; // Add this line for the description
    // Retrieve other form fields as needed

    // Update the transaction in the database
    $query = "UPDATE bind SET tracknum = :tracknum, description = :description WHERE tr_id = :tr_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['tracknum' => $tracknum, 'description' => $description, 'tr_id' => $tr_id, 'user_id' => $_SESSION['user_id']]);

    // Redirect to the dashboard or display a success message
    header("Location: dashboard.php");
    exit();
} else {
    // If the form is not submitted, redirect to the dashboard or display an error message
    header("Location: dashboard.php");
    exit();
}

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

// Retrieve user information from the database
$query = "SELECT uc.*, ui.name FROM user_cred uc
          JOIN user_info ui ON uc.user_id = ui.user_id
          WHERE uc.user_id = :user_id";

$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// Check if the user is found
if ($user) {
    // Display the user's name
    $userName = $user['name'];
} else {
    // Handle the case where the user is not found (optional)
    $userName = "User Not Found";
}

// Retrieve transactions for the current user excluding those with status 'S700'
$transactionQuery = "SELECT b.tr_id, b.tracknum, b.description, b.status, l.ts_addentry, l.ts_dropoff, l.ts_pickup
                     FROM bind b
                     JOIN locker l ON b.tr_id = l.tr_id
                     WHERE b.user_id = :user_id AND b.status != 'S700'";

$transactionStmt = $pdo->prepare($transactionQuery);
$transactionStmt->execute(['user_id' => $_SESSION['user_id']]);
$transactions = $transactionStmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to the Dashboard, <?php echo $userName; ?>!</h1>

    <!-- Add Entry Button -->
    <a href="add_entry.php"><button>Add Entry</button></a>

    <!-- Display the transaction table -->
    <table border="1">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Tracking Number</th>
                <th>Description</th>
                <th>Status</th>
                <th>Entry Added</th>
                <th>Drop-off</th>
                <th>Pick-up</th> <!-- New column for pick-up timestamp -->
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?php echo $transaction['tr_id']; ?></td>
                    <td><?php echo $transaction['tracknum']; ?></td>
                    <td><?php echo $transaction['description']; ?></td>
                    <td>
                        <?php
                        $status = $transaction['status'];
                        if ($status === 'S500') {
                            echo 'Waiting for drop-off';
                        } elseif ($status === 'S600') {
                            echo 'Received and locked';
                        } elseif ($status === 'S700') {
                            echo 'Unlocked and done';
                        } else {
                            echo 'Unknown status';
                        }
                        ?>
                    </td>
                    <td><?php echo $transaction['ts_addentry']; ?></td>
                    <td><?php echo $transaction['ts_dropoff']; ?></td>
                    <td><?php echo $transaction['ts_pickup']; ?></td>
                    <td>
                        <?php if ($status === 'S500'): ?>
                            <a href="edit_entry.php?tr_id=<?php echo $transaction['tr_id']; ?>">Edit</a>
                        <?php elseif ($status === 'S600'): ?>
                            <button onclick="unlockFunction('<?php echo $transaction['tr_id']; ?>', '<?php echo $transaction['status']; ?>')">Unlock</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add JavaScript functions for the buttons -->
    <script>
        function unlockFunction(transactionId, status) {
            // Use AJAX to send the unlock command and update the 'ts_pickup' column
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        // Handle the response from the server if needed
                        alert('Unlock command sent successfully for transaction ' + transactionId + ' with status ' + status);
                        // Reload the page after successful unlock
                        location.reload();
                    } else {
                        // Handle the error or provide feedback to the user
                        alert('Error sending unlock command: ' + this.responseText);
                    }
                }
            };

            // Replace 'unlock.php' with the actual path to your unlock script
            xhttp.open("GET", "unlock.php?transactionId=" + encodeURIComponent(transactionId) + "&status=" + encodeURIComponent(status), true);
            xhttp.send();
        }

        function editFunction(transactionId) {
            // Add logic for editing the transaction with the given ID
            // You may want to redirect the user to an edit page or handle it using AJAX
            alert('Editing transaction ' + transactionId);
        }
    </script>

    <p><a href="done.php">Unlocked and done</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

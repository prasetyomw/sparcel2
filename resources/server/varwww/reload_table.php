<?php
// Include the database connection file
require_once 'db_connection.php';

// Retrieve transactions for the current user
$transactionQuery = "SELECT tr_id, tracknum, description, status 
                     FROM bind 
                     WHERE user_id = :user_id";

$transactionStmt = $pdo->prepare($transactionQuery);
$transactionStmt->execute(['user_id' => $_SESSION['user_id']]);
$transactions = $transactionStmt->fetchAll();
?>

<!-- Display the updated transaction table body content -->
<table border="1">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Tracking Number</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th> <!-- New column for buttons -->
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
                    <td>
                        <?php if ($status === 'S500'): ?>
                            <button onclick="unlockFunction('<?php echo $transaction['tr_id']; ?>', '<?php echo $transaction['status']; ?>')">Unlock</button>
                            <a href="edit_entry.php?tr_id=<?php echo $transaction['tr_id']; ?>">Edit</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

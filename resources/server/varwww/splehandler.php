<?php
// Include the database connection file
include 'db_connection.php';

// Check if the necessary parameters are provided in the query string
if (isset($_GET['mode']) && isset($_GET['hostid']) && isset($_GET['lockerid']) && isset($_GET['tracknum'])) {
    
    // Extract values from the query string
    $mode = $_GET['mode'];
    $hostid = $_GET['hostid'];
    $lockerid = $_GET['lockerid'];
    $tracknum = $_GET['tracknum'];

    // Ensure that mode is 501
    if ($mode == 501) {
        // Get the current timestamp
        $currentTimestamp = date('Y-m-d H:i:s');

        // Start a transaction for atomicity
        mysqli_begin_transaction($conn);

        try {
            // Update the "locker" table
            $updateLockerQuery = "UPDATE locker SET hostid = '$hostid', lockerid = '$lockerid', ts_dropoff = '$currentTimestamp' WHERE tracknum = $tracknum";
            mysqli_query($conn, $updateLockerQuery);

            // Update the "bind" table
            $updateBindQuery = "UPDATE bind SET tracknum = $tracknum, status = 'S600' WHERE tracknum = $tracknum";
            mysqli_query($conn, $updateBindQuery);

            // Commit the transaction
            mysqli_commit($conn);

            echo "Data updated successfully!";
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid mode.";
    }
} else {
    echo "Missing parameters in the query string.";
}

// Close the database connection
mysqli_close($conn);
?>

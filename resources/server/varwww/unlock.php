<?php
// unlock.php

// Include the database connection file
require_once 'db_connection.php';

// Check if transaction ID is provided
if (isset($_GET['transactionId'])) {
    $transactionId = $_GET['transactionId'];

    // Fetch lockerid and tracknum from the database based on transactionId
    $fetchLockerInfoQuery = "SELECT lockerid, tracknum FROM locker WHERE tr_id = :transactionId";
    $fetchLockerInfoStmt = $pdo->prepare($fetchLockerInfoQuery);
    $fetchLockerInfoStmt->execute(['transactionId' => $transactionId]);
    $lockerInfoRow = $fetchLockerInfoStmt->fetch();

    if ($lockerInfoRow) {
        $lockerid = $lockerInfoRow['lockerid'];
        $tracknum = $lockerInfoRow['tracknum'];

        // Continue with the rest of the script using the dynamically fetched $lockerid and $tracknum

        // Example: Update the status to 'S700' in the database
        $updateStatusQuery = "UPDATE bind SET status = 'S700' WHERE tr_id = :transactionId";
        $updateStatusStmt = $pdo->prepare($updateStatusQuery);
        $updateStatusStmt->execute(['transactionId' => $transactionId]);

        // Send Command to External URL:
        $handlerUrl = 'http://192.168.56.20:5000/handler';
        $cmd = '601'; // Command for unlock

        $handlerParams = [
            'cmd'      => $cmd,
            'lockerid' => $lockerid,
            'tracknum' => $tracknum,
        ];

        $handlerUrl .= '?' . http_build_query($handlerParams);

        $ch = curl_init($handlerUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $handlerResponse = curl_exec($ch);
        curl_close($ch);

        // Optional: Return a response
        echo "Unlock command processed for transaction $transactionId";

        // Optional: Log the response from the external handler
        echo "Response from external handler: " . $handlerResponse;
    } else {
        // Handle the case where locker information is not found in the database
        echo "Locker information not found for transaction $transactionId";
    }
} else {
    // Handle the case where transaction ID is not provided
    echo "Transaction ID not provided";
}
?>

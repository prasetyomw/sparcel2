<?php
// Include the unlock script
require_once 'your_unlock_script.php';

// Set the transaction ID
$_GET['transactionId'] = '12345';

// Call the unlock script
include 'unlock.php';
?><?php
// Include the unlock script
require_once 'your_unlock_script.php';

// Test Case 1: Provide a valid transaction ID
$_GET['transactionId'] = '12345';
include 'unlock.php';

// Test Case 2: Provide an invalid transaction ID
$_GET['transactionId'] = '98765';
include 'unlock.php';

// Test Case 3: Do not provide a transaction ID
unset($_GET['transactionId']);
include 'unlock.php';
?>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the text parameter from the URL
    $text = $_GET['text'] ?? '';

    // Validate the text (you should perform more validation in a real application)
    if (!empty($text)) {
        // Create a log message
        $logMessage = date('Y-m-d H:i:s') . " - $text\n";

        // Log the message to a text file
        $logFilePath = 'log.txt';
        file_put_contents($logFilePath, $logMessage, FILE_APPEND);

        echo 'Log entry added!';
    } else {
        echo 'Invalid or missing text parameter.';
    }
}

?>

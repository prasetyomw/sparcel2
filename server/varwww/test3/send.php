<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the command parameter from the URL
    $command = $_GET['command'] ?? '';

    // Validate the command (you should perform more validation in a real application)
    if (!empty($command)) {
        // Create the URL to forward the query to
        $forwardUrl = 'http://192.168.56.105:5000/execute_command?command=' . urlencode($command);

        // Perform the HTTP request
        $response = file_get_contents($forwardUrl);

        if ($response !== false) {
            echo 'Command forwarded successfully. Response: ' . $response;
        } else {
            echo 'Failed to forward the command.';
        }
    } else {
        echo 'Invalid or missing command parameter.';
    }
}

?>

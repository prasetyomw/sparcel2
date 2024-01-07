<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Admin Page</h2>

<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "P@55w0rd";
$dbname = "sparcel2host";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from board_info and lockerstatus tables
$sql = "SELECT board_info.*, lockerstatus.tracknum, lockerstatus.status
        FROM board_info
        LEFT JOIN lockerstatus ON board_info.lockerid = lockerstatus.lockerid";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Locker ID</th>
                <th>Location</th>
                <th>VID</th>
                <th>PID</th>
                <th>SN</th>
                <th>Track Number</th>
                <th>Status</th>
            </tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['lockerid']}</td>
                <td>{$row['location']}</td>
                <td>{$row['VID']}</td>
                <td>{$row['PID']}</td>
                <td>{$row['SN']}</td>
                <td>{$row['tracknum']}</td>
                <td>{$row['status']}</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "0 results";
}

// Close the connection
$conn->close();
?>

</body>
</html>

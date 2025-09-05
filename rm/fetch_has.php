<?php
// Include the database connection file
include 'db_connection.php';

// Query to fetch all data from the HAS table
$sql = "SELECT * FROM HAS";
$result = $conn->query($sql);

$data = []; // Array to hold table rows

if ($result && $result->num_rows > 0) {
    // Fetch each row and add it to the data array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);

// Close the database connection
$conn->close();
?>

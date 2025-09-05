<?php
include 'db_connection.php';

header('Content-Type: application/json');

// Query to fetch ingredients from the database
$sql = "SELECT Ing_id, recipe_name, Ing_name, Ing_amt, ing_type FROM ingredients";
$result = $conn->query($sql);

// Check if there are any ingredients
if ($result->num_rows > 0) {
    // Initialize an array to store the ingredients
    $ingredients = [];
    
    // Fetch each ingredient and add to the array
    while ($row = $result->fetch_assoc()) {
        $ingredients[] = $row;
    }

    // Return the ingredients as a JSON response
    echo json_encode($ingredients);
} else {
    // If no ingredients found, return an empty array
    echo json_encode([]);
}

$conn->close();
?>

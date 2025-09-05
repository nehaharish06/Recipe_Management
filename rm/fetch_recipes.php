<?php
include 'db_connection.php';

$sql = "SELECT Recipe_id, recipe_name, recipe_type, quantity, description FROM RECIPE";
$result = $conn->query($sql);

$recipes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($recipes);
?>

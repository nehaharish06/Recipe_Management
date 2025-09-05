<?php
header('Content-Type: application/json'); // Return JSON response

$host = 'localhost';
$user = 'root';
$password = '';

// Connect to multiple databases
$db1 = new mysqli($host, $user, $password, 'recipe_management');
$db2 = new mysqli($host, $user, $password, 'world_recipes');
$db3 = new mysqli($host, $user, $password, 'chef_specials');

if ($db1->connect_error || $db2->connect_error || $db3->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Fetch recipes from all databases
function fetchRecipes($db) {
    $recipes = [];
    $query = "SELECT Recipe_id, Name, Ingredients, Category FROM recipe LIMIT 5";
    $result = $db->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    
    return $recipes;
}

// Combine recipes from all databases
$all_recipes = array_merge(fetchRecipes($db1), fetchRecipes($db2), fetchRecipes($db3));

echo json_encode($all_recipes); // Return JSON response
?>

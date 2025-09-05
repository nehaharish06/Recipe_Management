<?php
include 'db_connection.php';

$search_query = $_GET['search_query']; // Get the search input from the form
$search_query = $conn->real_escape_string($search_query); // Escape user input for security

$results = [
    'recipes' => [],
    'users' => [],
    'ingredients' => []
];

// Check if the search query is numeric
if (is_numeric($search_query)) {
    $rating_threshold = (float)$search_query;

    // Fetch recipes with an average rating greater than or equal to the threshold
    $rating_query = "
        SELECT 
            r.Recipe_id, 
            r.Recipe_name, 
            AVG(rt.Rating_value) AS avg_rating 
        FROM RECIPE r 
        INNER JOIN RATING rt ON r.Recipe_id = rt.Recipe_id 
        GROUP BY r.Recipe_id 
        HAVING avg_rating >= $rating_threshold 
        ORDER BY avg_rating DESC";

    $rating_result = $conn->query($rating_query);
    if ($rating_result && $rating_result->num_rows > 0) {
        while ($row = $rating_result->fetch_assoc()) {
            $results['recipes'][] = $row;
        }
    }
} else {
    // Search in RECIPE table
    $recipe_query = "SELECT Recipe_id, Recipe_name, Description FROM RECIPE 
                     WHERE Recipe_name LIKE '%$search_query%' OR Description LIKE '%$search_query%'";
    $recipe_result = $conn->query($recipe_query);
    if ($recipe_result && $recipe_result->num_rows > 0) {
        while ($row = $recipe_result->fetch_assoc()) {
            $results['recipes'][] = $row;
        }
    }

    // Search in USER table
    $user_query = "SELECT User_id, Fname, Lname FROM USER 
                   WHERE Fname LIKE '%$search_query%' OR Lname LIKE '%$search_query%'";
    $user_result = $conn->query($user_query);
    if ($user_result && $user_result->num_rows > 0) {
        while ($row = $user_result->fetch_assoc()) {
            $results['users'][] = $row;
        }
    }

    // Search in INGREDIENTS table
    $ingredient_query = "SELECT Ing_id, Ing_name FROM INGREDIENTS 
                         WHERE Ing_name LIKE '%$search_query%'";
    $ingredient_result = $conn->query($ingredient_query);
    if ($ingredient_result && $ingredient_result->num_rows > 0) {
        while ($row = $ingredient_result->fetch_assoc()) {
            $results['ingredients'][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('https://img.freepik.com/premium-photo/magnifying-glass-beige-background-search-concept_661495-10886.jpg?semt=ais_hybrid');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .results {
            margin-top: 20px;
        }
        .results h2 {
            color: #4CAF50;
        }
        .result-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Search Results</h1>
    <div class="results">
        <h2>Recipes</h2>
        <?php if (count($results['recipes']) > 0): ?>
            <?php foreach ($results['recipes'] as $recipe): ?>
                <div class="result-item">
                    <a href="recipe_details.php?id=<?= htmlspecialchars($recipe['Recipe_id']); ?>">
                        <?= htmlspecialchars($recipe['Recipe_name']); ?>
                    </a> 
                    <?php if (isset($recipe['avg_rating'])): ?>
                        - Average Rating: <?= number_format($recipe['avg_rating'], 2); ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No recipes found.</p>
        <?php endif; ?>

        <h2>Users</h2>
        <?php if (count($results['users']) > 0): ?>
            <?php foreach ($results['users'] as $user): ?>
                <div class="result-item">
                    <?= htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>

        <h2>Ingredients</h2>
        <?php if (count($results['ingredients']) > 0): ?>
            <?php foreach ($results['ingredients'] as $ingredient): ?>
                <div class="result-item">
                    <?= htmlspecialchars($ingredient['Ing_name']); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No ingredients found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

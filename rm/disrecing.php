<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if Recipe_id is provided in the query string
if (!isset($_GET['recipe_id'])) {
    echo "Recipe not found.";
    exit();
}

$recipe_id = $_GET['recipe_id'];

// Fetch recipe details
$recipe_query = "SELECT * FROM RECIPE WHERE Recipe_id = '$recipe_id'";
$recipe_result = $conn->query($recipe_query);
if ($recipe_result->num_rows === 0) {
    echo "Recipe not found.";
    exit();
}
$recipe = $recipe_result->fetch_assoc();

// Fetch ingredients for the recipe
$ingredient_query = "
    SELECT i.Ing_name, i.Ing_amt 
    FROM RECIPE_INGREDIENT ri
    JOIN INGREDIENTS i ON ri.Ing_id = i.Ing_id
    WHERE ri.Recipe_id = '$recipe_id'";
$ingredient_result = $conn->query($ingredient_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            margin-top: 20px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recipe: <?= htmlspecialchars($recipe['Recipe_name']); ?></h1>
        <h2>Ingredients</h2>
        <table>
            <thead>
                <tr>
                    <th>Ingredient Name</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ingredient_result->num_rows > 0): ?>
                    <?php while ($ingredient = $ingredient_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($ingredient['Ing_name']); ?></td>
                            <td><?= htmlspecialchars($ingredient['Ing_amt']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No ingredients found for this recipe.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="my_recipe.php">Back to My Recipes</a>
    </div>
</body>
</html>

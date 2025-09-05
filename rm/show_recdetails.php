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

// Fetch recipe details including video link ✅
$recipe_query = "SELECT * FROM RECIPE WHERE Recipe_id = '$recipe_id'";
$recipe_result = $conn->query($recipe_query);

if ($recipe_result->num_rows === 0) {
    echo "Recipe not found.";
    exit();
}
$recipe = $recipe_result->fetch_assoc();

// Fetch ingredients for the recipe ✅
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
    <title>Recipe Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            background: url('https://png.pngtree.com/background/20230528/original/pngtree-table-with-many-indian-foods-picture-image_2778309.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .instructions {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .instructions ol {
            padding-left: 20px;
        }
        .video-container {
            margin-top: 20px;
            text-align: center;
        }
        .video-link {
            display: inline-block;
            padding: 10px 15px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s ease;
        }
        .video-link:hover {
            background: #c82333;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recipe Details</h1>
        <h2><?= htmlspecialchars($recipe['Recipe_name']); ?></h2>
        <p><strong>Description:</strong> <?= htmlspecialchars($recipe['Description']); ?></p>

        <h3>Ingredients:</h3>
        <?php if ($ingredient_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Ingredient Name</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($ingredient = $ingredient_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($ingredient['Ing_name']); ?></td>
                            <td><?= htmlspecialchars($ingredient['Ing_amt']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No ingredients found for this recipe.</p>
        <?php endif; ?>

        <!-- Display Instructions Below Ingredients -->
        <?php if (!empty($recipe['instructions'])): ?>
            <div class="instructions">
                <h3>Instructions:</h3>
                <ol>
                    <?php
                    $steps = explode("\n", $recipe['instructions']);
                    foreach ($steps as $step) {
                        if (trim($step) !== "") {
                            echo "<li>" . htmlspecialchars($step) . "</li>";
                        }
                    }
                    ?>
                </ol>
            </div>
        <?php endif; ?>

        <!-- ✅ Display Video Link if available -->
        <?php if (!empty($recipe['video_link'])): ?>
            <div class="video-container">
                <h3>Watch Video:</h3>
                <a href="<?= htmlspecialchars($recipe['video_link']); ?>" class="video-link" target="_blank">Watch Tutorial</a>
            </div>
        <?php endif; ?>

        <a href="my_recipe.php" class="back-button">Back to My Recipes</a>
    </div>
</body>
</html>

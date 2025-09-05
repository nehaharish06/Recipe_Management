<?php
include 'db_connection.php';

// Fetch ingredients from the database
$sql = "SELECT Ing_id, recipe_name, Ing_name, Ing_type FROM INGREDIENTS";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ingredients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('https://png.pngtree.com/background/20230610/original/pngtree-various-fruits-and-ingredients-laid-out-on-a-table-picture-image_3054212.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .ingredient-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .ingredient-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .ingredient-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .ingredient-card h2 {
            font-size: 1.5rem;
            margin: 0;
            padding: 15px;
            background: #4CAF50;
            color: white;
        }
        .ingredient-card p {
            padding: 15px;
            margin: 0;
            font-size: 1rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Our Ingredients</h1>
        <div class="ingredient-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="ingredient-card">
                        <h2><?= htmlspecialchars($row['recipe_name']); ?></h2>
                        <p><strong>Ingredient ID:</strong> <?= htmlspecialchars($row['Ing_id']); ?></p>
                        <p><strong>Ingredient Name:</strong> <?= htmlspecialchars($row['Ing_name']); ?></p>
                        <p><strong>Ingredient Type:</strong> <?= htmlspecialchars($row['Ing_type']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No ingredients found!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
include 'db_connection.php';

// Fetch recipes from the database
$sql = "SELECT Recipe_id, recipe_name, recipe_type, description FROM RECIPE";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recipes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('https://cdn.apartmenttherapy.info/image/fetch/f_auto,q_auto:eco,c_fill,g_auto,w_800,h_400/https%3A%2F%2Fs3.amazonaws.com%2Fpixtruder%2Foriginal_images%2Ff5cffedb779ce8ea3991f8020b5616d39ef6c0ee');
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
        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .recipe-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .recipe-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .recipe-card h2 {
            font-size: 1.5rem;
            margin: 0;
            padding: 15px;
            background: #4CAF50;
            color: white;
        }
        .recipe-card p {
            padding: 15px;
            margin: 0;
            font-size: 1rem;
            line-height: 1.6;
        }
        .recipe-card .rate-btn {
            display: block;
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 10px 0;
            font-size: 1rem;
            text-decoration: none;
            border-radius: 0 0 10px 10px;
            transition: background-color 0.3s;
        }
        .recipe-card .rate-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div id="recipes"></div> <!-- Recipes will be displayed here -->

<script>
fetch('http://localhost/rm/api/get_recipes.php')
  .then(response => response.json())
  .then(data => {
      let output = "";
      data.forEach(recipe => {
          output += `<p><strong>${recipe.Name}</strong> - ${recipe.Category}</p>`;
      });
      document.getElementById("recipes").innerHTML = output;
  })
  .catch(error => console.error('Error fetching recipes:', error));
</script>

    <div class="container">
        <h1>Our Recipes</h1>
        <div class="recipe-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="recipe-card">
                        <h2><?= htmlspecialchars($row['recipe_name']); ?></h2>
                        <p><strong>Type:</strong> <?= htmlspecialchars($row['recipe_type']); ?></p>
                        <p><?= htmlspecialchars($row['description']); ?></p>
                        <a href="rating_crud.php?recipe_id=<?= htmlspecialchars($row['Recipe_id']); ?>" class="rate-btn">Rate</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No recipes found!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

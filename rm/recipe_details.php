<?php
include 'db_connection.php';

// Get the Recipe_id from the URL
$recipe_id = $_GET['id'] ?? '';

// Ensure the recipe_id is not empty and is a valid ID
if (!empty($recipe_id)) {
    $recipe_id = $conn->real_escape_string($recipe_id); // Escape user input for security

    // Fetch the recipe details from the database
    $sql = "SELECT Recipe_id, Recipe_name, Recipe_type, Quantity, Description FROM RECIPE WHERE Recipe_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the recipe exists
    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
    } else {
        $message = "Recipe not found.";
    }

    $stmt->close();
} else {
    $message = "Invalid recipe ID.";
}
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
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .recipe-details {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .recipe-details h2 {
            color: #4CAF50;
        }
        .recipe-details p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Recipe Details</h1>
    <div class="recipe-details">
        <?php if (isset($recipe)): ?>
            <h2><?= htmlspecialchars($recipe['Recipe_name']); ?></h2>
            <p><strong>Recipe ID:</strong> <?= htmlspecialchars($recipe['Recipe_id']); ?></p>
            <p><strong>Recipe Type:</strong> <?= htmlspecialchars($recipe['Recipe_type']); ?></p>
            <p><strong>Quantity:</strong> <?= htmlspecialchars($recipe['Quantity']); ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($recipe['Description']); ?></p>
        <?php else: ?>
            <p><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <button class="back-button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>

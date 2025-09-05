<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection
include 'db_connection.php';

// Get user details from session
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Recipes</title>
    <style>
        /* Styling for the recipe cards */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            background-image: url('https://static.vecteezy.com/system/resources/previews/008/710/516/non_2x/seamless-pattern-with-cute-fast-food-cartoon-background-vector.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            color: #fff;
        }
        .recipes-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .recipe-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
        }
        .recipe-card:hover {
            transform: scale(1.05);
        }
        .recipe-title {
            font-size: 1.5em;
            color: #007BFF;
            margin-bottom: 10px;
        }
        .recipe-details {
            font-size: 1em;
            color: #555;
            margin-bottom: 10px;
        }
        .view-button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9em;
            display: inline-block;
        }
        .view-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>My Recipes</h2>
    <div class="recipes-container">
        <?php
        // Fetch and display recipes for the logged-in user
        $query = "SELECT Recipe_id, Recipe_name, Recipe_type, Quantity FROM RECIPE WHERE User_id = '$user_id'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            while ($recipe = mysqli_fetch_assoc($result)) {
                echo "<div class='recipe-card'>";
                echo "<h3 class='recipe-title'>" . htmlspecialchars($recipe['Recipe_name']) . "</h3>";
                echo "<p class='recipe-details'><strong>Type:</strong> " . htmlspecialchars($recipe['Recipe_type']) . "</p>";
                echo "<p class='recipe-details'><strong>Quantity:</strong> " . htmlspecialchars($recipe['Quantity']) . "</p>";
                // Updated View Recipe button to redirect to show_recdetails.php
                echo "<a class='view-button' href='show_recdetails.php?recipe_id=" . htmlspecialchars($recipe['Recipe_id']) . "'>View Recipe</a>";
                echo "</div>";
            }
        } else {
            echo "<p>You have not added any recipes yet.</p>";
        }
        ?>
    </div>
</body>
</html>

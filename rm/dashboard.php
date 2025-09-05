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
$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* General Body Styling */
        body {
            background-image: url('https://media.istockphoto.com/id/475511846/vector/kitchen-seamless-pattern-vector-background.jpg?s=612x612&w=0&k=20&c=inpW5Mc2MFyuc7PsMXVY49OUBU39EXekcCNe8xVeI_k='); /* Add your image URL here */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* Sidebar Styling */
        .sidebar {
            background-color: #333;
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            border-bottom: 1px solid #444;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .sidebar .dropdown {
            position: relative;
        }

        .sidebar .dropdown-content {
            display: none;
            background-color: #444;
        }

        .sidebar .dropdown:hover .dropdown-content {
            display: block;
        }

        .sidebar .dropdown-content a {
            padding-left: 40px;
            color: white;
            border-bottom: 1px solid #555;
        }

        .sidebar .dropdown-content a:hover {
            background-color: #666;
        }

        /* Content Styling */
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .welcome-message {
            text-align: center;
            margin-top: 20px;
            font-size: 24px;
        }

        /* Search Bar Styling */
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .search-bar {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            background-color: #333;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        .search-button:hover {
            background-color: #ddd;
            color: black;
        }

        .welcome-message {
            text-align: center;
            margin-top: 20px;
            font-size: 48px; /* Large font size */
            font-weight: bold; /* Make the text bold */
            color: #333; /* Set text color to dark gray for readability */
        }

        /* New "Show My Recipe" section */
        .my-recipe-link {
            text-align: center;
            margin-top: 40px;
            font-size: 20px;
        }

        .my-recipe-link a {
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            background-color: #333;
            color: white;
            border-radius: 5px;
        }

        .my-recipe-link a:hover {
            background-color: #575757;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="dashboard.php">Home</a>
        <div class="dropdown">
            <a href="#">Recipes</a>
            <div class="dropdown-content">
                <a href="recipe_crud.php">Manage Recipes</a>
                <a href="view_recipes.php">View Recipes</a>
                <a href="category_crud.php">Category</a> <!-- New Link -->
                <a href="has_crud.php">Recipe Has?</a> <!-- New Link -->
            </div>
        </div>
        <div class="dropdown">
            <a href="#">Ingredients</a>
            <div class="dropdown-content">
                <a href="ing_crud.php">Manage Ingredients</a>
                <a href="view_ingredients.php">View Ingredients</a>
                <a href="rec_ing.php">Recipe-Ingredients</a>
            </div>
        </div>
        <a href="view_users.php">Profile</a>
        <a href="user_crud.php">Users</a>
        <a href="logout.php">Logout</a>

        <!-- New Section: Top Recipes -->
        <a href="view_rate.php">Top Recipes</a> <!-- Link to top-rated recipes -->
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="search-container">
            <form action="search_results.php" method="GET">
                <input type="text" name="search_query" class="search-bar" placeholder="Search recipes, users, or ingredients..." required>
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>

        <div class="welcome-message">
            <p>Welcome, <?php echo htmlspecialchars($user_name); ?>!</p>
        </div>

        <!-- Show My Recipe Section -->
        <div class="my-recipe-link">
            <a href="my_recipe.php">Show My Recipes</a>
        </div>
    </div>

</body>
</html>

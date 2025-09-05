<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch all recipes and ingredients
$recipes_query = "SELECT Recipe_id, Recipe_name FROM RECIPE";
$ingredients_query = "SELECT Ing_id, Ing_name FROM INGREDIENTS";
$recipes_result = $conn->query($recipes_query);
$ingredients_result = $conn->query($ingredients_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected recipe and ingredient data for adding
    if (isset($_POST['add_recipe_id'])) {
        $recipe_id = $_POST['add_recipe_id'];
        $ing_id = $_POST['add_ing_id'];

        // Insert into RECIPE_INGREDIENT table
        $insert_query = "INSERT INTO RECIPE_INGREDIENT (Recipe_id, Ing_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ss", $recipe_id, $ing_id);

        if ($stmt->execute()) {
            echo "Ingredient added successfully!";
        } else {
            echo "Error adding ingredient.";
        }
        $stmt->close();
    }

    // Update functionality
    if (isset($_POST['update_recipe_id'])) {
        $recipe_id = $_POST['update_recipe_id'];
        $ing_id = $_POST['update_ing_id'];

        // Update the RECIPE_INGREDIENT table (Modify the existing record)
        $update_query = "UPDATE RECIPE_INGREDIENT SET Recipe_id = ?, Ing_id = ? WHERE Recipe_id = ? AND Ing_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssss", $recipe_id, $ing_id, $_POST['old_recipe_id'], $_POST['old_ing_id']);

        if ($stmt->execute()) {
            echo "Ingredient updated successfully!";
        } else {
            echo "Error updating ingredient.";
        }
        $stmt->close();
    }

    // Delete functionality
    if (isset($_POST['delete_recipe_id'])) {
        $recipe_id = $_POST['delete_recipe_id'];
        $ing_id = $_POST['delete_ing_id'];

        // Delete the record from RECIPE_INGREDIENT table
        $delete_query = "DELETE FROM RECIPE_INGREDIENT WHERE Recipe_id = ? AND Ing_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("ss", $recipe_id, $ing_id);

        if ($stmt->execute()) {
            echo "Ingredient deleted successfully!";
        } else {
            echo "Error deleting ingredient.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ingredients for Recipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            background-image: url('https://img.freepik.com/premium-vector/kitchen-seamless-background-pattern_53562-1281.jpg?semt=ais_hybrid');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            max-width: 800px;
            margin: 50px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .button-container button {
            width: 180px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container button:hover {
            background-color: #0056b3;
        }

        .form-container {
            display: none;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        .form-container.active {
            display: block;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // Function to show the appropriate form
        function showForm(formId) {
            document.querySelectorAll('.form-container').forEach(form => form.classList.remove('active'));
            document.getElementById(formId).classList.add('active');
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Manage Ingredients for Recipe</h1>

        <div class="button-container">
            <button onclick="showForm('add_form')">Add Ingredient</button>
            <button onclick="showForm('update_form')">Update Ingredient</button>
            <button onclick="showForm('delete_form')">Delete Ingredient</button>
        </div>

        <!-- Add Ingredient Form -->
        <div id="add_form" class="form-container">
            <h2>Add Ingredient to Recipe</h2>
            <form method="POST">
                <label for="add_recipe_id">Select Recipe:</label>
                <select name="add_recipe_id" id="add_recipe_id" required>
                    <?php while ($row = $recipes_result->fetch_assoc()): ?>
                        <option value="<?= $row['Recipe_id']; ?>"><?= $row['Recipe_name']; ?></option>
                    <?php endwhile; ?>
                </select><br><br>

                <label for="add_ing_id">Select Ingredient:</label>
                <select name="add_ing_id" id="add_ing_id" required>
                    <?php while ($row = $ingredients_result->fetch_assoc()): ?>
                        <option value="<?= $row['Ing_id']; ?>"><?= $row['Ing_name']; ?></option>
                    <?php endwhile; ?>
                </select><br><br>

                <button type="submit">Add Ingredient</button>
            </form>
        </div>

        <!-- Update Ingredient Form -->
        <div id="update_form" class="form-container">
            <h2>Update Ingredient for Recipe</h2>
            <form method="POST">
                <label for="update_recipe_id">Select Recipe:</label>
                <select name="update_recipe_id" id="update_recipe_id" required>
                    <?php
                    // Re-fetch results for dropdowns
                    $recipes_result = $conn->query($recipes_query);
                    while ($row = $recipes_result->fetch_assoc()):
                    ?>
                        <option value="<?= $row['Recipe_id']; ?>"><?= $row['Recipe_name']; ?></option>
                    <?php endwhile; ?>
                </select><br><br>

                <label for="update_ing_id">Select Ingredient:</label>
                <select name="update_ing_id" id="update_ing_id" required>
                    <?php
                    // Re-fetch results for dropdowns
                    $ingredients_result = $conn->query($ingredients_query);
                    while ($row = $ingredients_result->fetch_assoc()):
                    ?>
                        <option value="<?= $row['Ing_id']; ?>"><?= $row['Ing_name']; ?></option>
                    <?php endwhile; ?>
                </select><br><br>

                <input type="hidden" name="old_recipe_id" id="old_recipe_id">
                <input type="hidden" name="old_ing_id" id="old_ing_id">

                <button type="submit">Update Ingredient</button>
            </form>
        </div>

        <!-- Delete Ingredient Form -->
        <div id="delete_form" class="form-container">
            <h2>Delete Ingredient from Recipe</h2>
            <form method="POST">
                <label for="delete_recipe_id">Select Recipe:</label>
                <select name="delete_recipe_id" id="delete_recipe_id" required>
                    <?php
                    // Re-fetch results for dropdowns
                    $recipes_result = $conn->query($recipes_query);
                    while ($row = $recipes_result->fetch_assoc()):
                    ?>
                        <option value="<?= $row['Recipe_id']; ?>"><?= $row['Recipe_name']; ?></option>
                    <?php endwhile; ?>
                </select><br><br>

                <label for="delete_ing_id">Select Ingredient:</label>
                <select name="delete_ing_id" id="delete_ing_id" required>
                    <?php
                    // Re-fetch results for dropdowns
                    $ingredients_result = $conn->query($ingredients_query);
                    while ($row = $ingredients_result->fetch_assoc()):
                    ?>
                        <option value="<?= $row['Ing_id']; ?>"><?= $row['Ing_name']; ?></option>
                    <?php endwhile; ?>
                </select><br><br>

                <button type="submit">Delete Ingredient</button>
            </form>
        </div>

        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

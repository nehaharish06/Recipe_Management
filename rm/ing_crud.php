<?php
include 'db_connection.php';

// Handle form submission for Ingredient CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $ingredient_id = $_POST['ingredient_id'] ?? '';
    $recipe_name = $_POST['recipe_name'] ?? '';
    $ingredient_name = $_POST['ingredient_name'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $ing_type = $_POST['ing_type'] ?? ''; // New field

    switch ($action) {
        case 'create':
            $sql = "INSERT INTO ingredients (Ing_id, recipe_name, Ing_name, Ing_amt, ing_type) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $ingredient_id, $recipe_name, $ingredient_name, $quantity, $ing_type); // Updated column names
            $message = $stmt->execute() ? "Ingredient added successfully" : "Error adding ingredient: " . $conn->error;
            break;

        case 'update':
            $sql = "UPDATE ingredients SET recipe_name = ?, Ing_name = ?, Ing_amt = ?, ing_type = ? 
                    WHERE Ing_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $recipe_name, $ingredient_name, $quantity, $ing_type, $ingredient_id); // Updated column names
            $message = $stmt->execute() ? "Ingredient updated successfully" : "Error updating ingredient: " . $conn->error;
            break;

        case 'delete':
            $sql = "DELETE FROM ingredients WHERE Ing_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $ingredient_id);
            $message = $stmt->execute() ? "Ingredient deleted successfully" : "Error deleting ingredient: " . $conn->error;
            break;

        default:
            $message = "Invalid action!";
    }

    echo "<script>alert('$message');</script>";
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredient CRUD</title>
    <style>
        /* Styling for the interface */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('https://media.istockphoto.com/id/1352758440/photo/paper-shopping-food-bag-with-grocery-and-vegetables.jpg?s=612x612&w=0&k=20&c=iEYDgT97dpF7DuG4-QUJU3l0-5MKQb01mKbQgEG1pcc=');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .button-group {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        button:hover {
            transform: scale(1.1);
            background-color: #45a049;
        }
        .form-container {
            display: none;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container.active {
            display: block;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        function showForm(formId) {
            document.querySelectorAll('.form-container').forEach(form => form.classList.remove('active'));
            document.getElementById(formId).classList.add('active');
        }

        function fetchIngredients() {
            fetch('fetch_ing.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('ingredientTableBody');
                    tableBody.innerHTML = data.map(ingredient => ` 
                        <tr>
                            <td>${ingredient.Ing_id}</td>
                            <td>${ingredient.recipe_name}</td>
                            <td>${ingredient.Ing_name}</td>
                            <td>${ingredient.Ing_amt}</td>
                            <td>${ingredient.ing_type}</td>
                        </tr>
                    `).join('');
                });
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Ingredient CRUD Operations</h1>
        <div class="button-group">
            <button onclick="showForm('addForm')">Add Ingredient</button>
            <button onclick="showForm('updateForm')">Update Ingredient</button>
            <button onclick="showForm('deleteForm')">Delete Ingredient</button>
            <button onclick="showForm('viewForm'); fetchIngredients()">View Ingredients</button>
        </div>

        <!-- Add Ingredient Form -->
        <div id="addForm" class="form-container">
            <h2>Add Ingredient</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <label>Ingredient ID:</label>
                <input type="text" name="ingredient_id" required>
                <label>Recipe Name:</label>
                <input type="text" name="recipe_name" required>
                <label>Ingredient Name:</label>
                <input type="text" name="ingredient_name" required>
                <label>Ingredient Type:</label>
                <input type="text" name="ing_type" required>
                <label>Quantity:</label>
                <input type="text" name="quantity" required>
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Update Ingredient Form -->
        <div id="updateForm" class="form-container">
            <h2>Update Ingredient</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <label>Ingredient ID:</label>
                <input type="text" name="ingredient_id" required>
                <label>Recipe Name:</label>
                <input type="text" name="recipe_name" required>
                <label>Ingredient Name:</label>
                <input type="text" name="ingredient_name" required>
                <label>Ingredient Type:</label>
                <input type="text" name="ing_type" required>
                <label>Quantity:</label>
                <input type="text" name="quantity" required>
                <button type="submit">Update</button>
            </form>
        </div>

        <!-- Delete Ingredient Form -->
        <div id="deleteForm" class="form-container">
            <h2>Delete Ingredient</h2>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <label>Ingredient ID:</label>
                <input type="text" name="ingredient_id" required>
                <button type="submit">Delete</button>
            </form>
        </div>

        <!-- View Ingredients -->
        <div id="viewForm" class="form-container">
            <h2>View Ingredients</h2>
            <table>
                <thead>
                    <tr>
                        <th>Ingredient ID</th>
                        <th>Recipe Name</th>
                        <th>Ingredient Name</th>
                        <th>Quantity</th>
                        <th>Ingredient Type</th>
                    </tr>
                </thead>
                <tbody id="ingredientTableBody"></tbody>
            </table>
        </div>

        <!-- Go Back to Dashboard Button -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" style="text-decoration: none;">
                <button style="
                    padding: 10px 20px;
                    font-size: 16px;
                    background-color: #333;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: transform 0.3s ease;
                " onmouseover="this.style.backgroundColor='#575757'" 
                   onmouseout="this.style.backgroundColor='#333'">
                   Go Back to Dashboard
                </button>
            </a>
        </div>
    </div>
</body>
</html>

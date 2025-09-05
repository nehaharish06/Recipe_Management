<?php
include 'db_connection.php';

// Handle form submission for Recipe CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $recipe_id = $_POST['Recipe_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $recipe_name = $_POST['recipe_name'] ?? '';
    $recipe_type = $_POST['recipe_type'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $description = $_POST['description'] ?? '';
    $instructions = $_POST['instructions'] ?? '';
    $video_link = $_POST['video_link'] ?? '';

    switch ($action) {
        case 'create':
            $sql = "INSERT INTO RECIPE (Recipe_id, user_id, recipe_name, recipe_type, quantity, description, instructions, video_link) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $recipe_id, $user_id, $recipe_name, $recipe_type, $quantity, $description, $instructions, $video_link);
            if ($stmt->execute()) {
                echo "<script>
                    if (confirm('Recipe added successfully! Do you want to add ingredients for this recipe?')) {
                        window.location.href = 'ing_crud.php';
                    }
                </script>";
            } else {
                echo "<script>alert('Error adding recipe: " . $conn->error . "');</script>";
            }
            break;

            case 'update':
                $recipe_id = $_POST['Recipe_id'];
            
                $fields = [];
                $params = [];
                $types = "";
            
                if (!empty($_POST['recipe_name'])) {
                    $fields[] = "recipe_name = ?";
                    $params[] = $_POST['recipe_name'];
                    $types .= "s";
                }
                if (!empty($_POST['recipe_type'])) {
                    $fields[] = "recipe_type = ?";
                    $params[] = $_POST['recipe_type'];
                    $types .= "s";
                }
                if (!empty($_POST['quantity'])) {
                    $fields[] = "quantity = ?";
                    $params[] = $_POST['quantity'];
                    $types .= "s";
                }
                if (!empty($_POST['description'])) {
                    $fields[] = "description = ?";
                    $params[] = $_POST['description'];
                    $types .= "s";
                }
                if (!empty($_POST['instructions'])) {
                    $fields[] = "instructions = ?";
                    $params[] = $_POST['instructions'];
                    $types .= "s";
                }
                if (!empty($_POST['video_link'])) {
                    $fields[] = "video_link = ?";
                    $params[] = $_POST['video_link'];
                    $types .= "s";
                }
            
                if (!empty($fields)) {
                    $sql = "UPDATE RECIPE SET " . implode(", ", $fields) . " WHERE Recipe_id = ?";
                    $params[] = $recipe_id;
                    $types .= "s";
            
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param($types, ...$params);
                    $message = $stmt->execute() ? "Recipe updated successfully" : "Error updating recipe: " . $conn->error;
                    echo "<script>alert('$message');</script>";
                } else {
                    echo "<script>alert('No fields provided for update!');</script>";
                }
                break;
            


        case 'delete':
            $sql = "DELETE FROM RECIPE WHERE Recipe_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $recipe_id);
            $message = $stmt->execute() ? "Recipe deleted successfully" : "Error deleting recipe: " . $conn->error;
            echo "<script>alert('$message');</script>";
            break;

        default:
            echo "<script>alert('Invalid action!');</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe CRUD</title>
    <style>
        /* Styling for the interface */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('https://assets.epicurious.com/photos/57169f68cdfa3dbe4601dd89/16:9/w_2560%2Cc_limit/EP_04182015_GroceryBag_Menu_Hero_6x4.jpg');
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
        input, textarea {
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
        footer {
            text-align: center;
            margin-top: 20px;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 0;
        }
        .back-btn {
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .back-btn:hover {
            background-color: #007B9D;
        }
        .action-btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .update-sub-form {
    display: none;
}

.update-sub-form.active {
    display: block;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>RECIPES</h1>
        <h3>Choose the following</h3>
        <div class="button-group">
            <button onclick="showForm('addForm')">Add Recipe</button>
            <button onclick="showForm('updateForm')">Update Recipe</button>
            <button onclick="showForm('deleteForm')">Delete Recipe</button>
            <button onclick="showForm('viewForm'); fetchRecipes()">View Recipes</button>
        </div>

        <!-- Add Recipe Form -->
        <div id="addForm" class="form-container">
            <h2>Add Recipe</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <label>Recipe ID:</label>
                <input type="text" name="Recipe_id" required>
                <label>User ID:</label>
                <input type="text" name="user_id" required>
                <label>Recipe Name:</label>
                <input type="text" name="recipe_name" required>
                <label>Recipe Type:</label>
                <input type="text" name="recipe_type" required>
                <label>Quantity:</label>
                <input type="text" name="quantity" required>
                <label>Description:</label>
                <textarea name="description" rows="4" required></textarea>
                <label>Instructions:</label>
                <textarea name="instructions" rows="4" required></textarea>
                <label>Video Link:</label> 
                <input type="text" name="video_link">
                <div class="action-btn-group">
                    <button type="submit">Submit</button>
                    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
                </div>
            </form>
        </div>

        <!-- Update Recipe Form -->
        
<div id="updateForm" class="form-container">
    <h2>Update Recipe</h2>
    <div class="button-group">
        <button onclick="showUpdateForm('updateNameTypeForm')">Update Name and Type</button>
        <button onclick="showUpdateForm('updateQuantityDescriptionForm')">Update Quantity and Description</button>
        <button onclick="showUpdateForm('updateInstructionsVideoForm')">Update Instructions and Video</button>
    </div>

    <!-- Update Name and Type Form -->
    <div id="updateNameTypeForm" class="update-sub-form">
        <h3>Update Name and Type</h3>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <label>Recipe ID:</label>
            <input type="text" name="Recipe_id" required>
            <label>User ID:</label>
            <input type="text" name="user_id" required>
            <label>Recipe Name:</label>
            <input type="text" name="recipe_name">
            <label>Recipe Type:</label>
            <input type="text" name="recipe_type">
            <div class="action-btn-group">
                <button type="submit">Update Name and Type</button>
                <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
            </div>
        </form>
    </div>

    <!-- Update Quantity and Description Form -->
    <div id="updateQuantityDescriptionForm" class="update-sub-form">
        <h3>Update Quantity and Description</h3>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <label>Recipe ID:</label>
            <input type="text" name="Recipe_id" required>
            <label>Quantity:</label>
            <input type="text" name="quantity">
            <label>Description:</label>
            <textarea name="description" rows="4"></textarea>
            <div class="action-btn-group">
                <button type="submit">Update Quantity and Description</button>
                <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
            </div>
        </form>
    </div>

    <!-- Update Instructions and Video Form -->
    <div id="updateInstructionsVideoForm" class="update-sub-form">
        <h3>Update Instructions and Video</h3>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <label>Recipe ID:</label>
            <input type="text" name="Recipe_id" required>
            <label>Instructions:</label>
            <textarea name="instructions" rows="4"></textarea>
            <label>Video Link:</label>
            <input type="text" name="video_link">
            <div class="action-btn-group">
                <button type="submit">Update Instructions and Video</button>
                <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
            </div>
        </form>
    </div>
</div>

        <!-- Delete Recipe Form -->
        <div id="deleteForm" class="form-container">
            <h2>Delete Recipe</h2>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <label>Recipe ID:</label>
                <input type="text" name="Recipe_id" required>
                <div class="action-btn-group">
                    <button type="submit">Delete</button>
                    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
                </div>
            </form>
        </div>

        <!-- View Recipes -->
        <div id="viewForm" class="form-container">
            <h2>View Recipes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Recipe ID</th>
                        <th>Recipe Name</th>
                        <th>Recipe Type</th>
                        <th>Quantity</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="recipeTableBody"></tbody>
            </table>
            <div class="action-btn-group">
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        </div>
        </div>
    </div>

    <script>
function showForm(formId) {
    var forms = document.querySelectorAll('.form-container');
    forms.forEach(function(form) {
        form.classList.remove('active');
    });

    var form = document.getElementById(formId);
    if (form) {
        form.classList.add('active');
    }
}
function showUpdateForm(formId) {
    var forms = document.querySelectorAll('.update-sub-form');
    forms.forEach(function(form) {
        form.classList.remove('active');
    });

    var form = document.getElementById(formId);
    if (form) {
        form.classList.add('active');
    }
}
function fetchRecipes() {
    fetch('fetch_recipes.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('recipeTableBody');
            tableBody.innerHTML = '';
            data.forEach(recipe => {
                
                const row = `<tr>
                    <td>${recipe.Recipe_id}</td>
                    <td>${recipe.recipe_name}</td>
                    <td>${recipe.recipe_type}</td>
                    <td>${recipe.quantity}</td>
                    <td>${recipe.description}</td>
                    
                </tr>`;
                
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error fetching recipes:', error));
}
</script>
</body>
</html>



<?php
include 'db_connection.php';

// Handle form submission for Rating CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $rating_id = $_POST['Rating_id'] ?? null;
    $rating_value = $_POST['Rating_value'] ?? null;
    $comment = $_POST['Comment'] ?? '';
    $user_id = $_POST['User_id'] ?? null;
    $recipe_id = $_POST['Recipe_id'] ?? null;

    switch ($action) {
        case 'create':
            $sql = "INSERT INTO RATING (Rating_value, Comment, User_id, Recipe_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $rating_value, $comment, $user_id, $recipe_id);
            $message = $stmt->execute() ? "Rating added successfully" : "Error adding rating: " . $conn->error;
            echo "<script>alert('$message');</script>";
            break;

        case 'update':
            $sql = "UPDATE RATING SET Rating_value = ?, Comment = ?, User_id = ?, Recipe_id = ? WHERE Rating_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssi", $rating_value, $comment, $user_id, $recipe_id, $rating_id);
            $message = $stmt->execute() ? "Rating updated successfully" : "Error updating rating: " . $conn->error;
            echo "<script>alert('$message');</script>";
            break;

        case 'delete':
            $sql = "DELETE FROM RATING WHERE Rating_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $rating_id);
            $message = $stmt->execute() ? "Rating deleted successfully" : "Error deleting rating: " . $conn->error;
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
    <title>Rating CRUD</title>
    <style>
        /* Styling for the interface */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            background-image: url('https://t3.ftcdn.net/jpg/01/66/47/44/360_F_166474454_k4U9kW02BXZ1ihMvRifkcFb5eoFzD0To.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-container {
            display: none;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Rating CRUD Operations</h1>
        <div class="button-group">
            <button onclick="showForm('addForm')">Add Rating</button>
            <button onclick="showForm('updateForm')">Update Rating</button>
            <button onclick="showForm('deleteForm')">Delete Rating</button>
        </div>

        <!-- Add Rating Form -->
        <div id="addForm" class="form-container">
            <h2>Add Rating</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <label>Rating Value:</label>
                <input type="number" name="Rating_value" min="1" max="5" required>
                <label>Comment:</label>
                <textarea name="Comment" rows="4"></textarea>
                <label>User ID:</label>
                <input type="text" name="User_id" required>
                <label>Recipe ID:</label>
                <input type="text" name="Recipe_id" required>
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Update Rating Form -->
        <div id="updateForm" class="form-container">
            <h2>Update Rating</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <label>Rating ID:</label>
                <input type="number" name="Rating_id" required>
                <label>Rating Value:</label>
                <input type="number" name="Rating_value" min="1" max="5" required>
                <label>Comment:</label>
                <textarea name="Comment" rows="4"></textarea>
                <label>User ID:</label>
                <input type="text" name="User_id" required>
                <label>Recipe ID:</label>
                <input type="text" name="Recipe_id" required>
                <button type="submit">Update</button>
            </form>
        </div>

        <!-- Delete Rating Form -->
        <div id="deleteForm" class="form-container">
            <h2>Delete Rating</h2>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <label>Rating ID:</label>
                <input type="number" name="Rating_id" required>
                <button type="submit">Delete</button>
            </form>
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
    </script>
</body>
</html>

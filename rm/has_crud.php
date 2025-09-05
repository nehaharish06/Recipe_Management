<?php
include 'db_connection.php';

// Handle form submission for HAS operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $recipe_id = $_POST['recipe_id'] ?? '';
    $nutri_info = $_POST['nutri_info'] ?? '';
    $cook_time = $_POST['cook_time'] ?? '';

    switch ($action) {
        case 'create':
            $sql = "INSERT INTO HAS (User_id, Recipe_id, Nutri_info, Cook_time) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $user_id, $recipe_id, $nutri_info, $cook_time);
            if ($stmt->execute()) {
                $message = "HAS relationship added successfully";
                echo "<script>
                    if (confirm('HAS relationship added successfully! Do you want to view all relationships?')) {
                        window.location.href = 'has_crud.php';
                    }
                </script>";
            } else {
                $message = "Error adding HAS relationship: " . $conn->error;
                echo "<script>alert('$message');</script>";
            }
            break;

        case 'update':
            $sql = "UPDATE HAS SET Nutri_info = ?, Cook_time = ? WHERE User_id = ? AND Recipe_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nutri_info, $cook_time, $user_id, $recipe_id);
            $message = $stmt->execute() ? "HAS relationship updated successfully" : "Error updating HAS relationship: " . $conn->error;
            echo "<script>alert('$message');</script>";
            break;

        case 'delete':
            $sql = "DELETE FROM HAS WHERE User_id = ? AND Recipe_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $user_id, $recipe_id);
            $message = $stmt->execute() ? "HAS relationship deleted successfully" : "Error deleting HAS relationship: " . $conn->error;
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
    <title>HAS CRUD</title>
    <style>
        /* Styling for the interface */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('https://img.freepik.com/free-vector/hand-drawn-world-health-day-background_23-2149306515.jpg');
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Recipe has🤔...</h1>
        <div class="button-group">
            <button onclick="showForm('addForm')">Add HAS Relationship</button>
            <button onclick="showForm('updateForm')">Update HAS Relationship</button>
            <button onclick="showForm('deleteForm')">Delete HAS Relationship</button>
            <button onclick="showForm('viewForm'); fetchHAS()">View HAS Relationships</button>
        </div>

        <!-- Add HAS Form -->
        <div id="addForm" class="form-container">
            <h2>Add </h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <label>User ID:</label>
                <input type="text" name="user_id" required>
                <label>Recipe ID:</label>
                <input type="text" name="recipe_id" required>
                <label>Nutri Info:</label>
                <input type="text" name="nutri_info" required>
                <label>Cook Time:</label>
                <input type="text" name="cook_time" required>
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Update HAS Form -->
        <div id="updateForm" class="form-container">
            <h2>Update </h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <label>User ID:</label>
                <input type="text" name="user_id" required>
                <label>Recipe ID:</label>
                <input type="text" name="recipe_id" required>
                <label>Nutri Info:</label>
                <input type="text" name="nutri_info" required>
                <label>Cook Time:</label>
                <input type="text" name="cook_time" required>
                <button type="submit">Update</button>
            </form>
        </div>

        <!-- Delete HAS Form -->
        <div id="deleteForm" class="form-container">
            <h2>Delete </h2>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <label>User ID:</label>
                <input type="text" name="user_id" required>
                <label>Recipe ID:</label>
                <input type="text" name="recipe_id" required>
                <button type="submit">Delete</button>
            </form>
        </div>

        <!-- View HAS -->
        <div id="viewForm" class="form-container">
            <h2>View </h2>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Recipe ID</th>
                        <th>Nutri Info</th>
                        <th>Cook Time</th>
                    </tr>
                </thead>
                <tbody id="hasTableBody"></tbody>
            </table>
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

    function fetchHAS() {
        fetch('fetch_has.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('hasTableBody');
                tableBody.innerHTML = '';
                data.forEach(row => {
                    const rowHtml = `<tr>
                        <td>${row.User_id}</td>
                        <td>${row.Recipe_id}</td>
                        <td>${row.Nutri_info}</td>
                        <td>${row.Cook_time}</td>
                    </tr>`;
                    tableBody.innerHTML += rowHtml;
                });
            })
            .catch(error => console.error('Error fetching HAS data:', error));
    }
    </script>
</body>
</html>



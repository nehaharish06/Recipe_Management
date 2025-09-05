<?php
include 'db_connection.php';

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // CREATE
    if ($action == 'create') {
        $category_id = $_POST['category_id'];
        $recipe_id = $_POST['recipe_id'];
        $type = $_POST['type'];
        $diet_type = $_POST['diet_type'];
        $regional = $_POST['regional'];

        $sql = "INSERT INTO CATEGORY (Category_id, Recipe_id, Type, Diet_Type, Regional) 
                VALUES ('$category_id', '$recipe_id', '$type', '$diet_type', '$regional')";

        if ($conn->query($sql) === TRUE) {
            $message = "New category added successfully!";
        } else {
            $message = "Error adding category: " . $conn->error;
        }
    }

    // UPDATE
    elseif ($action == 'update') {
        $category_id = $_POST['category_id'];
        $recipe_id = $_POST['recipe_id'];
        $type = $_POST['type'];
        $diet_type = $_POST['diet_type'];
        $regional = $_POST['regional'];

        $sql = "UPDATE CATEGORY 
                SET Recipe_id='$recipe_id', Type='$type', Diet_Type='$diet_type', Regional='$regional' 
                WHERE Category_id='$category_id'";

        if ($conn->query($sql) === TRUE) {
            $message = "Category updated successfully!";
        } else {
            $message = "Error updating category: " . $conn->error;
        }
    }

    // DELETE
    elseif ($action == 'delete') {
        $category_id = $_POST['category_id'];

        $sql = "DELETE FROM CATEGORY WHERE Category_id='$category_id'";

        if ($conn->query($sql) === TRUE) {
            $message = "Category deleted successfully!";
        } else {
            $message = "Error deleting category: " . $conn->error;
        }
    }
}

// READ (Display all categories)
$sql = "SELECT * FROM CATEGORY";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category CRUD</title>
    <style>
        /* Styling for the interface */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://t3.ftcdn.net/jpg/07/56/46/90/360_F_756469075_DU8FRMdOsrRQQsgKzBERBEfSFUyr10Pt.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 800px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
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
            background-color: rgb(90, 108, 227);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        button:hover {
            transform: scale(1.1);
            background-color: rgb(19, 54, 110);
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
        .radio-group {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 5px 0;
        }
        .radio-group label {
            margin-right: 10px;
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
        <h1>Category CRUD Operations</h1>

        <div class="button-group">
            <button onclick="showForm('addForm')">Add Category</button>
            <button onclick="showForm('updateForm')">Update Category</button>
            <button onclick="showForm('deleteForm')">Delete Category</button>
            <button onclick="showForm('viewForm')">View Categories</button>
        </div>

        <!-- Add Category Form -->
        <div id="addForm" class="form-container">
            <h2>Add Category</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <label>Category ID:</label>
                <input type="text" name="category_id" required>
                <label>Recipe ID:</label>
                <input type="text" name="recipe_id" required>
                <label>Type:</label>
                <input type="text" name="type" required>
                <label>Diet Type:</label>
                <div class="radio-group">
                    <label><input type="radio" name="diet_type" value="vegan" required> Vegan</label>
                    <label><input type="radio" name="diet_type" value="veg" required> Veg</label>
                    <label><input type="radio" name="diet_type" value="non veg" required> Non-Veg</label>
                </div>
                <label>Regional:</label>
                <input type="text" name="regional" required>
                <button type="submit">Add</button>
            </form>
        </div>

        <!-- Update Category Form -->
        <div id="updateForm" class="form-container">
            <h2>Update Category</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <label>Category ID:</label>
                <input type="text" name="category_id" required>
                <label>Recipe ID:</label>
                <input type="text" name="recipe_id" required>
                <label>Type:</label>
                <input type="text" name="type" required>
                <label>Diet Type:</label>
                <div class="radio-group">
                    <label><input type="radio" name="diet_type" value="vegan" required> Vegan</label>
                    <label><input type="radio" name="diet_type" value="veg" required> Veg</label>
                    <label><input type="radio" name="diet_type" value="non veg" required> Non-Veg</label>
                </div>
                <label>Regional:</label>
                <input type="text" name="regional" required>
                <button type="submit">Update</button>
            </form>
        </div>

        <!-- Delete Category Form -->
        <div id="deleteForm" class="form-container">
            <h2>Delete Category</h2>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <label>Category ID:</label>
                <input type="text" name="category_id" required>
                <button type="submit">Delete</button>
            </form>
        </div>

        <!-- View Categories -->
        <div id="viewForm" class="form-container">
            <h2>View Categories</h2>
            <table>
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Recipe ID</th>
                        <th>Type</th>
                        <th>Diet Type</th>
                        <th>Regional</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['Category_id']}</td>
                                <td>{$row['Recipe_id']}</td>
                                <td>{$row['Type']}</td>
                                <td>{$row['Diet_Type']}</td>
                                <td>{$row['Regional']}</td>
                              </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No categories found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript to handle showing forms and alerts -->
    <script>
        function showForm(formId) {
            var forms = document.querySelectorAll('.form-container');
            forms.forEach(function (form) {
                form.classList.remove('active');
            });

            var form = document.getElementById(formId);
            if (form) {
                form.classList.add('active');
            }
        }

        // PHP will output a JavaScript variable if there's a message
        <?php if (!empty($message)) : ?>
            alert("<?php echo $message; ?>");
        <?php endif; ?>
    </script>
</body>
</html>

<?php
include 'db_connection.php';  // Include the database connection

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // CREATE
    if ($action == 'create') {
        $user_id = $_POST['user_id']; // User ID field (varchar)
        $fname = $_POST['fname'];
        $minit = $_POST['minit'];
        $lname = $_POST['lname'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

        // Insert query to add the user to the USER table
        $sql = "INSERT INTO USER (User_id, Fname, Minit, Lname, Password) VALUES ('$user_id', '$fname', '$minit', '$lname', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New user added!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }

    // UPDATE
    elseif ($action == 'update') {
        $user_id = $_POST['user_id']; // User ID field
        $fname = $_POST['fname'];
        $minit = $_POST['minit'];
        $lname = $_POST['lname'];

        // Update query to modify user details in the USER table
        $sql = "UPDATE USER SET Fname='$fname', Minit='$minit', Lname='$lname' WHERE User_id='$user_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('User updated successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }

    // DELETE
    elseif ($action == 'delete') {
        $user_id = $_POST['user_id']; // User ID field for deleting

        // Check if the password field is set before accessing it
        $entered_password = isset($_POST['password']) ? $_POST['password'] : '';

        // Query to fetch the stored password for the given User_id
        $sql = "SELECT Password FROM USER WHERE User_id='$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Fetch the stored password
            $row = $result->fetch_assoc();
            $stored_password = $row['Password'];

            // Verify if the entered password matches the stored password
            if (password_verify($entered_password, $stored_password)) {
                // Delete query to remove the user from the USER table
                $delete_sql = "DELETE FROM USER WHERE User_id='$user_id'";
                if ($conn->query($delete_sql) === TRUE) {
                    echo "<script>alert('User deleted successfully!');</script>";
                } else {
                    echo "<script>alert('Error: " . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Incorrect password. Deletion aborted.');</script>";
            }
        } else {
            echo "<script>alert('User not found.');</script>";
        }
    }
}

// READ (Display all users)
$sql = "SELECT * FROM USER";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User CRUD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f2f2f2; /* Light gray background for better readability */
            background-image: url('https://chefkart-strapi-media.s3.ap-south-1.amazonaws.com/Website_banner_01_web_1_40ea9de1d0.webp'); /* Replace 'background-image-url' with your image path */
            background-size: cover;
            background-position: center;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* White background with transparency for readability */
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease-in-out;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        button:hover {
            background-color: #45a049;
            transform: scale(1.1);
        }
        .form-container {
            display: none;
            transition: opacity 0.5s ease-in-out;
            opacity: 0;
        }
        .form-container.active {
            display: block;
            opacity: 1;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            transition: all 0.3s ease-in-out;
        }
        input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease-in-out;
        }
        input:focus {
            border-color: #4CAF50;
        }
    </style>
    <script>
        function showForm(formId) {
            const forms = document.querySelectorAll('.form-container');
            forms.forEach(form => form.classList.remove('active'));
            document.getElementById(formId).classList.add('active');
        }

        window.onload = function() {
            showForm('addForm'); // Show Add form by default when page loads
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>User CRUD Operations</h1>
        <div class="button-group">
            <button onclick="showForm('addForm')">Add User</button>
            <button onclick="showForm('updateForm')">Update User</button>
            <button onclick="showForm('deleteForm')">Delete User</button>
        </div>

        <div id="addForm" class="form-container active">
            <h2>Add New User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" required>
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" required>
                <label for="minit">Middle Initial:</label>
                <input type="text" id="minit" name="minit" maxlength="1" required>
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Add User</button>
            </form>
        </div>

        <div id="updateForm" class="form-container">
            <h2>Update User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" required>
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" required>
                <label for="minit">Middle Initial:</label>
                <input type="text" id="minit" name="minit" maxlength="1" required>
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" required>
                <button type="submit">Update User</button>
            </form>
        </div>

        <div id="deleteForm" class="form-container">
            <h2>Delete User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="delete">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Delete User</button>
            </form>
        </div>
    </div>
</body>
</html>

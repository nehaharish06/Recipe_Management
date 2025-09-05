<?php
include 'db_connection.php'; // Ensure the correct path to db_connection.php

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']); // Sanitize User_id
    $fname = mysqli_real_escape_string($conn, $_POST['fname']); // Sanitize First Name
    $minit = mysqli_real_escape_string($conn, $_POST['minit']); // Sanitize Middle Initial
    $lname = mysqli_real_escape_string($conn, $_POST['lname']); // Sanitize Last Name
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Ensure unique User_id by checking for existing entry
    $check_user_id = "SELECT User_id FROM USER WHERE User_id = '$user_id'";
    $result = $conn->query($check_user_id);

    if ($result->num_rows > 0) {
        echo "<script>alert('User ID already exists. Please choose a different ID.');</script>";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO USER (User_id, Fname, Minit, Lname, Password) 
                VALUES ('$user_id', '$fname', '$minit', '$lname', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New user added successfully!');</script>";
            header('Location: login.php'); // Redirect to login.php after successful registration
            exit(); // Ensure no further code runs after redirect
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://static.vecteezy.com/system/resources/thumbnails/036/431/471/small_2x/seamless-pattern-green-cabbage-illustration-design-for-kale-day-healthy-food-health-day-recipes-green-and-white-background-cartoon-assorted-cabbage-for-cover-book-decoration-postcard-vector.jpg') no-repeat center center fixed; /* Add your background image URL here */
            background-size: cover; /* Make sure the image covers the entire screen */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9); /* Add a semi-transparent background for better readability */
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-container h1 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .login-container .signup-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-container .signup-link a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .login-container .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Add User</h1>
        <form method="POST">
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
        <div class="signup-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>

<?php
session_start(); // Start the session

// Include the database connection
include 'config.php'; // Update with the correct path to your config.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = trim($_POST['user_id']);
    $password = trim($_POST['password']);

    // Prepare and execute the SQL query to fetch the user
    $sql = "SELECT * FROM user WHERE User_id = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['Password'])) {
                // Store user data in session
                $_SESSION['user_id'] = $row['User_id'];
                $_SESSION['user_name'] = $row['Fname']; // Assuming the column for first name is 'Fname'

                // Redirect to dashboard
                header('Location: dashboard.php');
                exit();
            } else {
                $error_message = "Invalid Password!";
            }
        } else {
            $error_message = "Invalid User ID!";
        }
        $stmt->close();
    } else {
        $error_message = "Failed to prepare the SQL statement.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://t3.ftcdn.net/jpg/08/76/85/70/360_F_876857051_aGe8ozEkAYUHXnFoBKB76sFnmisY9aPG.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #ffffff;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            color: #333;
        }
        .login-container h1 {
            text-align: center;
            color: #333;
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
        .error-message {
            color: red;
            text-align: center;
        }
        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <div class="signup-link">
            <p>Don't have an account? <a href="add_user.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>

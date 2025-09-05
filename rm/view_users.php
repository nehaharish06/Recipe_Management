<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please log in to access your profile.';
    header('Location: login.php');
    exit();
}

// Include database connection
include 'db_connection.php';

// Get user details from session
$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT * FROM user WHERE User_id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('MySQL prepare failed: ' . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $_SESSION['error_message'] = 'User not found.';
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://t3.ftcdn.net/jpg/08/76/85/70/360_F_876857051_aGe8ozEkAYUHXnFoBKB76sFnmisY9aPG.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 600px;
            height: 500px; /* Fixed height */
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            overflow: hidden; /* Prevents text from resizing container */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Profile Picture Styling */
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #ccc;
            margin: 0 auto;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: #fff;
            text-transform: uppercase;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-details {
            margin-top: 20px;
            text-align: left;
        }

        .profile-details p {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
            padding: 8px;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 5px;
        }

        .profile-details span {
            font-weight: bold;
            color: #007BFF;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>User Profile</h1>

        <!-- Profile Picture (Placeholder or uploaded picture) -->
        <div class="profile-picture">
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
            <?php else: ?>
                <?php echo strtoupper(substr($user['Fname'], 0, 1)); ?>
            <?php endif; ?>
        </div>

        <!-- User Details -->
        <div class="profile-details">
            <p><span>User ID:</span> <?php echo htmlspecialchars($user['User_id']); ?></p>
            <p><span>First Name:</span> <?= htmlspecialchars($user['Fname']) ?: 'Not available'; ?></p>
            <p><span>Middle Initial:</span> <?= htmlspecialchars($user['Minit']) ?: 'Not available'; ?></p>
            <p><span>Last Name:</span> <?= htmlspecialchars($user['Lname']) ?: 'Not available'; ?></p>
            
        </div>

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>

</body>
</html>

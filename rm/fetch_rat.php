<?php
// Include the database connection
include 'db_connection.php';  // Ensure this points to the correct path for your config.php file

// Fetch ratings data in descending order
$ratings_query = "SELECT r.recipe_id, r.recipe_name, r.user_id AS creator_user_id, rt.rating_value 
                  FROM recipes r 
                  INNER JOIN ratings rt ON r.recipe_id = rt.recipe_id 
                  ORDER BY rt.rating_value DESC";


$ratings_result = $conn->query($ratings_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ratings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #333;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #ddd;
        }
        h1 {
            text-align: center;
        }
        .no-data {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>

    <h1>Recipe Ratings</h1>

    <?php if ($ratings_result && $ratings_result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Recipe ID</th>
                <th>Recipe Name</th>
                <th>User ID</th>
                <th>Rating</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $ratings_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['recipe_id']); ?></td>
                <td><?php echo htmlspecialchars($row['recipe_name']); ?></td>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['rating']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="no-data">No ratings found.</p>
    <?php endif; ?>

</body>
</html>

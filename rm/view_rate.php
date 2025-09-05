<?php
// Include the database connection
include 'db_connection.php';  // Ensure this points to the correct path for your config.php file

// Fetch recipes along with the average rating in descending order
$ratings_query = "
    SELECT 
        r.Recipe_id, 
        r.Recipe_name, 
        r.User_id AS creator_user_id, 
        AVG(rt.Rating_value) AS avg_rating  -- Calculate the average rating dynamically
    FROM RECIPE r 
    INNER JOIN RATING rt ON r.Recipe_id = rt.Recipe_id 
    GROUP BY r.Recipe_id  -- Group by Recipe_id to calculate the average rating per recipe
    ORDER BY avg_rating DESC";  // Sort by the average rating in descending order

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
            background-image: url('https://media.istockphoto.com/id/1063311616/vector/hand-holding-trophy-cup-tophy-form-lines-triangles-and-particle-style-design-illustration.jpg?s=612x612&w=0&k=20&c=Nsq8eg-J52Olur2sxA6pV_yBT8Yy9sKe9jL8-Kz-jTg=');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
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

        table tr:nth-child(even), table tr:nth-child(odd) {
            background-color: white;  /* Set rows to white */
        }

        table tr:hover {
            background-color: #f1f1f1;  /* Light gray hover effect */
        }

        h1 {
            text-align: center;
            color:white;
        }

        .no-data {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>

    <h1>Recipe Rankings</h1>

    <?php if ($ratings_result && $ratings_result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Recipe ID</th>
                <th>Recipe Name</th>
                <th>User ID</th>
                <th>Average Rating</th>  <!-- Change the column to 'Average Rating' -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $ratings_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['Recipe_id']); ?></td>
                <td><?php echo htmlspecialchars($row['Recipe_name']); ?></td>
                <td><?php echo htmlspecialchars($row['creator_user_id']); ?></td>
                <td><?php echo number_format($row['avg_rating'], 2); ?></td> <!-- Display the average rating -->
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="no-data">No ratings found.</p>
    <?php endif; ?>

</body>
</html>

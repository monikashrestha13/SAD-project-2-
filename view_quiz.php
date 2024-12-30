<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "quiz"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all quizzes from the database
$sql = "SELECT q.id, q.question_text, c.category_name FROM questions q JOIN categories c ON q.category_id = c.id";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Quizzes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            color:black;
        }

        .container {
            width: 70%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 30px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        .action-btn {
    padding: 5px 10px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-right: 10px; /* Creates a gap between the Edit and Delete buttons */
}

.action-btn:hover {
    background-color: #2980b9;
}
        .error {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>View Quizzes</h1>

    <!-- Display Error Messages -->
    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] == 'quiz_not_found'): ?>
            <div class="error">Quiz not found. It may have been deleted or doesn't exist.</div>
        <?php elseif ($_GET['error'] == 'invalid_quiz_id'): ?>
            <div class="error">Invalid quiz ID.</div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Display the quizzes in a table -->
    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['question_text']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td>
                             <div style="display: flex; gap: 10px;">
                            <a href="edit_quiz.php?quiz_id=<?php echo $row['id']; ?>" class="action-btn">Edit</a>
                            <!-- You can add a delete button if needed -->
                            <a href="delete_quiz.php?quiz_id=<?php echo $row['id']; ?>" class="action-btn" onclick="return confirm('Are you sure you want to delete this quiz?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No quizzes found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

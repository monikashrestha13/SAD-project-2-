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

// Fetch categories from the database
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}


// Close connection
$conn->close();
?>
<?php
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// User is logged in, continue with the page content
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Game - Select Category</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .quiz-container {
            max-width: 400px;
            margin: 90px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            color: #2c3e50;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        button {
            display: block;
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        button:hover {
            background-color: #2980b9;
        }

        p {
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="quiz-container">
    <h1>Welcome to the Quiz App</h1>
    <p>Select a category to get started:</p>
    <form action="quiz.php" method="POST">
        <?php if (!empty($categories)) : ?>
            <?php foreach ($categories as $category) : ?>
                <label>
                    <input type="radio" name="category" value="<?php echo $category['id']; ?>" required>
                    <?php echo $category['category_name']; ?>
                </label>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No categories available. Please check back later.</p>
        <?php endif; ?>
        <button type="submit">Start Quiz</button>
    </form>
</div>
</body>
</html>

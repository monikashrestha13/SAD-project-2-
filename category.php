<?php
// Start the session for user authentication
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "quiz"; // Your database name

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
    $category_name = $_POST['category_name'];

    if (!empty($category_name)) {
        // Insert category into database
        $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        
        if ($stmt->execute()) {
            $message = "New category added successfully!";
            $message_type = 'success';
        } else {
            $message = "Error: " . $stmt->error;
            $message_type = 'error';
        }
        $stmt->close();
    } else {
        $message = "Please enter a category name.";
        $message_type = 'error';
    }
}

// Handle Delete Category
if (isset($_GET['delete_category_id'])) {
    $category_id = $_GET['delete_category_id'];

    // Delete category from database
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        $message = "Category deleted successfully!";
        $message_type = 'success';
    } else {
        $message = "Error: " . $stmt->error;
        $message_type = 'error';
    }
    $stmt->close();
}

// Fetch existing categories
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

// Initialize an empty array for categories
$categories = [];

if ($result->num_rows > 0) {
    // Fetch all categories from the database
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
} else {
    // If no categories found, set an error message
    $categories = [];
    $message = "No categories found in the database.";
    $message_type = 'error';
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    <link rel="stylesheet" href="admin_dashboard.css"> <!-- Add your CSS file -->
    <style>
        /* Some basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
        }

        .message.success {
            background-color: #4CAF50;
            color: white;
        }

        .message.error {
            background-color: #f44336;
            color: white;
        }

        button {
            background-color: #f44336;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #d32f2f;
        }

        a {
            color: #2196F3;
            text-decoration: none;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .add-category-form input {
            padding: 8px;
            width: 200px;
        }

        .add-category-form button {
            padding: 8px 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Category Management</h2>
    
    <!-- Display Success or Error Message -->
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Add Category Form -->
    <div class="add-category-form">
        <h3>Add New Category</h3>
        <form action="category.php" method="POST">
            <input type="text" name="category_name" placeholder="Enter category name" required>
            <button type="submit">Add Category</button>
        </form>
    </div>

    <!-- Existing Categories Table -->
    <h3>Existing Categories</h3>
    <table>
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="edit_category.php?id=<?php echo $category['id']; ?>">Edit</a>
                            <!-- Delete Button -->
                            <button onclick="confirmDelete(<?php echo $category['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No categories available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- JavaScript for deletion confirmation -->
<script>
    function confirmDelete(categoryId) {
        if (confirm("Are you sure you want to delete this category?")) {
            window.location.href = 'category.php?delete_category_id=' + categoryId;
        }
    }
</script>

</body>
</html>

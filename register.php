<?php
include('connection.php'); // Ensure you have the correct database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Initialize error and success messages
$error_message = "";
$success_message = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check for empty inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required!";
    }

    // Validate password length
    elseif (strlen($password) < 8) {
        $error_message = "Password should be at least 8 characters long.";
    }

    // If validation is passed
    if (empty($error_message)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO register (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Check if the statement was prepared successfully
        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                $success_message = "Account created successfully! <a href='login.php'>Login now</a>";
            } else {
                $error_message = "Error executing query: " . $stmt->error;
            }
        } else {
            $error_message = "Error in SQL statement: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Register</title>
</head>
<body>
    <div class="register-container">
        <form action="register.php" method="POST">
            <h2>Register an Account</h2>
            <!-- Display error or success message -->
            <?php if ($error_message): ?>
                <div style="color: red; text-align: center;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div style="color: green; text-align: center;">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <label>Username:</label>
            <input type="text" name="username" required><br>
            
            <label>Email:</label>
            <input type="email" name="email" required><br>
            
            <label>Password:</label>
            <input type="password" name="password" required><br>
            
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>

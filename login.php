<?php
session_start();
include('connection.php');

$error_message = ""; // For error display

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        // Retrieve user from the database
        $sql = "SELECT id, username, password FROM register WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
    <script>
        // JavaScript function for form validation
        function validateForm() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            var errorMessage = '';

            // Check if username is empty
            if (username === '') {
                errorMessage += 'Username is required.\n';
            }

            // Check if password is empty
            if (password === '') {
                errorMessage += 'Password is required.\n';
            }

            // If there's any error, show the alert and return false
            if (errorMessage !== '') {
                alert(errorMessage);
                return false;
            }

            // If no error, allow form submission
            return true;
        }
    </script>
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="POST" onsubmit="return validateForm()">
            <h2>Login</h2>
            
            <!-- Display error message if any -->
            <?php if ($error_message): ?>
                <div style="color: red; text-align: center;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>

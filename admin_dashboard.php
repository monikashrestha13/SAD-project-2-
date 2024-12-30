<?php
// Start the session for user authentication (ensure user is logged in)
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css"> 
    <link rel="stylesheet" href="add_quiz.css"> 
    <!-- Use your existing CSS -->
</head>
<body>

<div class="dashboard">
    <div class="quiz-sidebar">
        <h2 class="app-title">QUIZ GAME</h2>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="category.php">Categories</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Manage Quizzes</a>
                <ul class="submenu">
                    <li><a href="add_quiz.php">Add Quiz</a></li>
                    <li><a href="view_quiz.php">View Quizzes</a></li>
                </ul>
            </li>
            <li><a href="results.php">Results</a></li>
            <li><a href="admin_login.php" class="logout">Logout</a></li>
        </ul>
    </div>

    <main class="content">
        <div class="welcome-container">
            <h2>Welcome to the Dashboard, Admin</h2>
            <p>Select an option from the sidebar to get started.</p>
        </div>
    </main>
</div>
</body>
</html>

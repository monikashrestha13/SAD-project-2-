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

// Check if quiz_id is passed in the URL
if (isset($_GET['quiz_id']) && is_numeric($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    // Delete the quiz from the database
    $sql = "DELETE FROM questions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quiz_id);

    if ($stmt->execute()) {
        // Redirect to view_quizzes.php with a success message
        header("Location: view_quiz.php?success=quiz_deleted");
    } else {
        // Redirect to view_quizzes.php with an error message
        header("Location: view_quiz.php?error=quiz_delete_failed");
    }
    $stmt->close();
} else {
    // Redirect if quiz_id is not valid
    header("Location: view_quiz.php?error=invalid_quiz_id");
}

$conn->close();
?>

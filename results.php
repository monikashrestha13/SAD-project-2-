<?php
// Start session
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "quiz"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all user results
$sql = "SELECT username, score, date FROM results ORDER BY username ASC, date DESC";
$result = $conn->query($sql);

$allResults = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allResults[] = $row;
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Quiz Results</title>
    <link rel="stylesheet" href="results.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="results-container">
        <h1>All Quiz Results</h1>
        <?php if (!empty($allResults)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Score</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allResults as $result): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($result['username']); ?></td>
                            <td><?php echo htmlspecialchars($result['score']); ?></td>
                            <td><?php echo htmlspecialchars(date("d M Y, H:i", strtotime($result['date']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No quiz results available.</p>
        <?php endif; ?>
        <a href="admin_dashboard.php" class="back-button">← Back to Dashboard</a>
    </div>
</body>
</html>

<?php
include('connection.php'); // Include your database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if quiz_id is provided via GET method
if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    // Fetch the existing question details
    $sql = "SELECT * FROM questions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quiz_id); // Use 'i' for integer binding
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
    } else {
        die("No question found with this ID.");
    }
} else {
    die("No quiz ID provided.");
}

// Process the form submission to update the question
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the submitted data
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $category_id = $_POST['category_id'];

    // Prepare the update query
    $sql_update = "UPDATE questions SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ?, category_id = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssii", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option, $category_id, $quiz_id);

    if ($stmt_update->execute()) {
        // On successful update, show a success message
        echo "<script>alert('Quiz updated successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Failed to update quiz.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: -100px;
        }

        .message {
            background-color: #2ecc71;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 1.2em;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            color:black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block; /* Ensures label is a block element, making it visible */
            margin-bottom: 8px; /* Space between label and input */
            font-size: 1.2em;
            color:blueviolet; /* Optional: increase the font size for better visibility */
        }

        input, select, textarea {
            width: 90%;
            padding: 13px;
            font-size: 1em;
            margin-bottom: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        button:hover {
            background-color:rgb(13, 38, 52);
        }

        .question-wrapper {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
        }

        /* Styling for the quiz section */
        #quiz-section {
            display: none;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Quiz Question</h1>
    <form method="POST" action="edit_quiz.php?quiz_id=<?php echo $quiz_id; ?>">

        <div class="form-group">
            <label for="question_text">Question Text:</label>
            <textarea name="question_text" id="question_text" required><?php echo htmlspecialchars($question['question_text']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="option_a">Option A:</label>
            <input type="text" name="option_a" id="option_a" value="<?php echo htmlspecialchars($question['option_a']); ?>" required>
        </div>

        <div class="form-group">
            <label for="option_b">Option B:</label>
            <input type="text" name="option_b" id="option_b" value="<?php echo htmlspecialchars($question['option_b']); ?>" required>
        </div>

        <div class="form-group">
            <label for="option_c">Option C:</label>
            <input type="text" name="option_c" id="option_c" value="<?php echo htmlspecialchars($question['option_c']); ?>" required>
        </div>

        <div class="form-group">
            <label for="option_d">Option D:</label>
            <input type="text" name="option_d" id="option_d" value="<?php echo htmlspecialchars($question['option_d']); ?>" required>
        </div>

        <div class="form-group">
            <label for="correct_option">Correct Option:</label>
            <input type="text" name="correct_option" id="correct_option" value="<?php echo htmlspecialchars($question['correct_option']); ?>" required>
        </div>

        <div class="form-group">
            <label for="category_id">Category:</label>
            <input type="number" name="category_id" id="category_id" value="<?php echo htmlspecialchars($question['category_id']); ?>" required>
        </div>

        <button type="submit">Update Question</button>
        <a href="admin_dashboard.php"><button type="button">Back</button></a>
    </form>
</div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>

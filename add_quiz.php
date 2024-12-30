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

// Fetch categories for dropdown
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions</title>
    <link rel="stylesheet" href="style.css">
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
            padding:10px;
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
    <h1>Add Questions</h1>

    <!-- This will display a success message after form submission -->
    <?php if (isset($message)) : ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="submit.php">
        <!-- Questions Wrapper -->
        <div id="question-form-container">
            <div class="question-wrapper">
                <div class="form-group">
                    <label for="category0">Select Category:</label>
                    <select name="questions[0][category]" id="category0" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="question_text0">Question:</label>
                    <textarea name="questions[0][question_text]" id="question_text0" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="option_a0">Option A:</label>
                    <input type="text" name="questions[0][option_a]" id="option_a0" required>
                </div>

                <div class="form-group">
                    <label for="option_b0">Option B:</label>
                    <input type="text" name="questions[0][option_b]" id="option_b0" required>
                </div>

                <div class="form-group">
                    <label for="option_c0">Option C:</label>
                    <input type="text" name="questions[0][option_c]" id="option_c0" required>
                </div>

                <div class="form-group">
                    <label for="option_d0">Option D:</label>
                    <input type="text" name="questions[0][option_d]" id="option_d0" required>
                </div>

                <div class="form-group">
                    <label for="correct_option0">Correct Option (A, B, C, D):</label>
                    <input type="text" name="questions[0][correct_option]" id="correct_option0" required>
                </div>
            </div>
        </div>

        <button type="submit">Submit Questions</button>
        <button type="button" id="add-question-btn">Add Another Question</button>
        <a href="admin_dashboard.php"><button type="button">Back</button></a>
    </form>
</div>

<script>
    let questionCount = 1; // To track the number of questions
    const questionFormContainer = document.getElementById('question-form-container');
    
    // Function to add a new question form dynamically
    document.getElementById('add-question-btn').addEventListener('click', function() {
        let newQuestionWrapper = document.createElement('div');
        newQuestionWrapper.classList.add('question-wrapper');

        newQuestionWrapper.innerHTML = `
            <div class="form-group">
                <label for="category${questionCount}">Select Category:</label>
                <select name="questions[${questionCount}][category]" id="category${questionCount}" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="question_text${questionCount}">Question:</label>
                <textarea name="questions[${questionCount}][question_text]" id="question_text${questionCount}" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="option_a${questionCount}">Option A:</label>
                <input type="text" name="questions[${questionCount}][option_a]" id="option_a${questionCount}" required>
            </div>

            <div class="form-group">
                <label for="option_b${questionCount}">Option B:</label>
                <input type="text" name="questions[${questionCount}][option_b]" id="option_b${questionCount}" required>
            </div>

            <div class="form-group">
                <label for="option_c${questionCount}">Option C:</label>
                <input type="text" name="questions[${questionCount}][option_c]" id="option_c${questionCount}" required>
            </div>

            <div class="form-group">
                <label for="option_d${questionCount}">Option D:</label>
                <input type="text" name="questions[${questionCount}][option_d]" id="option_d${questionCount}" required>
            </div>

            <div class="form-group">
                <label for="correct_option${questionCount}">Correct Option (A, B, C, D):</label>
                <input type="text" name="questions[${questionCount}][correct_option]" id="correct_option${questionCount}" required>
            </div>
        `;
        questionFormContainer.appendChild(newQuestionWrapper);
        questionCount++; // Increment the question count
    });
</script>
</body>
</html>

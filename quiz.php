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

// Fetch questions for the selected category
$category_id = isset($_POST['category']) ? (int)$_POST['category'] : 0;
$sql = "SELECT * FROM questions WHERE category_id = $category_id";
$result = $conn->query($sql);
$questions = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
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
    <title>Quiz Game</title>
    <link rel="stylesheet" href="style.css">
    <script>
        let currentQuestionIndex = 0;
        let questions = <?php echo json_encode($questions); ?>;
        let score = 0;
        let timer;
        const timeLimit = 10; // Time limit in seconds

        function loadQuestion() {
            if (currentQuestionIndex < questions.length) {
                document.getElementById("question").textContent = questions[currentQuestionIndex].question_text;
                document.getElementById("options").innerHTML = `
                    <label><input type="radio" name="answer" value="A" required> ${questions[currentQuestionIndex].option_a}</label>
                    <label><input type="radio" name="answer" value="B"> ${questions[currentQuestionIndex].option_b}</label>
                    <label><input type="radio" name="answer" value="C"> ${questions[currentQuestionIndex].option_c}</label>
                    <label><input type="radio" name="answer" value="D"> ${questions[currentQuestionIndex].option_d}</label>
                `;

                resetTimer();
                startTimer();
            } else {
                endQuiz();
            }
        }

        function submitAnswer() {
            const selectedOption = document.querySelector('input[name="answer"]:checked');
            if (selectedOption) {
                const answer = selectedOption.value;
                if (answer === questions[currentQuestionIndex].correct_option) {
                    score++;
                }
            }
            moveToNextQuestion();
        }

        function moveToNextQuestion() {
            currentQuestionIndex++;
            clearInterval(timer);
            loadQuestion();
        }

        function startTimer() {
            let timeLeft = timeLimit;
            document.getElementById("timer").textContent = `Time left: ${timeLeft} seconds`;

            timer = setInterval(() => {
                timeLeft--;
                document.getElementById("timer").textContent = `Time left: ${timeLeft} seconds`;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    moveToNextQuestion();
                }
            }, 1000);
        }

        function resetTimer() {
            clearInterval(timer);
        }

        function endQuiz() {
            document.getElementById("quiz-section").classList.add("hidden");
            document.getElementById("result-section").classList.remove("hidden");
            document.getElementById("final-score").textContent = `Your final score: ${score} / ${questions.length}`;
        }

        document.addEventListener("DOMContentLoaded", function () {
            const quizForm = document.getElementById("quiz-form");
            quizForm.addEventListener("submit", function (e) {
                e.preventDefault();
                submitAnswer();
            });

            loadQuestion();
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .quiz-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .hidden {
            display: none;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #000;
        }

        .timer-section, .score-section{
            margin-bottom: 15px;
            color: #000; 
        }

    #question {
        color: #000; /* Ensure questions are displayed in black */
        font-weight: bold; /* Optional: Makes questions stand out */
        margin-bottom: 20px;
    }
    #result-section{
        color: #000; /* Ensure questions are displayed in black */
        font-weight: bold;
    } 
    </style>
</head>
<body>
<div class="quiz-container">
    <h1>Quiz Game</h1>
    <div id="quiz-section">
        <div class="score-section">
            <p id="score">Score: 0</p>
        </div>
        <div class="timer-section">
            <p id="timer">Time left: 10 seconds</p>
        </div>
        <div class="question-section">
            <p id="question"></p>
            <form id="quiz-form">
                <div id="options"></div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <div id="result-section" class="hidden">
        <h2>Quiz Completed!</h2>
        <p id="final-score"></p>
        <button onclick="location.href='index.php';">Go to Home</button>
        <button onclick="location.href='login.php';">Logout</button>
    </div>
</div>
</body>
</html>

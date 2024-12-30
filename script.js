const categories = {
    "Computer Science": [
        { question: "What does HTML stand for?", options: ["Hyper Text Markup Language", "High Text Machine Language", "Hyper Tool Multi Language", "Hyper Text Multiple Language"], answer: 0 },
        { question: "Which language is primarily used for Android app development?", options: ["Python", "Java", "C++", "Swift"], answer: 1 },
        { question: "What is the time complexity of binary search?", options: ["O(n)", "O(log n)", "O(n^2)", "O(1)"], answer: 1 },
    ],
    "Math": [
        { question: "What is the square root of 64?", options: ["6", "7", "8", "9"], answer: 2 },
        { question: "What is the value of Pi (approximately)?", options: ["2.14", "3.14", "3.41", "4.13"], answer: 1 },
    ],
    "Sports": [
        { question: "How many players are there in a football (soccer) team?", options: ["9", "10", "11", "12"], answer: 2 },
        { question: "Which country has won the most FIFA World Cups?", options: ["Germany", "Italy", "Brazil", "Argentina"], answer: 2 },
    ],
};

let selectedCategory = null;
let currentQuestionIndex = 0;
let score = 0;
let timer = null;

document.getElementById("category-form").addEventListener("submit", (e) => {
    e.preventDefault();
    selectedCategory = document.querySelector('input[name="category"]:checked').value;
    document.getElementById("category-selection").classList.add("hidden");
    document.getElementById("quiz-section").classList.remove("hidden");
    loadQuestion();
    startTimer();
});

document.getElementById("quiz-form").addEventListener("submit", (e) => {
    e.preventDefault();
    clearInterval(timer);

    const selectedAnswer = document.querySelector('input[name="option"]:checked');
    if (selectedAnswer && parseInt(selectedAnswer.value) === categories[selectedCategory][currentQuestionIndex].answer) {
        score++;
    }

    currentQuestionIndex++;
    if (currentQuestionIndex < categories[selectedCategory].length) {
        loadQuestion();
        startTimer();
    } else {
        showResults();
    }
});

document.getElementById("restart-button").addEventListener("click", () => {
    location.reload();
});

function loadQuestion() {
    const questionData = categories[selectedCategory][currentQuestionIndex];
    document.getElementById("question").textContent = questionData.question;

    const optionsContainer = document.getElementById("options");
    optionsContainer.innerHTML = "";
    questionData.options.forEach((option, index) => {
        const label = document.createElement("label");
        label.innerHTML = `<input type="radio" name="option" value="${index}" required> ${option}`;
        optionsContainer.appendChild(label);
    });

    document.getElementById("score").textContent = `Score: ${score}`;
}

function startTimer() {
    let timeLeft = 10;
    const timerElement = document.getElementById("timer");
    timerElement.textContent = `Time left: ${timeLeft} seconds`;

    timer = setInterval(() => {
        timeLeft--;
        timerElement.textContent = `Time left: ${timeLeft} seconds`;

        if (timeLeft <= 0) {
            clearInterval(timer);
            document.getElementById("quiz-form").submit();
        }
    }, 1000);
}

function showResults() {
    document.getElementById("quiz-section").classList.add("hidden");
    document.getElementById("result-section").classList.remove("hidden");
    document.getElementById("final-score").textContent = `Your final score is ${score}`;
}

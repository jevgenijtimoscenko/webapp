// Function to reload the navigation bar with updated lessons
function loadNavigationBar() {
    
    if (window.location.pathname.includes('quiz.html')) {
    
        return;
    }


    fetch('getLessons.php')
        .then(response => response.json())
        .then(lessons => {
            const navBar = document.querySelector('.navbar ul');
            navBar.innerHTML = ''; // Clear the navigation bar

            lessons.forEach((lesson, index) => {
                let cleanedTitle = lesson.title.replace(/^Lesson \d+:\s*/, ''); // Clean title
                let numberedTitle = `${index + 1}. ${cleanedTitle}`; // Add numbering

                const lessonItem = document.createElement('li');
                lessonItem.innerHTML = `<a href="#" class="lesson-link" data-lesson-id="${lesson.id}">${numberedTitle}</a>`;
                navBar.appendChild(lessonItem);
            });

            // Add Quiz and Admin Panel links as regular hyperlinks (for all pages except quiz.html)
            const quizLink = document.createElement('li');
            quizLink.innerHTML = `<a href="quiz.html" id="loadQuizButton">Quiz</a>`; // Add Quiz button
            navBar.appendChild(quizLink);

            const adminLink = document.createElement('li');
            adminLink.innerHTML = `<a href="admin.html">Admin Panel</a>`;
            navBar.appendChild(adminLink);

            // Re-attach event listeners to lesson links after updating the navbar
            attachLessonEventListeners();
        })
        .catch(error => {
            console.error('Error loading navigation bar:', error);
        });
}

// Function to attach event listeners to lesson links
function attachLessonEventListeners() {
    document.querySelectorAll('.lesson-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default behavior for lesson links
            const lessonId = this.getAttribute('data-lesson-id');
            
            // Fetch lesson content via AJAX
            fetch(`getLessons.php?lesson_id=${lessonId}`)
                .then(response => response.json())
                .then(data => {
                    const lessonContentDetails = document.getElementById('lesson-content-details');
                    
                    if (data.error) {
                        lessonContentDetails.innerHTML = `<p>${data.error}</p>`;
                    } else {
                        lessonContentDetails.innerHTML = `
                            <h3>${data.title}</h3>
                            <p>${data.content}</p>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching lesson content:', error);
                    document.getElementById('lesson-content-details').innerHTML = '<p>Failed to load lesson content.</p>';
                });
        });
    });
}

// Load the quiz only on quiz.html
if (window.location.pathname.includes('quiz.html')) {

    document.addEventListener('DOMContentLoaded', function() {
        loadQuiz();
    });

    // Function to load the quiz
    function loadQuiz() {
        fetch('getQuiz.php')
            .then(response => response.json())
            .then(quizQuestions => {
                const quizContainer = document.getElementById('quiz-container');
                quizContainer.innerHTML = ''; // Clear any previous quiz content

                quizQuestions.forEach((quiz, index) => {
                    quizContainer.innerHTML += `
                        <div class="question">
                            <p>${index + 1}. ${quiz.question}</p>
                            <label><input type="radio" name="q${quiz.id}" value="a"> ${quiz.option_a}</label>
                            <label><input type="radio" name="q${quiz.id}" value="b"> ${quiz.option_b}</label>
                            <label><input type="radio" name="q${quiz.id}" value="c"> ${quiz.option_c}</label>
                            <label><input type="radio" name="q${quiz.id}" value="d"> ${quiz.option_d}</label>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error('Error fetching quiz questions:', error);
            });
    }

    // Handle quiz submission
    document.getElementById('quiz-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const userAnswers = {};

        formData.forEach((value, key) => {
            userAnswers[key] = value;
        });

        // For demonstration, assume correct answers are hard-coded
        const correctAnswers = {
            q1: 'b',
            q2: 'a',
            q3: 'a',
            q4: 'b',
            q5: 'a',
            q6: 'b'
        };

        let correct = 0;
        let total = Object.keys(correctAnswers).length;

        Object.keys(correctAnswers).forEach(key => {
            if (userAnswers[key] === correctAnswers[key]) {
                correct++;
            }
        });

        document.getElementById('quiz-result').innerText = `You got ${correct} out of ${total} correct!`;
    });
}

// Initial load of lessons and navigation bar
document.addEventListener('DOMContentLoaded', function() {
    loadNavigationBar(); // Load the navigation bar with all lessons and Quiz link
});

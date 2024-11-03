<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// db connection
include 'db_connect.php';

// Fetch all quiz questions from the database
$sql = "SELECT id, question, option_a, option_b, option_c, option_d FROM quiz";
$result = $conn->query($sql);

$quizQuestions = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $quizQuestions[] = $row;
    }
    // Return the quiz questions as JSON
    echo json_encode($quizQuestions);
} else {
    echo json_encode(["error" => "No quiz questions found"]);
}

$conn->close();
?>

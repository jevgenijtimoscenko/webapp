<?php
// Enable error reporting 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// connection
include 'db_connect.php';

// Check if a lesson ID is provided
if (isset($_GET['lesson_id'])) {
    $lessonId = (int)$_GET['lesson_id']; 

    // Prepare SQL to fetch the specific lesson by ID
    $sql = "SELECT title, content FROM lessons WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['error' => 'Error preparing the SQL statement: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $lessonId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a lesson was found
    if ($result->num_rows > 0) {
        $lesson = $result->fetch_assoc();
        echo json_encode($lesson);  // Return the lesson content as JSON
    } else {
        echo json_encode(['error' => 'Lesson not found']);
    }
    $stmt->close();
} else {
    // If no lesson ID is provided, fetch all lessons
    $sql = "SELECT id, title FROM lessons";   
    $result = $conn->query($sql);

    $lessons = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lessons[] = $row;  // Add each lesson to the array
        }
        echo json_encode($lessons);  // Return the lessons as JSON
    } else {
        echo json_encode(['error' => 'No lessons found']);
    }
}

$conn->close();
?>

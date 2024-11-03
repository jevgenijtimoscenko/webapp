<?php
// Include the database connection
include 'db_connect.php';

// Get the lesson ID from the request
if (isset($_GET['id'])) {
    $lessonId = (int)$_GET['id'];

    // Prepare SQL query to fetch the lesson by ID
    $sql = "SELECT id, title, content FROM lessons WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['error' => 'Error preparing SQL statement: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $lessonId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $lesson = $result->fetch_assoc();
        echo json_encode($lesson); // Return the lesson data as JSON
    } else {
        echo json_encode(['error' => 'Lesson not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Lesson ID is missing']);
}

$conn->close();

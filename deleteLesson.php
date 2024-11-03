<?php

// Db connection
include 'db_connect.php';

// Check if the lesson ID is provided via JSON
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $lessonId = (int) $data['id'];

    // Prepare the SQL statement to delete the lesson
    $sql = "DELETE FROM lessons WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo json_encode(["message" => "Error preparing the SQL statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $lessonId);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Lesson deleted successfully!"]);
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["message" => "Invalid input data."]);
}

$conn->close();
?>

<?php
// DB connection
include 'db_connect.php';

// Get data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['title']) && isset($data['content'])) {
    $lessonId = (int) $data['id'];
    $title = $conn->real_escape_string($data['title']);
    $content = $conn->real_escape_string($data['content']);

    // Prepare and execute the update query
    $sql = "UPDATE lessons SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(["message" => "Error preparing the SQL statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssi", $title, $content, $lessonId);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Lesson updated successfully!"]);
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["message" => "Invalid input data."]);
}

$conn->close();

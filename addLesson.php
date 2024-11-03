<?php


// The database connection
include 'db_connect.php';

// Check if data is submitted as JSON
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['title']) && isset($data['content'])) {
    $title = $conn->real_escape_string($data['title']);
    $content = $conn->real_escape_string($data['content']);

    // Insert the new lesson into the database
    $sql = "INSERT INTO lessons (title, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo json_encode(["message" => "Error preparing the SQL statement: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        echo json_encode(["message" => "New lesson added successfully!"]);
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["message" => "Invalid input data."]);
}

$conn->close();
?>

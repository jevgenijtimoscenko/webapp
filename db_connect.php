<?php
$servername = "localhost";
$username = "s7fl6f5_webapp";  
$password = "Psymed1cus";
$database = "s7fl6f5_lesson_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

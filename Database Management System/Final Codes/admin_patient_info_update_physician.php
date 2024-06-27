<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get patient name and physician input from POST request
$name = $_POST['name'];
$physicianInput = $_POST['physician'];

// Update physician in the database
$sql = "UPDATE dc3_table_medical_info SET physician = '$physicianInput' WHERE name = '$name'";
$result = $conn->query($sql);

$sql = "INSERT INTO dc3_table_medical_info2 (name, details, due_date, physician) SELECT name, details, due_date, physician FROM dc3_table_medical_info";
$conn->query($sql);

$sql = "DELETE FROM dc3_table_medical_info WHERE name = '$name'";
$conn->query($sql);

// Close database connection
$conn->close();
?>
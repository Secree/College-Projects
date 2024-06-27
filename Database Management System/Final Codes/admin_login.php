<?php
// Start session
session_start();

// Database connection

// Create connection
$conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize_input($conn, $data) {
    // Remove extra whitespaces
    $data = trim($data);    
    // Escape special characters to prevent SQL injection
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $id = sanitize_input($conn, $_POST["adminID"]);
    $password = sanitize_input($conn, $_POST["adminPassword"]);

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM dc2_table WHERE id = ? AND password = ?");
    $stmt->bind_param("ss", $id, $password);

    // Execute SQL statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if there's a match
    if ($result->num_rows > 0) {
        // Successful login
        $_SESSION['success_message'] = "Login successful!";
        $_SESSION['id'] = $id;
        header("Location: admin_page.php");
        exit();
        // Store the logged-in user's ID in a session variable
        $_SESSION['id'] = $id;
        header("Location: admin_page.php"); // Redirect to admin page
        exit();
    } else {
        $_SESSION['error_message'] = "Login failed. Please check your email and password.";
        header("Location: home_page.php");
        exit();
    }

    // Close prepared statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>

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
function sanitize_input($data) {
    $data = trim($data);    
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $email = sanitize_input($_POST["email"]);
    $password = sanitize_input($_POST["password"]);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM dc1_table WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);

    // Execute SQL statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if there's a match
    if ($result->num_rows > 0) {
        // Successful login
        // After successful login
        $_SESSION['success_message'] = "Login successful!";
        $_SESSION['email'] = $email;
        header("Location: patient_page.php");
        exit();

        // Store the logged-in user's email in a session variable
        $_SESSION['email'] = $email;
        header("Location: patient_page.php"); // Redirect to patient page
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

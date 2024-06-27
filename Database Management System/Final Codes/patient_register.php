<?php

session_start(); // Start the session if not already started

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and fetch data from the registration form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    // Check if email already exists
    $check_email_query = "SELECT * FROM dc1_table WHERE email = '$email'";
    $result = $conn->query($check_email_query);

    if ($result->num_rows > 0) {
        // Email already exists, redirect with error message
        $_SESSION['error_message'] = "The email provided is already registered.";
        header("Location: home_page.php");
        exit();
    } else {
        // Email is unique, proceed with registration
        // SQL query to insert data into the table
        $sql = "INSERT INTO dc1_table (email, password, name) VALUES ('$email', '$password', '$name')";

        if ($conn->query($sql) === TRUE) {
            // Insert name into dc3_table_patient_profile
            $sql2 = "INSERT INTO dc3_table_patient_profile (name) VALUES ('$name')";
            if ($conn->query($sql2) === TRUE) {
                $_SESSION['success_message'] = "Registration successful!";
                header("Location: home_page.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Error: " . $sql2 . "<br>" . $conn->error;
                header("Location: home_page.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Error: " . $sql . "<br>" . $conn->error;
            header("Location: home_page.php");
            exit();
        }
    }

    $conn->close();
} else {
    // If the form is not submitted, redirect back to the registration page
    header("Location: home_page.php");
    exit();
}
?>

<?php
session_start();

// Handle sign-out logic
if (isset($_GET['signout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page after signing out
    header("Location: home_page.php"); // Change "home_page.php" to your desired page
    exit;
}

// Handle status update logic
if (isset($_POST['update_status'])) {
    $status = $_POST['update_status'];
    $due_date = $_POST['due_date'];
    $name = $_POST['name'];
    $details = $_POST['details'];
    $total_price = $_POST['total_price']; // Capture the total price from the form

    // Create connection
    $conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement with a WHERE clause to update only the specific row based on the due date
    $sql = "UPDATE dc3_table_billing SET status=? WHERE due_date=?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $status, $due_date);

    if ($stmt->execute()) {
        echo "Status updated successfully";
        // If status is 'Paid', move the billing to past table
        if ($status == 'Paid') {
            $sql_move_to_past = "INSERT INTO dc3_table_billing_past SELECT * FROM dc3_table_billing WHERE due_date=?";
            $stmt_move_to_past = $conn->prepare($sql_move_to_past);
            $stmt_move_to_past->bind_param("s", $due_date);
            if ($stmt_move_to_past->execute()) {
                // Insert details into medical info table
                $sql_insert_medical_info = "INSERT INTO dc3_table_medical_info (name, due_date, details) VALUES (?, ?, ?)";
                $stmt_insert_medical_info = $conn->prepare($sql_insert_medical_info);
                $stmt_insert_medical_info->bind_param("sss", $name, $due_date, $details); // Updated binding params
                $stmt_insert_medical_info->execute();
                
                // Delete the billing from current table
                $sql_delete_current = "DELETE FROM dc3_table_billing WHERE due_date=?";
                $stmt_delete_current = $conn->prepare($sql_delete_current);
                $stmt_delete_current->bind_param("s", $due_date);
                $stmt_delete_current->execute();
            } else {
                echo "Error moving billing to past table: " . $conn->error;
            }
        } 
        // If status is 'Overdue', move the billing to past table and overdue table
        else if ($status == 'Overdue') {
            $sql_move_to_past = "INSERT INTO dc3_table_billing_past (name, due_date, details, total_price, status) SELECT name, due_date, details, total_price, 'Overdue' FROM dc3_table_billing WHERE due_date=?";
            $stmt_move_to_past = $conn->prepare($sql_move_to_past);
            $stmt_move_to_past->bind_param("s", $due_date);
            if ($stmt_move_to_past->execute()) {
                // Delete the billing from current table
                $sql_delete_current = "DELETE FROM dc3_table_billing WHERE due_date=?";
                $stmt_delete_current = $conn->prepare($sql_delete_current);
                $stmt_delete_current->bind_param("s", $due_date);
                $stmt_delete_current->execute();
            } else {
                echo "Error moving billing to past table: " . $conn->error;
            }
        }
        
        // Refresh the page after status update
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    } else {
        echo "Error updating status: " . $conn->error;
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
}

// Create connection
$conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve billings from the database
$sql = "SELECT * FROM dc3_table_billing";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Billing</title>
    <link rel="stylesheet" href="admin_patient_billing_style.css">
</head>

<body>

<nav class="navbar">
  <ul>
    <li><img src="medicare2.png" alt=""></li>
    <li><a href="?signout=1">Sign Out</a></li>
  </ul>
</nav>

<!-- Add a back button -->
<div class="back-button">
  <a href="admin_page.php">Back</a>
</div>

<div class="background">

<!-- Display the billing list -->
<div class="billing-list">
    <h2>Billing List</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo "<div class='billing-info'>";
                echo "<h3>Name:</h3>";
                echo "<p>" . $row["name"] . "</p>";
                echo "</div>";
                echo "<div class='billing-info'>";
                echo "<h3>Details:</h3>";
                echo "<p>" . $row["details"] . "</p>";
                echo "</div>";
                echo "<div class='billing-info'>";
                echo "<h3>Total Price:</h3>";
                echo "<p>" . $row["total_price"] . "</p>";
                echo "</div>";
                echo "<div class='billing-info'>";
                echo "<h3>Due Date:</h3>";
                echo "<p>" . $row["due_date"] . "</p>";
                echo "</div>";
                echo "<div class='billing-info'>";
                echo "<h3>Status:</h3>";
                echo "<p>" . $row["status"] . "</p>";
                echo "</div>";
                echo "<form method='post' action=''>";
                // Add hidden input fields to hold data
                echo "<input type='hidden' name='name' value='" . $row["name"] . "'>";
                echo "<input type='hidden' name='details' value='" . $row["details"] . "'>";
                echo "<input type='hidden' name='due_date' value='" . $row["due_date"] . "'>";
                echo "<input type='hidden' name='total_price' value='" . $row["total_price"] . "'>"; // Add total price field
                echo "<button type='submit' name='update_status' value='Paid'>Paid</button>";
                echo "<button type='submit' name='update_status' value='Overdue'>Overdue</button>";
                echo "</form>";
                echo "</li>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </ul>
</div>

</div>

</body>

</html>

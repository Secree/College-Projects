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

// Delete appointment logic
if (isset($_POST['delete_appointment'])) {
    $appointment_id = $_POST['appointment_id'];

    // Create connection
    $conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: ". $conn->connect_error);
    }

    $sql = "DELETE FROM dc3_table_appointment_current WHERE id=$appointment_id";

    if ($conn->query($sql) === TRUE) {
        echo "Appointment deleted successfully";
        // Refresh the page after deletion
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    } else {
        echo "Error deleting appointment: " . $conn->error;
    }

    $conn->close();
}

// Create connection
$conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// SQL query to retrieve appointments from the database
$sql = "SELECT * FROM dc3_table_appointment_current";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: ". $conn->error);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Appointments</title>
    <link rel="stylesheet" href="admin_appointment_style.css">
</head>

<body>

    <nav class="navbar">
  <ul>
    <li><img src="medicare2.png" alt=""></li>
    <li><a href="?signout=1">Sign Out</a></li> <!-- Added sign-out button -->
  </ul>
</nav>

<!-- Add a back button -->
<div class="back-button">
  <a href="admin_page.php">Back</a>
</div>

    <!-- Navbar and back button remain the same -->

    <div class="background">

    <!-- Display the appointment list -->
    <div class="appointment-list">
        <h2>Appointment List</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<li>";
                    echo "<div class='appointment-info'>";
                    echo "<h3>Name:</h3>";
                    echo "<p>" . $row["name"] . "</p>";
                    echo "</div>";
                    echo "<div class='appointment-info'>";
                    echo "<h3>Address:</h3>";
                    echo "<p>" . $row["address"] . "</p>";
                    echo "</div>";
                    echo "<div class='appointment-info'>";
                    echo "<h3>Appointment for:</h3>";
                    echo "<p>" . $row["appointment_for"] . "</p>";
                    echo "</div>";
                    echo "<div class='appointment-info'>";
                    echo "<h3>Date:</h3>";
                    echo "<p>" . $row["date"] . "</p>";
                    echo"</div>";
                    echo "<div class='appointment-info'>";
                    echo "<h3>Time:</h3>";
                    echo "<p>" . $row["time"] . "</p>";
                    echo "</div>";
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='appointment_id' value='" . $row["id"] . "'>";
                    echo "<button type='submit' name='delete_appointment'>Done</button>";
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

    <?php
    if (isset($_POST['delete_appointment'])) {
        $appointment_id = $_POST['appointment_id'];

        // Create connection
        $conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: ". $conn->connect_error);
        }

        $sql = "DELETE FROM dc3_table_appointment_current WHERE id=$appointment_id";

        if ($conn->query($sql) === TRUE) {
            echo "Appointment deleted successfully";
            // Refresh the page after deletion
            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        } else {
            echo "Error deleting appointment: " . $conn->error;
        }

        $conn->close();
    }
    ?>

</body>

</html>
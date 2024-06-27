<?php
// Start the session
session_start();

// Establish a database connection
$conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate the session
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page
    exit;
}

// Fetch user's name from the database
$stmt = $conn->prepare("SELECT name FROM dc1_table WHERE email = ?");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['name'];
} else {
    $user_name = "User"; // Default to "User" if the name couldn't be fetched
}

// Handle sign-out logic
if (isset($_GET['signout'])) {
    signOut();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $user_name; // Use the session user's name directly
    $address = $_POST['address'];
    $appointment_for = $_POST['appointment_for'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Check if the user already has an appointment
    $stmt = $conn->prepare("SELECT COUNT(*) as num_appointments FROM dc3_table_appointment_current WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $num_appointments = $row['num_appointments'];

    if ($num_appointments < 1) {
        // Generate a random ID for the appointment
        $random_id = rand(1, 1000);

        // Check if the random ID already exists in the database
        do {
            $stmt = $conn->prepare("SELECT COUNT(*) as num_appointments FROM dc3_table_appointment_current WHERE id = ?");
            $stmt->bind_param("i", $random_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $num_appointments = $row['num_appointments'];
            if ($num_appointments > 0) {
                $random_id = rand(1, 1000);
            }
        } while ($num_appointments > 0);

        // Insert appointment data into the database
        $stmt = $conn->prepare("INSERT INTO dc3_table_appointment_current (id, name, address, appointment_for, date, time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $random_id, $name, $address, $appointment_for, $date, $time);
        if ($stmt->execute()) {
            $_SESSION['booking_message'] = "Booked successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "You have reached the maximum number of appointments.";
    }
}

// Check for booking message session variable
if (isset($_SESSION['booking_message'])) {
    $message = $_SESSION['booking_message'];
    unset($_SESSION['booking_message']);
}

// Close the database connection
$conn->close();

// Sign-out function
function signOut() {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page after signing out
    header("Location: home_page.php"); // Change "login.php" to your desired page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Page</title>
  <link rel="stylesheet" href="patient_page_styless.css">
</head>
<body>

<nav class="navbar">
  <ul>
    <li><img src="medicare2.png" alt=""></li>
    <li><a href="?signout=1">Sign Out</a></li>
  </ul>
</nav>

<div class="background">
<div class="welcome-message">
    <?php echo htmlspecialchars($user_name); ?>
</div>

  <div id="background">
    <a href="patient_billing.php" class="image-link"><img src="pati1.png" alt=""></a>
    <a href="patient_info.php" class="image-link"><img src="profile.png" alt=""></a>

    <div class="appointment-form">
      <h2>Book an Appointment</h2>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
        </div>
        <div class="form-group">
          <label for="address">Address:</label>
          <input type="text" id="address" name="address" required>
        </div>
        <div class="form-group">
          <label for="appointment_for">Appointment for:</label>
          <textarea id="appointment_for" name="appointment_for" rows="4" required></textarea>
        </div>
        <div class="form-group">
          <label for="date">Date:</label>
          <input type="date" id="date" name="date" required>
        </div>
        <div class="form-group">
          <label for="time">Time:</label>
          <input type="time" id="time" name="time" required>
        </div>
        <button type="submit">Submit</button>
      </form>
    </div>
  </div>
</div>

<div id="floating-message"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const floatingMessage = document.getElementById("floating-message");

  <?php if (!empty($message)) : ?>
    floatingMessage.textContent = "<?php echo htmlspecialchars($message); ?>";
    floatingMessage.style.display = "block";
    setTimeout(() => {
      floatingMessage.style.display = "none";
    }, 3000);
  <?php endif; ?>
});
</script>

</body>
</html>

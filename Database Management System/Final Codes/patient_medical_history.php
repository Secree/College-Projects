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



// Prepare the SQL statement with a placeholder for the user name
$stmt2 = $conn->prepare("SELECT birth, gender, contact_details, emergency_contact, past_conditions, allergies, family_history FROM dc3_table_patient_profile WHERE name = ?");

// Bind the user name parameter to the prepared statement
$stmt2->bind_param("s", $user_name);

// Execute the statement
$stmt2->execute();

// Get the result
$result = $stmt2->get_result();

// Check if any rows were returned
if ($result && $result->num_rows > 0) {
    // Fetch the associative array of the first row
    $row = $result->fetch_assoc();

    // Assign the fetched values to the variables
    $birth = $row['birth'];
    $gender = $row['gender'];
    $contact_details = $row['contact_details'];
    $emergency_contact = $row['emergency_contact'];
    $past_conditions = $row['past_conditions'];
    $allergies = $row['allergies'];
    $family_history = $row['family_history'];

} else {
    // Default to "User" if no rows were found
    $birth = "Not Assigned";
    $gender = "Not Assigned";
    $contact_details = "Not Assigned";
    $emergency_contact = "Not Assigned";
    $past_conditions = "Not Assigned";
    $allergies = "Not Assigned";
    $family_history = "Not Assigned";
}

// Close the statement
$stmt2->close();

$stmt3 = $conn->prepare("SELECT details, due_date, physician FROM dc3_table_medical_info2 WHERE name = ?");

// Bind the user name parameter to the prepared statement
$stmt3->bind_param("s", $user_name);

// Execute the statement
$stmt3->execute();

// Get the result
$result = $stmt3->get_result();

// Check if any rows were returned
if ($result && $result->num_rows > 0) {
    // Fetch the associative array of the first row
    $row = $result->fetch_assoc();

    // Assign the fetched values to the variables
    $details = $row['details'];
    $due_date = $row['due_date'];
    $physician = $row['physician'];
    

} else {
    $details = "Not yet selected";
    $due_date = "Not yet selected";
    $physician = "Not yet selected";
}

// Close the statement
$stmt3->close();

$invalidDateMessage = '';


$due_dates = array();
$query = "SELECT due_date FROM dc3_table_medical_info2";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $due_dates[] = $row['due_date'];
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["date"])) {
    $selectedDate = $_GET["date"];

    // Retrieve details, due date, and physician based on selected date
    $query = "SELECT details, due_date, physician FROM dc3_table_medical_info2 WHERE due_date = '$selectedDate'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $details = $row["details"];
        $due_date = $row["due_date"];
        $physician = $row["physician"];
    } else {
        $details = "";
        $due_date = "";
        $physician = "";
    }
} else {
    $details = "";
    $due_date = "";
    $physician = "";
    $selectedDate = "";
}




if (isset($_GET['signout'])) {
  signOut();
}

  // Close the connection
  $conn->close();


// Sign-out function
function signOut() {
    // Unset all session variables
    $_SESSION = array();

    // Destroy thesession
    session_destroy();

    // Redirect to the login page or any other page after signing out
    header("Location: home_page.php"); // Change "login.php" to your desired page
    exit;
}



// HTML template
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="patient_medical_history_style.css">
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
  <a href="patient_page.php">Back</a>
</div>

<div class="background">
<div class="medical-history">
        <h2>Personal Information</h2>
            <div class="medical-item">
                <div class="medical-label">Full Name:</div>
                <div class="medical-value">
                  <?php echo $user_name ?>
                </div>
            </div>
            <div class="medical-item">
                <div class="medical-label">Date of Birth:</div>
                <div class="medical-value">
                  <?php echo $birth ?>
                </div>
            </div>
            <div class="medical-item">
                <div class="medical-label">Gender:</div>
                <div class="medical-value">
                <?php echo $gender ?>
                </div>
            </div>

            <div class="medical-item">
                <div class="medical-label">Contact Details:</div>
                <div class="medical-value">
                <?php echo $contact_details ?>
                </div>
            </div>

            <div class="medical-item">
                <div class="medical-label">Emergency Contact:</div>
                <div class="medical-value">
                <?php echo $emergency_contact ?>
                </div>
            </div>

            <div class="medical-item">
                <div class="medical-label">Past Conditions:</div>
                <div class="medical-value">
                <?php echo $past_conditions ?>
                </div>
            </div>

            <div class="medical-item">
                <div class="medical-label">Allergies:</div>
                <div class="medical-value">
                <?php echo $allergies ?>
                </div>
            </div>

            <div class="medical-item">
                <div class="medical-label">Family History:</div>
                <div class="medical-value">
                <?php echo $family_history ?>
                </div>
            </div>    
    </div>
    <!-- HTML template -->
    <div class="medical-history2">
    <h2>Medical Information</h2>
    <div class="medical-item">
        <div class="medical-label">Details:</div>
        <div class="medical-value">
          <?php echo $details ?>
        </div>
    </div>
    <div class="medical-item">
        <div class="medical-label">Due Date:</div>
        <div class="medical-value">
          <?php echo $due_date ?>
        </div>
    </div>

    <div class="medical-item">
        <div class="medical-label">Prescribing Physician:</div>
        <div class="medical-value">
          <?php echo $physician ?>
        </div>
    </div>

    <div class="medical-item">
        <div class="medical-label">Select a date: </div>
        <div class="medical-value">
            <form action="" method="get">
                <select name="date" id="date">
                    <option value="">All dates</option>
                    <?php foreach ($due_dates as $due_date):?>
                        <option value="<?= htmlspecialchars($due_date)?>" <?= ($selectedDate == $due_date)? 'selected' : ''?>>
                            <?= htmlspecialchars($due_date)?>
                        </option>
                    <?php endforeach;?>
                </select>
                <button type="submit">Go</button>
                <?php if ($invalidDateMessage):?>
                    <span style="color: red;"><?= htmlspecialchars($invalidDateMessage)?></span>
                <?php endif;?>
            </form>
        </div>
    </div>
</div>
</div>
</body>
</html>

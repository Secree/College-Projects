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

$conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Get the patient email from the session
$email = $_SESSION['email'];

// Get the patient name from the dc1_table based on the email
$sql = "SELECT name FROM dc1_table WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
} else {
    echo "Error: Unable to retrieve patient name";
    exit;
}

// Get the patient's profile information from the database
$sql = "SELECT * FROM dc3_table_patient_profile WHERE name='$name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $birth = $row['birth'];
    $gender = $row['gender'];
    $contact_details = $row['contact_details'];
    $emergency_contact = $row['emergency_contact'];
    $past_conditions = $row['past_conditions'];
    $allergies = $row['allergies'];
    $family_history = $row['family_history'];
} else {
    echo "Error: Unable to retrieve profile information";
    exit;
}

// Update the profile information if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure that the user is updating their own profile
    $submitted_name = $_POST['name'];
    if ($submitted_name !== $name) {
        echo "Error: You are not authorized to update this profile.";
        exit;
    }

    $birth = $_POST['birth'];
    $gender = $_POST['gender'];
    $contact_details = $_POST['contact_details'];
    $emergency_contact = $_POST['emergency_contact'];
    $past_conditions = $_POST['past_conditions'];
    $allergies = $_POST['allergies'];
    $family_history = $_POST['family_history'];

    $sql = "UPDATE dc3_table_patient_profile SET birth='$birth', gender='$gender', contact_details='$contact_details', emergency_contact='$emergency_contact', past_conditions='$past_conditions', allergies='$allergies', family_history='$family_history' WHERE name='$name'";

    if ($conn->query($sql) === TRUE) {
        echo "Profile updated successfully";
        // Redirect back to the current page after updating
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: ". $sql. "<br>". $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Profile</title>
  <link rel="stylesheet" href="patient_info_style.css">
</head>
<body>

<nav class="navbar">
    <ul>
        <li><img src="medicare2.png" alt="Medicare Logo"></li>
        <li><a href="?signout=1">Sign Out</a></li>
    </ul>
</nav>

<div class="back-button">
    <a href="patient_page.php">Back</a>
</div>

<div class="background">
    <div class="profile-form">
        <h2>Profile Details</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name;?>" readonly>
            <br>
            <label for="birth">Birth Date:</label>
            <input type="date" id="birth" name="birth" value="<?php echo $birth;?>">
            <br>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="male" <?php if ($gender == 'male') echo 'selected';?>>Male</option>
                <option value="female" <?php if ($gender =='female') echo 'selected';?>>Female</option>
                <option value="other" <?php if ($gender == 'other') echo 'selected';?>>Other</option>
            </select>
            <br>
            <label for="contact_details">Contact Details:</label>
            <input type="text" id="contact_details" name="contact_details" value="<?php echo $contact_details;?>">
            <br>
            <label for="emergency_contact">Emergency Contact:</label>
            <input type="text" id="emergency_contact" name="emergency_contact" value="<?php echo $emergency_contact;?>">
            <br>
            <label for="past_conditions">Past Conditions:</label>
            <textarea id="past_conditions" name="past_conditions"><?php echo $past_conditions;?></textarea>
            <br>
            <label for="allergies">Allergies:</label>
            <textarea id="allergies" name="allergies"><?php echo $allergies;?></textarea>
            <br>
            <label for="family_history">Family History:</label>
            <textarea id="family_history" name="family_history"><?php echo $family_history;?></textarea>
            <br>
            <input type="submit" value="Update Profile">
        </form>
    </div>
</div>

</body>
</html>

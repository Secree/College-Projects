<?php
session_start(); // Start the session if not already started

// Check if the user is already logged in as a patient
if(isset($_SESSION['email'])) {
    // Redirect to the patient page
    header("Location: patient_page.php");
    exit();
}

// Check if the user is already logged in as an admin
elseif(isset($_SESSION['id'])) {
    // Redirect to the admin page
    header("Location: admin_page.php");
    exit(); // Exit to prevent further execution of code
}

// Check if there's a registration or login success message stored in the session
if (isset($_SESSION['success_message'])) {
    $message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Remove the message from the session
}

// Check if there's an error message stored in the session
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Remove the message from the session
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="home_page_stylesss.css">
</head>
<body>

<div id="floating-message" <?php if(isset($message)) echo 'style="display: block;"'; ?>><?php if(isset($message)) echo $message; ?></div>

<div id="error-message" <?php if(isset($error_message)) echo 'style="display: block;"'; ?>><?php if(isset($error_message)) echo $error_message; ?></div>

  <nav class="navbar">
    <ul>
      <li><img src="medicare2.png" alt=""></li>
      <li><a href="#"></a></li>
      <li><a href="#"></a></li>
      <li><a href="#"></a></li>
      <li><a href="#"></a></li>
      <li><a></a></li>
      <li><a></a></li>
      <li><a></a></li>
      <li><a></a></li>
    </ul>
  </nav>

  <div class="login-container">
    <div class="role-buttons">
      <button class="role-btn role-transform" onclick="showPatientOptions()">Patient</button>
      <button class="role-btn role-transform" onclick="showAdminOptions()">Admin</button>
    </div>
    <!-- Trust image -->
  </div>

<div class="background">  

  <img src="trust1.png" alt="Trust" class="trust-image">

<!-- All between  the box -->
<div id="field">
  <div id="maintext">
    <div id="maintext-first"> 
        <span id="ms0img">
          <img src="log4.png" alt="Trust" class="trust-image">
        </span>
        <h6>Health and medical<br>theme</h6>
        <p>Exploring the intricate interplay<br>between biology, technology,<br>and human resilience, the<br>health and medical theme delves<br>into the frontline advancements<br>shaping the future of well-being</p>  
    </div>

    <div id="maintext-second">
      <span id="ms1img">
        <img src="log4.png" alt="Trust" class="trust-image">
      </span>
      <h6>World Class Doctors</h6>
      <p>"World-class doctors possess<br>unparalleled expertise, empathy,<br>and dedication, revolutionizing<br>healthcare"</p>
      <div id="ms2under">
        <span id="ms2img">
          <img src="log3.png" alt="Trust" class="trust-image">
        </span>
        <h6>Caring Doctors</h6>
        <p>We will treat you with<br>utmost care that no other<br>hospital will</p>
      </div>
      
    </div>
    <div id="maintext-third">
      <span id="ms3img">
        <img src="log2.png" alt="Trust" class="trust-image">
      </span>
      <h6>Medical Counseling</h6>
      <p>We will help you diagnose your<br>sickness with capable doctors</p>
        <div id="ms3under">
          <span id="ms4img">
            <img src="log1.png" alt="Trust" class="trust-image">
          </span>
          <h6>Emergency Services</h6>
          <p>Accomodate bad conditions<br>with fast to act staffs</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Patient Login Modal -->
<div id="patientModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Login as Patient</h2>
    <button class="login-btn" onclick="showPatientLogin()">Login</button>
    <button class="register-btn" onclick="showPatientRegistration()">Register</button>
  </div>
</div>

<!-- Patient register Modal -->
<div id="patientReg" class="modal">
  <div class="reg-content">
    
    <h2>Registration</h2>
    <form name = "register" method="post" action="patient_register.php">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required><br><br>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required><br><br>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required><br><br>
      <button class="register-btn" onclick="registerPatient()">Register</button>
    </form>
  </div>
</div>

<!-- Patient Login Modal -->
<div id="patientLog" class="modal">
  <div class="login-content">
    
    <h2>Login</h2>
    <form name="login" method="post" action="patient_login.php">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required><br><br>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required><br><br>
      <button class="login-btn" onclick="loginPatient()">Login</button>
    </form>
  </div>
</div>

<!-- Admin Login Modal -->
<div id="adminModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Login as Admin</h2>
    <button class="login-btn" onclick="showAdminLogin()">Login</button>
  </div>
</div>

<!-- Admin Login Modal -->
<div id="adminLog" class="modal">
  <div class="login-content">
    <h2>Admin Login</h2>
    <form name="adminLogin" method="post" action="admin_login.php">
      <label for="adminID">ID Number</label>
      <input type="text" id="adminID" name="adminID" required><br><br>
      <label for="adminPassword">Password</label>
      <input type="password" id="adminPassword" name="adminPassword" required><br><br>
      <button class="login-btn" onclick="loginAdmin()">Login</button>
    </form>
  </div>
</div>

<!-- baba ng medical services-->

<script>
function showPatientOptions() {
  document.getElementById("patientModal").style.display = "block";
}

function showPatientRegistration() {
  document.getElementById("patientReg").style.display = "block";
}

function showPatientLogin() {
  document.getElementById("patientLog").style.display = "block";
}

function showAdminOptions() {
  document.getElementById("adminModal").style.display = "block";
}

function showAdminLogin() {
  document.getElementById("adminLog").style.display = "block";
}

function closeModal() {
  document.getElementById("patientModal").style.display = "none";
  document.getElementById("adminModal").style.display = "none";
  document.getElementById("patientReg").style.display = "none";
  document.getElementById("patientLog").style.display = "none";
  document.getElementById("adminLog").style.display = "none";

  document.getElementById("name").value = "";
  document.getElementById("age").value = "";
  document.getElementById("sex").value = "";
  document.getElementById("phone").value = "";
  document.getElementById("email").value = "";
  document.getElementById("password").value = "";
}

window.onclick = function(event) {
  if (event.target == document.getElementById("patientModal") ||
      event.target == document.getElementById("adminModal") ||
      event.target == document.getElementById("adminLog") || 
      event.target == document.getElementById("patientReg") ||
      event.target == document.getElementById("patientLog")) {
    closeModal();
  }
}

document.addEventListener('DOMContentLoaded', function() {
    var floatingMessage = document.getElementById('floating-message');
    var errorMessage = document.getElementById('error-message');

    // Check if the message elements are present
    if (floatingMessage && errorMessage) {
        // Check if the messages are initially shown
        if (floatingMessage.style.display === 'block' || errorMessage.style.display === 'block') {
            // Set a timeout to hide the messages after 3 seconds
            setTimeout(function() {
                floatingMessage.style.display = 'none';
                errorMessage.style.display = 'none';
            }, 3000);
        }
    }
});
</script>

</body>
</html>

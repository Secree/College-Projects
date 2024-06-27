<?php
  // Start session
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

  // Database connection
  $conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Initialize the message variable
  $message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $name = $conn->real_escape_string($_POST['patient']);
    $detail = $conn->real_escape_string($_POST['detail']);
    $total_price = $conn->real_escape_string($_POST['total_price']);
    $due_date = $conn->real_escape_string($_POST['due_date']);
    $status = $conn->real_escape_string($_POST['status']);

    // Check if a billing record already exists for the given patient name
    $sql_check = "SELECT * FROM dc3_table_billing WHERE name = '$name'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        $message = "Billing record already exists for $name";
    } else {
        // Prepare an insert statement
        $sql = "INSERT INTO dc3_table_billing (name, details, total_price, due_date, status) VALUES ('$name', '$detail', '$total_price', '$due_date', '$status')";

        if ($conn->query($sql) === TRUE) {
            $message = "Saved successfully!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

  // Close database connection
  $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
  <link rel="stylesheet" href="admin_styles.css">
</head>
<body>

<nav class="navbar">
  <ul>
    <li><img src="medicare2.png" alt=""></li>
    <li><a href="?signout=1">Sign Out</a></li> <!-- Added sign-out button -->
  </ul>
</nav>

<div class="background">

  <div class="image-container">
    <a href="admin_appointment.php"><img src="pati7.png" alt="Picture 1"></a>
    <a href="admin_patient_info.php" id="tago"><img src="pati5.png" alt="Picture 2"></a>
    <a href="admin_patient_billing.php"><img src="pati8.png" alt="Picture 3"></a>
  </div>

<!-- Display patient list in a scrollable table -->
<div class="scrollable-table">
  <table>
    <thead>
      <tr>
        <th colspan="3" class="patients-label">Patients List</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Database connection
      $conn = new mysqli('localhost', 'root', 'root', 'dc1', 3308);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Fetch patient data from the database
      $sql = "SELECT name FROM dc1_table";
      $result = $conn->query($sql);

      // Check if there are any patients in the result set
      if ($result->num_rows > 0) {
          // Output data of each row
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row["name"] . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='3'>No patients found</td></tr>";
      }

      // Close database connection
      $conn->close();
      ?>
    </tbody>
  </table>
</div>
</div>

<!-- Add/Edit Billing Section -->
<div class="billing-section">
  <h2>Billing Information</h2>
  <form method="post">
    <div class="billing-details">

      <label for="detail">Name:</label>
      <input type="text" id="patient" name="patient" placeholder="patient" required>

      <label for="detail">Detail:</label>
      <input type="text" id="detail" name="detail" placeholder="Billing Detail" required>

      <label for="total_price">Total Price:</label>
      <input type="number" id="total_price" name="total_price" placeholder="Total Price" required>

      <label for="due_date">Due Date:</label>
      <input type="date" id="due_date" name="due_date" required>

      <label for="status">Status:</label>
      <select id="status" name="status" required>
        <option value="Pending">Pending</option>
      </select>
    </div>
    <button id="save_button" type="submit">Save</button>
  </form>
  <?php if (!empty($message)) : ?>
    <div class="success-message"><?php echo $message; ?></div>
  <?php endif; ?>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const rows = document.querySelectorAll(".scrollable-table tbody tr");

    rows.forEach(row => {
      row.addEventListener("click", function() {
        const name = this.querySelector("td:first-child").innerText;
        // Populate patient name in the billing section
        document.getElementById("patient").value = name;
        fetchBillingDetails(name);
      });
    });

    function fetchBillingDetails(name) {
      // AJAX call to fetch billing details for the selected name
      // Replace 'fetch_billing_details.php' with your PHP script to fetch billing details
      fetch(`fetch_billing_details.php?name=${name}`)
        .then(response => response.json())
        .then(data => {
          // Populate billing section with fetched data
          document.getElementById("detail").value = data.details;
          document.getElementById("total_price").value = data.total_price;
          document.getElementById("due_date").value = data.due_date;
          document.getElementById("status").value = data.status;
        })
        .catch(error => console.error("Error fetching billing details:", error));
    }
  });
</script>


</body>
</html>

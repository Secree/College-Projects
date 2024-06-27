<?php
// Start session
session_start();

// Handle sign-out logic
if (isset($_GET['signout']) && $_GET['signout'] == 1) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page after signing out
    header("Location: home_page.php"); // Change "home_page.php" to your desired page
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page
    exit;
}

// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=dc1;port=3308';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Fetch user's name from the database
$stmt = $pdo->prepare('SELECT name FROM dc1_table WHERE email = ?');
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $user_name = $user['name'];
} else {
    $user_name = "User"; // Default to "User" if the name couldn't be fetched
}

// Function to fetch billing details based on the name
function fetchBillingDetails($pdo, $user_name) {
    $query = 'SELECT name, details, total_price, due_date, status FROM dc3_table_billing WHERE name = ?';
    $params = [$user_name];

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch current billing details
$billingDetails = fetchBillingDetails($pdo, $user_name);

// Fetch billing history
function fetchBillingHistory($pdo, $user_name, $selectedDate = null) {
    $query = 'SELECT name, details, total_price, due_date, status FROM dc3_table_billing_past WHERE name = ?';
    $params = [$user_name];

    if ($selectedDate) {
        $query .= ' AND due_date = ?';
        $params[] = $selectedDate;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch unique due dates from the database
$stmt = $pdo->prepare('SELECT DISTINCT due_date FROM dc3_table_billing_past WHERE name = ?');
$stmt->execute([$user_name]);
$dueDates = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Handle date selection
$selectedDate = null;
$invalidDateMessage = '';
if (isset($_GET['date']) && $_GET['date'] !== '') {
    $selectedDate = $_GET['date'];
    $dateObj = DateTime::createFromFormat('Y-m-d', $selectedDate);
    if ($dateObj && $dateObj->format('Y-m-d') === $selectedDate) {
        $billingHistory = fetchBillingHistory($pdo, $user_name, $selectedDate);
    } else {
        $invalidDateMessage = "Invalid date format. Please use Y-m-d.";
        $billingHistory = fetchBillingHistory($pdo, $user_name);
    }
} else {
    $billingHistory = fetchBillingHistory($pdo, $user_name);
}

// Function to display billing details
function displayBillingDetails($billingDetails) {
    ?>
    <div class="billing-details">
        <h2>Current Billings</h2>
        <?php foreach ($billingDetails as $details) { ?>
            <div class="billing-item">
                <div class="billing-label">Name:</div>
                <div class="billing-value"><?= htmlspecialchars($details['name']) ?></div>
            </div>
            <div class="billing-item">
                <div class="billing-label">Details:</div>
                <div class="billing-value"><?= htmlspecialchars($details['details']) ?></div>
            </div>
            <div class="billing-item">
                <div class="billing-label">Total Price:</div>
                <div class="billing-value">$<?= htmlspecialchars($details['total_price']) ?></div>
            </div>
            <div class="billing-item">
                <div class="billing-label">Due Date:</div>
                <div class="billing-value"><?= htmlspecialchars($details['due_date']) ?></div>
            </div>
            <div class="billing-item">
                <div class="billing-label">Status:</div>
                <div class="billing-value"><?= htmlspecialchars($details['status']) ?></div>
            </div>
        <?php } ?>
    </div>
    <?php
}

// Function to display billing history
function displayBillingHistory($billingHistory) {
    ?>
    <div class="billing-history">
        <h2>Billing History</h2>
        <?php foreach ($billingHistory as $history) { ?>
            <div class="billing-item">
                <div class="billing-label">Name:</div>
                <div class="billing-value"><?= htmlspecialchars($history['name']) ?></div>
            </div>
            <div class="billing-item">
                <div class="billing-label">Details:</div>
                <div class="billing-value"><?= htmlspecialchars($history['details']) ?></div>
            </div>
            <div class="billing-item">
                <div class="billing-label">Total Price:</div>
                <div class="billing-value">$<?= htmlspecialchars($history['total_price']) ?></div>
            </div>
            <div class="billing-item">
                <div class="billing-label">Due Date:</div>
                <div class="billing-value"><?= htmlspecialchars($history['due_date']) ?></div>
            </div>
            <div class="billing-item">
                <div class="billing-label">Status:</div>
                <div class="billing-value"><?= htmlspecialchars($history['status']) ?></div>
            </div>
        <?php } ?>
    </div>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Billing</title>
    <link rel="stylesheet" href="patient_billing_styles.css">
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
<?php
// Display billing details
displayBillingDetails($billingDetails);

// Display billing history
displayBillingHistory($billingHistory);
?>

<!-- Add a form for selecting a specific date -->
<div class="select-date">
    <form action="" method="get">
        <label for="date">Select a date:</label>
        <select name="date" id="date">
            <option value="">All dates</option>
            <?php foreach ($dueDates as $dueDate): ?>
                <option value="<?= htmlspecialchars($dueDate) ?>" <?= ($selectedDate == $dueDate) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dueDate) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Go</button>
    </form>
    <?php if ($invalidDateMessage): ?>
        <p style="color: red;"><?= htmlspecialchars($invalidDateMessage) ?></p>
    <?php endif; ?>
</div>
</div>
</body>
</html>

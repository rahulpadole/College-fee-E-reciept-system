<?php
session_start();
include '../includes/session.php';

// Ensure only users can access this page
if ($_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/user_dashboard.css"> <!-- Link to User CSS -->
</head>
<body>
    <h1>Welcome, User <?php echo $_SESSION['username']; ?></h1>

    <div class="user-panel">
         <!-- New Option for Fee Payment -->
         <h2>Actions</h2>
         <a href="../fee_payment.php" class="btn-fee">Pay College Fee</a><!-- Link to fee payment page -->
        <hr>
        <!-- User-specific content goes here -->
        <a href="../logout.php">Logout</a>
    </div>
</body>
</html>

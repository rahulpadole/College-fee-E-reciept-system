<?php
session_start();
include '../includes/db.php'; // Include database connection
include '../includes/session.php'; // Include session management

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch all unapproved users (where is_approved = 0)
$sql = "SELECT * FROM users WHERE is_approved = 0 AND role = 'user'";
$result = $conn->query($sql);

// Handle Approve User
if (isset($_POST['approve'])) {
    $user_id = $_POST['user_id'];
    $sql = "UPDATE users SET is_approved = 1 WHERE id = $user_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "User approved successfully!";
    } else {
        echo "Error approving user: " . $conn->error;
    }
    
    // Redirect to the approval page
    header("Location: admin_approval.php");
    exit();
}

// Handle Reject User
if (isset($_POST['reject'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM users WHERE id = $user_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "User rejected and record deleted.";
    } else {
        echo "Error rejecting user: " . $conn->error;
    }
    
    // Redirect to the approval page
    header("Location: admin_approval.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending User Approvals</title>
    <link rel="stylesheet" href="../css/admin_approval.css"> <!-- Link to your CSS -->
</head>
<body>
    <h2>Pending User Approvals</h2>

    <?php
    // Check if there are any pending users
    if ($result->num_rows > 0) {
        // Display each unapproved user
        while ($row = $result->fetch_assoc()) {
            echo "<div class='user-approval'>";
            echo "<p><strong>First Name:</strong> " . $row['first_name'] . "</p>";
            echo "<p><strong>Student ID:</strong> " . $row['student_id'] . "</p>";
            echo "<p><strong>Username:</strong> " . $row['username'] . "</p>";

            // Form for approving or rejecting the user
            echo "<form method='POST' action='admin_approval.php'>";
            echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
            echo "<input type='submit' name='approve' value='Approve' class='btn-approve'>";
            echo "<input type='submit' name='reject' value='Reject' class='btn-reject'>";
            echo "</form>";
            echo "</div><hr>"; // Divider between users
        }
    } else {
        echo "<p>No users are awaiting approval.</p>";
    }
    ?>

    <!-- Link back to Admin Dashboard -->
    <a href="admin_dashboard.php" class="back-link">Back to Admin Dashboard</a>
</body>
</html>

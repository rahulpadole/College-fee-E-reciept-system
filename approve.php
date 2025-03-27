<?php
session_start();

// Correct path to db.php
include 'includes/db.php';  // Update the path to the correct location

// Ensure only admin can approve or reject users
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['approve'])) {
    // Approve the user
    $user_id = $_POST['user_id'];
    $sql = "UPDATE users SET is_approved = 1 WHERE id = $user_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "User approved successfully!";
    } else {
        echo "Error approving user: " . $conn->error;
    }
    header("Location: dashboard/admin_approval.php"); // Redirect back to approval dashboard
    exit();
}

if (isset($_POST['reject'])) {
    // Reject the user (delete from the database)
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM users WHERE id = $user_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "User rejected and removed!";
    } else {
        echo "Error rejecting user: " . $conn->error;
    }
    header("Location: dashboard/admin_approval.php"); // Redirect back to approval dashboard
    exit();
}
?>

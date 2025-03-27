<?php
// Check if a session is already active before calling session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect them to the login page
    header("Location: ../index.php");
    exit();
}
?>

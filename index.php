<?php
session_start();
include 'includes/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists and is approved
    $sql = "SELECT * FROM users WHERE username='$username' AND is_approved=1"; // Ensure user is approved
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: dashboard/admin_dashboard.php");
            } else {
                header("Location: dashboard/user_dashboard.php");
            }
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Your account is not approved yet!";
    }
}
?>


<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Link to Login CSS -->
</head>
<body>
    <form method="POST" action="index.php">
        <h2>Login</h2>
        <label>Student ID/Mobile no. for Admin</label>
        <input type="text" name="username" required>
        <br>
        <label>Password</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="login" value="Login">
        
        <!-- Registration Link Inside Form -->
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>

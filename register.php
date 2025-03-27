<?php
session_start();
include 'includes/db.php'; // Include database connection

if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];  // Capture First Name
    $role = $_POST['role']; // Role (admin or user)

    // If role is admin, use mobile number as username and auto-approve admin
    if ($role == 'admin') {
        $username = $_POST['mobile_number']; // Mobile number as username
        $mobile_number = $_POST['mobile_number'];
        $student_id = NULL; // Not needed for admin
        $is_approved = 1;   // Admin is automatically approved
    } else {
        // If role is user, use student ID as username and await approval
        $username = $_POST['student_id']; // Student ID as username
        $student_id = $_POST['student_id'];
        $mobile_number = NULL; // Not needed for user
        $is_approved = 0;   // Users need approval
    }
    
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Secure password

    // Check if the username (mobile number or student ID) already exists
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<p>This username is already taken!</p>";
    } else {
        // Insert user into the database with approval status based on role
        $sql = "INSERT INTO users (first_name, username, password, role, mobile_number, student_id, is_approved)
                VALUES ('$first_name', '$username', '$hashed_password', '$role', '$mobile_number', '$student_id', '$is_approved')";

        if ($conn->query($sql) === TRUE) {
            if ($role == 'admin') {
                echo "Registration successful! You can log in as Admin.";
            } else {
                echo "Registration successful! Awaiting admin approval.";
            }
            header("Location: index.php"); // Redirect to login page after registration
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>


<!-- HTML Registration Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css"> <!-- Link to CSS -->
</head>
<body>
    <form method="POST" action="register.php">
        <h2>Register</h2>

        <!-- First Name Field -->
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" required>
        <br>

        <!-- Role Selection (Admin/User) -->
        <label>Role</label>
        <select id="role" name="role" onchange="toggleFields()" required>
            <option value="user">Student</option>
            <option value="admin">Admin</option>
        </select>
        <br>

        <!-- For Admin (Mobile Number) -->
        <div id="adminFields" style="display:none;">
            <label>Mobile Number (Admin)</label>
            <input type="text" name="mobile_number" id="mobile_number">
            <br>
        </div>

        <!-- For User (Student ID) -->
        <div id="userFields">
            <label>Student ID (User)</label>
            <input type="text" name="student_id" id="student_id" required>
            <br>
        </div>

        <label>Password</label>
        <input type="password" name="password" required>
        <br>

        <input type="submit" name="register" value="Register">

        <!-- Link to go back to the login page -->
        <div class="back-to-login">
            <p>Already have an account? <a href="index.php">Back to Login</a></p>
        </div>
    </form>

    <!-- JavaScript to Toggle Fields -->
    <script>
        function toggleFields() {
            var role = document.getElementById('role').value;
            if (role === 'admin') {
                document.getElementById('adminFields').style.display = 'block';
                document.getElementById('userFields').style.display = 'none';
                document.getElementById('student_id').removeAttribute('required');
            } else {
                document.getElementById('adminFields').style.display = 'none';
                document.getElementById('userFields').style.display = 'block';
                document.getElementById('student_id').setAttribute('required', 'required');
            }
        }
    </script>
</body>
</html>


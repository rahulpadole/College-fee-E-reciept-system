<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'login_system';

// Create a new connection to the MySQL database
$conn = new mysqli($host, $user, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

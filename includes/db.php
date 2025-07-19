<?php

$host = "sql209.infinityfree.com"; // Found in control panel (e.g., sql204.epizy.com)
$dbname = "if0_39508444_login_system"; // Your database name
$username = "if0_39508444"; // Your InfinityFree DB username
$password = "Rahul1968"; // Set in control panel

$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>

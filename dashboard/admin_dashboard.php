<?php
session_start();
include '../includes/db.php'; // Include the database connection
include '../includes/session.php'; // Include the session management

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Check if a search has been made
$searchQuery = "";
if (isset($_GET['search'])) {
    $student_id = $_GET['student_id'];
    // SQL query to search for payment records based on student ID
    $searchQuery = "WHERE student_id = '$student_id'";
}

// Fetch payment details from the database (with optional search)
$sql = "SELECT * FROM payments $searchQuery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_dashboard.css"> <!-- Link to the Admin CSS -->
</head>
<body>
    <h1>Welcome, Admin <?php echo $_SESSION['username']; ?></h1>

    <!-- Admin Panel Options -->
    <div class="admin-panel">
        <a href="admin_approval.php" class="btn-link">Manage User Approvals</a>
        <br><br>
        <a href="../download_payments.php" class="btn-link">Download Payments as Excel</a>
        <br><br>
        <a href="../logout.php" class="btn-link">Logout</a>
    </div>

    <!-- Search Bar for Payment Details by Student ID -->
    <div class="search-section">
        <form action="admin_dashboard.php" method="GET">
            <label for="student_id">Search Payment by Student ID:</label>
            <input type="text" name="student_id" id="student_id" placeholder="Enter Student ID" required>
            <button type="submit" name="search" class="btn-link">Search</button>
        </form>
    </div>

    <!-- Payment Details Section -->
    <h2>Payment Details</h2>

    <?php
    // Check if there are any payment records
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Payment ID</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Branch</th>
                    <th>Year</th>
                    <th>Particulars of Fee</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                </tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['payment_id'] . "</td>
                    <td>" . $row['student_id'] . "</td>
                    <td>" . $row['name_of_student'] . "</td>
                    <td>" . $row['branch'] . "</td>
                    <td>" . $row['year'] . "</td>
                    <td>" . $row['particulars_fee'] . "</td>
                    <td>" . $row['amount'] . "</td>
                    <td>" . $row['payment_method'] . "</td>
                    <td>" . $row['payment_date'] . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        if (isset($_GET['search'])) {
            echo "<p>No payment records found for Student ID: " . $student_id . "</p>";
        } else {
            echo "<p>No payment records found.</p>";
        }
    }
    ?>

    
</body>
</html>

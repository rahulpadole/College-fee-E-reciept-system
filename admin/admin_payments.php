<?php
session_start();
include '../includes/db.php'; // Include database connection
include '../includes/session.php'; // Include session management

// Ensure only admins can access this page
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch all payments from the database
$sql = "SELECT * FROM payments ORDER BY payment_date DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Payment Details</title>
    <link rel="stylesheet" href="../css/admin_payments.css"> <!-- Link to your CSS for styling -->
</head>
<body>
    <h2>All Payment Details</h2>

    <?php
    // Check if there are any payments
    if ($result->num_rows > 0) {
        // Display payments in a table
        echo "<table border='1'>
                <tr>
                    <th>Payment ID</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>Year</th>
                    <th>Particulars of Fee</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                </tr>";
        
        // Loop through each payment and display in a table row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['payment_id']}</td>
                    <td>{$row['student_id']}</td>
                    <td>{$row['name_of_student']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['branch']}</td>
                    <td>{$row['year']}</td>
                    <td>{$row['particulars_fee']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['payment_method']}</td>
                    <td>{$row['payment_date']}</td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No payments found.</p>";
    }
    ?>

    <!-- Back to Admin Dashboard -->
    <a href="admin_dashboard.php" class="btn-back">Back to Dashboard</a>
</body>
</html>

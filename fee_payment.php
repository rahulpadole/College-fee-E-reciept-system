<?php
session_start();
include 'includes/db.php'; // Include database connection
include 'includes/session.php'; // Include session management

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // This loads the installed PHPMailer

// Ensure only users can access this page
if ($_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['pay_fee'])) {
    // Capture form details
    $payment_id = $_POST['payment_id'];
    $student_id = $_SESSION['username'];  // Use the session to get the Student ID
    $name_of_student = $_POST['name_of_student'];
    $email = $_POST['email'];  // Student email to send payment details
    $branch = $_POST['branch'];
    $year = $_POST['year'];
    $particulars_fee = $_POST['particulars_fee'];
    $amount = $_POST['amount'];
    $payment_method = "Online";  // Payment method fixed as "Online"
    $payment_date = date('Y-m-d H:i:s');  // Capture current date and time

    // Insert payment details into the database
    $sql = "INSERT INTO payments (payment_id, student_id, name_of_student, email, branch, year, particulars_fee, amount, payment_method, payment_date)
            VALUES ('$payment_id', '$student_id', '$name_of_student', '$email', '$branch', '$year', '$particulars_fee', '$amount', '$payment_method', '$payment_date')";

    if ($conn->query($sql) === TRUE) {
        // Create a new PHPMailer object
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use Gmail's SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'pavanbohsale@gmail.com'; // Your Gmail address
            $mail->Password = 'bvanjuqsgewvhqcn'; // Your Gmail password (or app password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('pavanbohsale@gmail.com', 'College Accounts Team'); // Sender email and name
            $mail->addAddress($email, $name_of_student);  // Student email and name

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->Subject = 'Payment Confirmation';
            $mail->Body = "Dear $name_of_student,<br><br>" .
                "Thank you for your payment. Below are the details of your payment:<br><br>" .
                "<strong>Payment ID:</strong> $payment_id<br>" .
                "<strong>Student ID:</strong> $student_id<br>" .
                "<strong>Branch:</strong> $branch<br>" .
                "<strong>Year:</strong> $year<br>" .
                "<strong>Particulars of Fee:</strong> $particulars_fee<br>" .
                "<strong>Amount Paid:</strong> $amount<br>" .
                "<strong>Payment Method:</strong> $payment_method<br>" .
                "<strong>Payment Date:</strong> $payment_date<br><br>" .
                "If you have any questions, feel free to contact us.<br><br>" .
                "Best regards,<br>" .
                "College Accounts Team";

            // Send the email
            $mail->send();
            echo "Payment successful! A receipt has been sent to your email.";
        } catch (Exception $e) {
            echo "Payment successful, but we couldn't send the email receipt. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Payment</title>
    <link rel="stylesheet" href="css/fee_payment.css"> <!-- Link to the CSS file for styling -->
</head>
<body>
    <h1>College Fee Payment</h1>

    <form method="POST" action="fee_payment.php">
        <!-- Payment ID -->
        <label for="payment_id">Payment ID</label>
        <input type="text" name="payment_id" id="payment_id" placeholder="Enter Transaction ID in it." required>
        <br>

        <!-- Student ID (Auto-filled from session) -->
        <label for="student_id">Student ID</label>
        <input type="text" name="student_id" id="student_id" value="<?php echo $_SESSION['username']; ?>" readonly>
        <br>

        <!-- Name of Student -->
        <label for="name_of_student">Name of Student</label>
        <input type="text" name="name_of_student" id="name_of_student" placeholder="Enter Name" required>
        <br>

        <!-- Email -->
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Enter mail" required>
        <br><br>

        <!-- Branch -->
        <label for="branch">Branch</label>
        <select name="branch" id="branch" required>
            <option value="CSE">CSE (Computer Science Engineering)</option>
            <option value="E&C">E&C (Electronics & Communication)</option>
            <option value="EE">EE (Electrical Engineering)</option>
            <option value="ME">ME (Mechanical Engineering)</option>
            <option value="CE">CE (Civil Engineering)</option>
            <option value="Other">Other</option>
        </select>
        <br>

        <!-- Year -->
        <label for="year">Year</label>
        <select name="year" id="year" required>
            <option value="1">1st Year</option>
            <option value="2">2nd Year</option>
            <option value="3">3rd Year</option>
            <option value="4">4th Year</option>
        </select>
        <br>

        <!-- Particulars of Fee -->
        <label for="particulars_fee">Particulars of Fee</label>
        <select name="particulars_fee" id="particulars_fee" required>
            <option value="Tuition fees">Tuition Fees</option>
            <option value="Development fees">Development Fees</option>
            <option value="Caution money Deposit">Caution Money Deposit</option>
            <option value="Late fees">Late Fees</option>
            <option value="Prospectus fees">Prospectus Fees</option>
            <option value="Uniform fee">Uniform Fee</option>
            <option value="Other fees">Other Fees</option>
        </select>
        <br>

        <!-- Amount -->
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" placeholder="Payment amount" required>
        <br>

        <!-- Payment Method (Fixed as "Online") -->
        <label for="payment_method">Payment Method</label>
        <input type="text" name="payment_method" id="payment_method" value="Online/Offline" readonly>
        <br>

        <!-- Submit Payment -->
        <input type="submit" name="pay_fee" value="Pay Now">
    </form>

    <!-- Back to Dashboard Button -->
    <div class="back-to-dashboard">
        <a href="dashboard/user_dashboard.php" class="btn-back">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
// Include database connection
include 'db.php';

// Start or resume a session to access $_SESSION superglobal
session_start();

// Retrieve email and booking ID from session
$email = $_SESSION['email'];

// Fetch booking details for the logged-in user
$sql = "SELECT * FROM booking WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    // Error handling for prepared statement creation failure
    echo "Error: " . $conn->error;
    exit();
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    // Error handling for query execution failure
    echo "Error: " . $stmt->error;
    exit();
}

// Check if any booking found
if ($result->num_rows == 0) {
    // No booking found for the user
    header("Location: mybooking.php");
    echo "No booking found for the user.";
    exit();
}

// Assuming there's only one booking per user for simplicity
$booking = $result->fetch_assoc();
$bookingId = $booking['id'];
$price = $booking['price'];

// Insert payment details into payment_details table
$paymentStatus = 'done';
$paymentDate = date('Y-m-d'); // Current date
$username = $_SESSION['email'];

$insertSql = "INSERT INTO payment_details (booking_id, username, payment_status, payment_date, price) VALUES (?, ?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
if (!$insertStmt) {
    // Error handling for prepared statement creation failure
    echo "Error: " . $conn->error;
    exit();
}
$insertStmt->bind_param("issss", $bookingId, $username, $paymentStatus, $paymentDate, $price);
$insertStmt->execute();

if ($insertStmt->affected_rows > 0) {
    // Payment details inserted successfully
    // You can perform further actions like updating booking status, sending email notifications, etc.
    echo "Payment details inserted successfully.";
} else {
    // Error handling for insertion failure
    echo "Error: Failed to insert payment details.";
}

// Close prepared statements
$stmt->close();
$insertStmt->close();

// Close database connection
$conn->close();

// Redirect back to mybookings page
header("Location: mybooking.php");
exit();
?>

<?php
include 'db.php';

// Start or resume a session to access $_SESSION superglobal
session_start();

// Retrieve email from session
$email = $_SESSION['email'];

// Disable foreign key checks
$sqlDisableFK = "SET foreign_key_checks = 0";
$conn->query($sqlDisableFK);

// Perform the deletion for bookings
$sqlBooking = "DELETE FROM booking WHERE email = ?";
$stmtBooking = $conn->prepare($sqlBooking);
$stmtBooking->bind_param("s", $email);

// Execute the statement
$success = $stmtBooking->execute();

// Enable foreign key checks
$sqlEnableFK = "SET foreign_key_checks = 1";
$conn->query($sqlEnableFK);

// Close the statement and the database connection
$stmtBooking->close();
$conn->close();

// Check if the deletion was successful
if ($success) {
    echo "Bookings canceled successfully.";
} else {
    header("Location: mybooking.php");
}
?>

<?php
session_start();

include 'db.php';

// Get username and password from POST request
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query using prepared statement to check if the provided username and password match any record in the database
$stmt = $conn->prepare("SELECT email FROM users WHERE email=? AND password=?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Login successful, store email in session
    $_SESSION["email"] = $username;
    echo "success";
} else {
    // Login failed
    echo "failure";
}

// Close prepared statement
$stmt->close();

// Close database connection
$conn->close();
?>
<?php
include 'db.php';

// Get form data from POST request
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$phoneNumber = $_POST['phoneNumber'];
$email = $_POST['email'];
$password = $_POST['password'];

// Check if the email is already taken
$sql_check_email = "SELECT * FROM users WHERE email='$email'";
$result_check_email = $conn->query($sql_check_email);

if ($result_check_email->num_rows > 0) {
    // Email already exists, signup failed
    echo "failure_username_exists";
} else {
    // Email doesn't exist, proceed with signup

    // SQL query to insert new user into the database
    $sql_signup = "INSERT INTO users (first_name, last_name, phone_number, email, password) VALUES ('$firstName', '$lastName', '$phoneNumber', '$email', '$password')";

    if ($conn->query($sql_signup) === TRUE) {
        // Signup successful
        echo "success";
    } else {
        // Signup failed
        echo "failure";
    }
}

// Close database connection
$conn->close();
?>

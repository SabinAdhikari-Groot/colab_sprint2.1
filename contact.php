<?php
// Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include 'db.php';
        // Prepare and bind the INSERT statement
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        // Set parameters and execute
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $stmt->execute();

        // Close statement and connection
        $stmt->close();
        $conn->close();

        header("Location: ncontactus.html");
         echo "<p>Thank you for contacting us!</p>";
    }
?>
<?php
include 'db.php';

// Start or resume a session to access $_SESSION superglobal
session_start();

// Retrieve email, first name, and last name from session
$email = $_SESSION['email'];

$sql = "SELECT first_name, last_name, phone_number FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the result row
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $phone_number = $row['phone_number'];

    // Use the retrieved first name and last name
    echo "First Name: $first_name<br>";
    echo "Last Name: $last_name<br>";
} else {
    echo "No user found with this email.";
}

// Retrieve other booking details from the form submission
$arrivalDate = $_POST['arrival-date'];
$departureDate = $_POST['departure-date'];
$gender = $_POST['gender'];
$roomNumber = $_POST['room-number'];
$guestNumber = $_POST['num-guests'];
$bedPreference = $_POST['bed-preference'];
$foodPreference = $_POST['food'];
$price = 0;

// Convert date format to 'YYYY-MM-DD' (MySQL format)
$arrivalDateFormatted = date('Y-m-d', strtotime($arrivalDate));
$departureDateFormatted = date('Y-m-d', strtotime($departureDate));

// Check if arrival date is before today
if (strtotime($arrivalDateFormatted) < strtotime(date('Y-m-d'))) {
    echo "Arrival date cannot be before today.";
    exit();
}

// Check if departure date is at least 2 days from arrival date
if (strtotime($departureDateFormatted) <= strtotime($arrivalDateFormatted) + (2 * 24 * 60 * 60)) {
    echo "Departure date must be at least 2 days after arrival date.";
    exit();
}

// Calculate number of days of stay
$numberOfDays = (strtotime($departureDateFormatted) - strtotime($arrivalDateFormatted)) / (24 * 60 * 60);

// Calculate price based on bed preference and number of days
if ($bedPreference === "single") {
    $price += (5 * $numberOfDays);
} elseif ($bedPreference === "double") {
    $price += (8 * $numberOfDays);
}

// Calculate price based on food preference and number of guests
if ($foodPreference === "yes") {
    $price += ($guestNumber * 7 * $numberOfDays); // $7 per guest per day
}

// Prepare and execute SQL query to check number of guests in the room
$sql_check_guests = "SELECT SUM(guest_number) AS total_guests FROM booking WHERE room_number = ?";
$stmt_check_guests = $conn->prepare($sql_check_guests);
$stmt_check_guests->bind_param("i", $roomNumber);
$stmt_check_guests->execute();
$result_check_guests = $stmt_check_guests->get_result();
$row_check_guests = $result_check_guests->fetch_assoc();
$totalGuests = $row_check_guests['total_guests'];

// Check if total guests exceed the room capacity (12 guests)
if (($totalGuests + $guestNumber) > 12) {
    echo "Booking cannot be done in this room. Room capacity exceeded.";
    exit();
}

// Prepare and execute SQL query to check if there are bookings with departure dates before today
$sql_auto_delete = "DELETE FROM booking WHERE departure_date < CURRENT_DATE()";
$conn->query($sql_auto_delete);

// Prepare and execute SQL query to insert booking details into the 'booking' table
$sql_insert_booking = "INSERT INTO booking (email, phone_number, first_name, last_name, arrival_date, departure_date, gender, room_number, guest_number, bed_preference, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert_booking = $conn->prepare($sql_insert_booking);
$stmt_insert_booking->bind_param("sssssssiisd", $email, $phone_number, $first_name, $last_name, $arrivalDateFormatted, $departureDateFormatted, $gender, $roomNumber, $guestNumber, $bedPreference, $price);
$stmt_insert_booking->execute();

// Check if the insertion was successful
if ($stmt_insert_booking->affected_rows > 0) {
    echo "Booking successful! Total price: $" . $price;
    header("Location: mybooking.php");
} else {
    echo "Error in booking. Please try again.";
}
?>

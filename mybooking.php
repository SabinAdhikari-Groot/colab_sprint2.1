<?php
include 'db.php';

// Start or resume a session to access $_SESSION superglobal
session_start();

// Retrieve email from session
$email = $_SESSION['email'];

// Fetch booking details for the logged-in user
$sql = "SELECT * FROM booking WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// Fetch payment details for the logged-in user
$sql1 = "SELECT * FROM payment_details WHERE username = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $email);
$stmt1->execute();
$result1 = $stmt1->get_result();
$payments = [];
while ($row1 = $result1->fetch_assoc()) {
    $payments[$row1['booking_id']] = $row1; // Store payment details indexed by booking ID
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="staff_dashboard.css">
</head>
<body>
<nav class="navbar">
    <div class="container">
        <h1 class="logo"><img src="./images/logo.png" alt="Logo"></h1>
        <ul class="nav-links">
            <li><a href="nhomepage.html">Home</a></li>
            <li><a href="mybooking.php">My Bookings</a></li>
            <li><a href="dashboard.html">Book</a></li>
            <li><a href="naboutus.html">About Us</a></li>
            <li><a href="ncontactus.html">Contact us</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav> 
    <h1>My Bookings</h1>
    <table>
        <thead>
            <tr>
                <th>Full name</th>
                <th>Phone number</th>
                <th>Arrival Date</th>
                <th>Departure Date</th>
                <th>Gender</th>
                <th>Room Number</th>
                <th>Number of Guests</th>
                <th>Bed Preference</th>
                <th>Price</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo $booking['first_name'] . " " . $booking['last_name']; ?></td>
                    <td><?php echo $booking['phone_number']; ?></td>
                    <td><?php echo $booking['arrival_date']; ?></td>
                    <td><?php echo $booking['departure_date']; ?></td>
                    <td><?php echo $booking['gender']; ?></td>
                    <td><?php echo $booking['room_number']; ?></td>
                    <td><?php echo $booking['guest_number']; ?></td>
                    <td><?php echo $booking['bed_preference']; ?></td>
                    <td>$<?php echo $booking['price']; ?></td>
                    <td>
                        <?php 
                            if (isset($payments[$booking['id']])) {
                                echo $payments[$booking['id']]['payment_status'];
                            } else {
                                echo "pending";
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
        <button onclick="cancelBooking()">Cancel Booking</button>
        <button onclick="pay()">Pay for booking</button>
    <script src="mybooking.js"></script>
</body>
</html>

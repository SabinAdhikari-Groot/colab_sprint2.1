<?php
include 'db.php'; // Include your database connection parameters

// Function to fetch guest information from the booking table
function fetchGuestInformation($search_query = "") {
    global $conn;

    // SQL query to fetch guest information with left join on payment_details table
    $sql = "SELECT booking.*, payment_details.payment_status 
            FROM booking 
            LEFT JOIN payment_details ON booking.id = payment_details.booking_id";

    // If a search query is provided, filter the results
    if (!empty($search_query)) {
        $search_query = $conn->real_escape_string($search_query);
        $sql .= " WHERE email LIKE '%$search_query%' OR first_name LIKE '%$search_query%' OR last_name LIKE '%$search_query%'";
    }

    $sql .= " ORDER BY id DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["phone_number"] . "</td>";
            echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
            echo "<td>" . $row["gender"] . "</td>";
            echo "<td>" . $row["arrival_date"] . "</td>";
            echo "<td>" . $row["departure_date"] . "</td>";
            echo "<td>" . $row["room_number"] . "</td>";
            echo "<td>" . $row["bed_preference"] . "</td>";
            echo "<td>$" . $row["price"] . "</td>";
            echo "<td>" . ($row["payment_status"] ?? "Pending") . "</td>"; // Display "Pending" if payment status is not available
            echo "<td><form method='POST' action='staff_cancelling_booking.php'><input type='hidden' name='booking_id' value='" . $row["id"] . "'>
            <button type='submit' onclick='return confirm(\"Are you sure you want to cancel this booking?\")'>Cancel</button></form></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='11'>No guest information found</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo"><img src="./images/logo.png" alt="Logo"></h1>
            <ul class="nav-links">
                <li><a href="staff_dashboard.php">User bookings</a></li>
                <li><a href="staff_booking.html">Manual booking</a></li>
                <li><a href="staff_manage_payment.php">Payment Management</a></li>
                <li><a href="staff_managing_useraccount.php">Manage user</a></li>
                <li><a href="staff_checking_notifications.php">User Queries</a></li>
                <li><a href="newLogin.html">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <section class="guest-info">
            <h2>Guest Information</h2>
            <form method="GET" action="">
                <input type="text" name="search_query" placeholder="Search by email or name">
                <p></p>
                <button type="submit">Search</button>
            </form>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Phone number</th>
                        <th>Full name</th>
                        <th>Gender</th>
                        <th>Arrival Date</th>
                        <th>Departure Date</th>
                        <th>Room Number</th>
                        <th>Bed type</th>
                        <th>Price</th>
                        <th>Payment Status</th>
                        <th>Cancel Booking</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if a search query is submitted
                    if (isset($_GET['search_query'])) {
                        $search_query = $_GET['search_query'];
                        fetchGuestInformation($search_query);
                    } else {
                        fetchGuestInformation(); // Display all guest information by default
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

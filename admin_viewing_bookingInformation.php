<?php
include 'db.php'; // Database connection parameters

// Function to fetch booking information
function fetchBookingInformation($search_query = "") {
    global $conn;
    
    $sql = "SELECT * FROM booking";
    
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
            echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
            echo "<td>" . $row["arrival_date"] . "</td>";
            echo "<td>" . $row["departure_date"] . "</td>";
            echo "<td>$ " . $row["price"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No bookings found</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo"><img src="./images/logo.png" alt="Logo"></h1>
            <ul class="nav-links">
                <li><a href="admin_viewing_bookingInformation.php">View Bookings</a></li>
                <li><a href="admin_fetching_transactionDetails.php">View Transactions</a></li>
                <li><a href="admin_managing_useraccount.php">Manage Users</a></li>
                <li><a href="admin_managing_staffaccount.php">Manage Staffs</a></li>
                <li><a href="newLogin.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <section class="booking-information">
            <h2>Booking Information</h2>
            <form method="GET" action="">
                <input type="text" name="search_query" placeholder="Search by email or name">
                <button type="submit">Search</button>
            </form>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Full name</th>
                        <th>Arrival Date</th>
                        <th>Departure date</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if a search query is submitted
                    if (isset($_GET['search_query'])) {
                        $search_query = $_GET['search_query'];
                        fetchBookingInformation($search_query);
                    } else {
                        fetchBookingInformation(); // Display all bookings by default
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

<?php
$conn->close();
?>

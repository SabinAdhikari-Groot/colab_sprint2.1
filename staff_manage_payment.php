<?php
include 'db.php'; // Database connection parameters

// Function to fetch payment details with search functionality
function fetchPaymentDetails($search = "") {
    global $conn;
    $sql = "SELECT pd.*, b.first_name, b.last_name 
            FROM payment_details pd
            LEFT JOIN booking b ON pd.username = b.email";

    // Add search condition if a search query is provided
    if (!empty($search)) {
        $sql .= " WHERE pd.username LIKE '%$search%' OR pd.payment_id LIKE '%$search%' OR pd.payment_date LIKE '%$search%' OR pd.price LIKE '%$search%'";
    }

    $sql .= " ORDER BY pd.payment_id DESC";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["payment_id"] . "</td>";
            echo "<td>" . $row["payment_date"] . "</td>";
            echo "<td>$ " . $row["price"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No payment details found</td></tr>";
    }
}


// Function to fetch users who haven't paid yet with search functionality
function fetchPendingPayments($search = "") {
    global $conn;

    $sql = "SELECT * FROM booking WHERE id NOT IN (SELECT booking_id FROM payment_details)";
    
    // Add search condition if a search query is provided
    if (!empty($search)) {
        $sql .= " AND (email LIKE '%$search%' OR id LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR price LIKE '%$search%')";
    }

    $sql .= " ORDER BY id DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>$" . $row["price"] . "</td>";
            echo "<td>Pending</td>";
            echo "<td>";
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='email' value='" . $row["email"] . "'>";
            echo "<button type='submit' name='pay'>Pay</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No pending payments</td></tr>";
    }
}

// Function to process payment
function staffProcessPayment($email) {
    global $conn;

    // Fetch booking details for the user
    $sql = "SELECT * FROM booking WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Assuming there's only one booking per user for simplicity
    $booking = $result->fetch_assoc();
    $bookingId = $booking['id'];
    $price = $booking['price'];

    // Insert payment details into payment_details table
    $paymentStatus = 'done';
    $paymentDate = date('Y-m-d'); // Current date

    $insertSql = "INSERT INTO payment_details (booking_id, username, payment_status, payment_date, price) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("issss", $bookingId, $email, $paymentStatus, $paymentDate, $price);
    $insertStmt->execute();

    // Redirect back to manage_payment.php after payment
    header("Location: manage_payment.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payment</title>
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
        <section class="payment-details">
            <h2>Payment Details</h2>
            <!-- Payment details search form -->
            <form method="get" action="">
                <input type="text" name="payment_search" placeholder="Search Payment Details">
                <button type="submit">Search</button>
            </form>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Full name</th>
                        <th>Email</th>
                        <th>Paid ID</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Fetch payment details with or without search query
                    if(isset($_GET['payment_search'])) {
                        fetchPaymentDetails($_GET['payment_search']);
                    } else {
                        fetchPaymentDetails();
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
    <div class="container">
        <section class="make-payment">
            <h2>Make Payment</h2>
            <!-- Pending payments search form -->
            <form method="get" action="">
                <input type="text" name="pending_payment_search" placeholder="Search Pending Payments">
                <button type="submit">Search</button>
            </form>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Full name</th>
                        <th>Email</th>
                        <th>Booking Id</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch users who haven't paid yet with or without search query
                    if(isset($_GET['pending_payment_search'])) {
                        fetchPendingPayments($_GET['pending_payment_search']);
                    } else {
                        fetchPendingPayments();
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

<?php
// Check if the Pay button is clicked
if (isset($_POST['pay'])) {
    $email = $_POST['email'];
    staffProcessPayment($email);
}
$conn->close();
?>

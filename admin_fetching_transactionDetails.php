<?php
include 'db.php'; // Database connection parameters

// Function to fetch booking information
function fetchTransactionDetails($searchQuery = "") {
    global $conn;

    // Query to fetch transaction details with optional search query
    $sql = "SELECT * FROM payment_details";
    if (!empty($searchQuery)) {
        $sql .= " WHERE username LIKE '%$searchQuery%'";
    }

    $sql .= " ORDER BY payment_id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["payment_id"] . "</td>";
            echo "<td>" . $row["payment_date"] . "</td>";
            echo "<td>$ " . $row["price"] . "</td>";
            echo "<td>" . $row["payment_status"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No bookings found</td></tr>";
    }
}

// Check if search query is submitted
if(isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
} else {
    $searchQuery = "";
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
<!-- Transaction details section -->
<section class="transaction-details">
    <h2>Transaction Details</h2>
    <!-- Search form -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by email" value="<?php echo $searchQuery; ?>">
        <button type="submit">Search</button>
    </form>
    <br>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Paid Id</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php fetchTransactionDetails($searchQuery); ?>
        </tbody>
    </table>
</section>
</div>
</body>
</html>

<?php
$conn->close();
?>

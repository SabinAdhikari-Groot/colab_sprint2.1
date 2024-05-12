<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
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

<h2>Contact Messages</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Date</th>
    </tr>

    <?php
    // Connect to your database
    include 'db.php';

    // Select data from contacts table
    $sql = "SELECT id, name, email, message, created_at FROM contacts";
    $sql .= " ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["message"] . "</td>";
            echo "<td>" . $row["created_at"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No messages found</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>

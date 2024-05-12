<?php
include 'db.php'; // Database connection parameters

// Function to fetch user information
function fetchUserInformation($search_query = "") {
    global $conn;

    $sql_users = "SELECT * FROM users";

    // If a search query is provided, filter the results
    if (!empty($search_query)) {
        $search_query = $conn->real_escape_string($search_query);
        $sql_users .= " WHERE first_name LIKE '%$search_query%' OR last_name LIKE '%$search_query%' OR email LIKE '%$search_query%'";
    }

    $result_users = $conn->query($sql_users);

    if ($result_users->num_rows > 0) {
        while($row_users = $result_users->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row_users["id"] . "</td>";
            echo "<td>" . $row_users["first_name"] . " " . $row_users["last_name"] . "</td>";
            echo "<td>" . $row_users["phone_number"] . "</td>";
            echo "<td>" . $row_users["email"] . "</td>";
            echo "<td>" . $row_users["password"] . "</td>";
            echo "<td><button onclick='deleteUser(" . $row_users["id"] . ")'>Delete</button></td>"; // Delete button
            echo "<td><button onclick='showEditFields(" . $row_users["id"] . ")'>Edit</button></td>"; // Edit button
            echo "</tr>";

            // Edit form
            echo "<tr id='editFields_" . $row_users["id"] . "' style='display: none;'>";
            echo "<td colspan='8'>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='id' value='" . $row_users["id"] . "'>";
            echo "<p>Enter a new first name: </p>";
            echo "<input type='text' name='first_name' value='" . $row_users["first_name"] . "' placeholder='First Name'>";
            echo "<p>Enter a new last name: </p>";
            echo "<input type='text' name='last_name' value='" . $row_users["last_name"] . "' placeholder='Last Name'>";
            echo "<p>Enter a new phone number: </p>";
            echo "<input type='text' name='phone_number' value='" . $row_users["phone_number"] . "' placeholder='Phone Number'>";
            echo "<p>Enter a new email: </p>";
            echo "<input type='text' name='email' value='" . $row_users["email"] . "' placeholder='Email'>";
            echo "<p>Enter a new password:</p> ";
            echo "<input type='text' name='password' value='" . $row_users["password"] . "' placeholder='Password'>";
            echo "<p></p>";
            echo "<input type='submit' name='edit_user' value='Save'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No users found</td></tr>";
    }
}

// Edit user details
if(isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql_update = "UPDATE users SET first_name='$first_name', last_name='$last_name', phone_number='$phone_number', email='$email', password='$password' WHERE id='$id'";
    if ($conn->query($sql_update) === TRUE) {
        // Successfully updated
    } else {
        // Error occurred
    }
}

// Delete user
if(isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];

    $sql_delete = "DELETE FROM users WHERE id='$id'";
    if ($conn->query($sql_delete) === TRUE) {
        // Successfully deleted
    } else {
        // Error occurred
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
    <script>
        function showEditFields(id) {
            var editFields = document.getElementById('editFields_' + id);
            editFields.style.display = 'table-row';
        }
        function deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "?delete_user=" + id;
            }
        }
    </script>
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
        <section class="manage-users">
            <h2>Manage User Accounts</h2>
            <form method="GET" action="">
                <input type="text" name="search_query" placeholder="Search by name or email">
                <button type="submit">Search</button>
            </form>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Full name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Delete account</th>
                        <th>Edit account</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if a search query is submitted
                    if (isset($_GET['search_query'])) {
                        $search_query = $_GET['search_query'];
                        fetchUserInformation($search_query);
                    } else {
                        fetchUserInformation(); // Display all users by default
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

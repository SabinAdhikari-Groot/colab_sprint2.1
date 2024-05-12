<?php
include 'db.php'; // Database connection parameters

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Adding staff
    if (isset($_POST["addstaff"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $fullname = $_POST["fullname"];
        $phonenumber = $_POST["phonenumber"];
        // Validate email format
        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
            exit();
        }

        // Check if email ends with @gmail.com
        if (substr($username, -10) !== "@gmail.com") {
            echo "Email must end with @gmail.com";
            exit();
        }

        // Check if email is unique
        $check_email_query = "SELECT * FROM stafflogin WHERE username='$username'";
        $check_email_result = $conn->query($check_email_query);
        if ($check_email_result->num_rows > 0) {
            echo "Email already exists";
            exit();
        }

        // Check if password follows specific rules
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            echo "Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long";
            exit();
        }
        $sql = "INSERT INTO stafflogin (username, password, full_name, phone_number) VALUES ('$username', '$password', '$fullname', '$phonenumber')";
        if ($conn->query($sql) === TRUE) {
            echo "New staff account created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    // Editing staff
    if (isset($_POST["editstaff"])) {
        $id = $_POST["id"];
        $newUsername = $_POST["newusername"];
        $newPassword = $_POST["newpassword"];
        $newfullname = $_POST["newfullname"];
        $newphonenumber = $_POST["newphonenumber"];
        $updateFields = [];
        
        // Check if fields are not empty and add them to updateFields array
        if (!empty($newUsername)) {
            $updateFields[] = "username='$newUsername'";
        }
        if (!empty($newPassword)) {
            $updateFields[] = "password='$newPassword'";
        }
        if (!empty($newphonenumber)) {
            $updateFields[] = "phone_number='$newphonenumber'";
        }
        if (!empty($newfullname)) {
            $updateFields[] = "full_name='$newfullname'";
        }
        // Construct the SQL query based on the fields to update
        $updateQuery = "UPDATE stafflogin SET " . implode(", ", $updateFields) . " WHERE id=$id";
        
        if (!empty($updateFields)) { // Check if there are fields to update
            if ($conn->query($updateQuery) === TRUE) {
                echo "Staff account updated successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
    }

    // Deleting staff
    if (isset($_POST["deletestaff"])) {
        $id = $_POST["id"];
        $sql = "DELETE FROM stafflogin WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "Staff account deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
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
            editFields.style.display = 'block';
        }
    </script>
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
        <section class="manage-staff">
            <h2>Manage Staff Accounts</h2>
            <!-- Search form -->
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" name="search" placeholder="Search by Username or Full Name">
                <button type="submit">Search</button>
            </form>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Full name</th>
                        <th>Password</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM stafflogin";
                    if(isset($_POST['search'])) {
                        $search = $_POST['search'];
                        $sql .= " WHERE username LIKE '%$search%' OR full_name LIKE '%$search%'";
                    }
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["full_name"] . "</td>";
                            echo "<td>" . $row["password"] . "</td>";
                            echo "<td>" . $row["phone_number"] . "</td>";
                            echo "<td>";
                            echo "<button onclick='showEditFields(" . $row["id"] . ")'>Edit</button><br><br>";
                            echo "<form method='post' action='".$_SERVER['PHP_SELF']."' id='editFields_" . $row["id"] . "' style='display: none;'>";
                            echo "<input type='hidden' name='id' value='".$row["id"]."'>";
                            echo "<input type='text' name='newusername' placeholder='New Email'><br><br>";
                            echo "<input type='password' name='newpassword' placeholder='New Password'><br><br>";
                            echo "<input type='text' name='newfullname' placeholder='New Fullname'><br><br>";
                            echo "<input type='tel' pattern='[0-9]{10}' title='Please enter a 10-digit integer' name='newphonenumber' placeholder='New Phone Number' maxlength='10'><br><br>";
                            echo "<button type='submit' name='editstaff'>Save</button><br><br>";
                            echo "</form>";
                            echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
                            echo "<input type='hidden' name='id' value='".$row["id"]."'>";
                            echo "<button type='submit' name='deletestaff'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No staff accounts found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <h2>Add Staff Accounts</h2>
        <!-- Add staff form -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="username">Email:</label><br>
                <input type="text" id="username" name="username" required>
            </div>
            <br>
            <div class="form-group">
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required>
            </div>
            <br>
            <div class="form-group">
                <label for="fullname">Full name:</label><br>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <br>
            <div class="form-group">
                <label for="phonenumber">Phone number:</label><br>
                <input type="text" id="phonenumber" name="phonenumber" required>
            </div>
            <br>
            <button type="submit" name="addstaff">Add Staff</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>

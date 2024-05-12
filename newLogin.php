<?php
include 'db.php'; // Database connection parameters

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if ($role == "Admin") {
        $sql = "SELECT * FROM adminlogin WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Redirect to admin dashboard
            header("Location: admin_viewing_bookingInformation.php");
            exit();
        } else {
            $errorMessage = "Invalid email or password";
        }
    } elseif ($role == "Staff") {
        $sql = "SELECT * FROM stafflogin WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Redirect to staff dashboard
            header("Location: staff_dashboard.php");
            exit();
        } else {
            $errorMessage = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1 class="logo"><img src="./images/logo.png" alt="Logo"></h1>
        </div>
    </nav>

    <div class="container">
        <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>Admin/Staff Login</h2>
            <div class="form-group">
                <label for="username">Email:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <label for="role">Choose your role:</label>
            <select name="role" id="role">
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
            </select>
            <div id="errorMessage" class="error-message" style="display: <?php echo isset($errorMessage) ? 'block' : 'none'; ?>;"><?php echo isset($errorMessage) ? $errorMessage : ''; ?></div>
            <p></p>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>

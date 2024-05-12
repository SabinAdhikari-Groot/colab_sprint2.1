<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch room numbers from the database
$sql = "SELECT roomnumber FROM rooms";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<option value='".$row["roomnumber"]."'>".$row["roomnumber"]."</option>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

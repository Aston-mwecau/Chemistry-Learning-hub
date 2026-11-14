<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chemistryhub";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Clear all users but keep table structure
$sql = "TRUNCATE TABLE users";

if ($conn->query($sql) === TRUE) {
    header("Location: dashboard.php?cleared=1");
    exit();
} else {
    echo "Error clearing users: " . $conn->error;
}

$conn->close();
?>

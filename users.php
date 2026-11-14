<?php
// Database connection
$servername = "localhost";
$username = "root";   // adjust if needed
$password = "";       // adjust if needed
$dbname = "chemistryhub";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into users table
$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    header("Location: dashboard.php?success=1");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

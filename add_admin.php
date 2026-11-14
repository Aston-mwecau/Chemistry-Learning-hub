<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
   
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chemistryhub";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    if (!empty($newUsername) && !empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $newUsername, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: dashboard.php?admin_added=1");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Both fields are required.";
    }
}

$conn->close();
?>

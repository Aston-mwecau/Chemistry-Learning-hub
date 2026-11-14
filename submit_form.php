<?php
session_start();
include 'db_connect.php';

// Capture form data safely
$name    = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// Insert into database
$stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    // Redirect to messages dashboard so admin sees it immediately
    header("Location: messages.php");
    exit();
} else {
    echo "Error inserting: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

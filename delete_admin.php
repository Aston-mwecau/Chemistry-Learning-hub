<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
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

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prevent deleting your own account while logged in
    $sqlCheck = "SELECT username FROM admins WHERE id = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $admin = $resultCheck->fetch_assoc();

    if ($admin && $admin['username'] === $_SESSION['admin_username']) {
        die("You cannot delete your own account while logged in.");
    }

    $sql = "DELETE FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?admin_deleted=1");
        exit();
    } else {
        echo "Error deleting admin: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

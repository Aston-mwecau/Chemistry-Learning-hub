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

$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $newPassword = $_POST['newPassword'];
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE admins SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashedPassword, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?admin_reset=1");
        exit();
    } else {
        echo "Error resetting password: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head><title>Reset Admin Password</title></head>
<body>
    <h2>Reset Admin Password</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <label>New Password:</label>
        <input type="password" name="newPassword" required>
        <button type="submit">Update Password</button>
    </form>
</body>
</html>

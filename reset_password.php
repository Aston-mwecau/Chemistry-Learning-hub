<?php
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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $newPassword = $_POST['newPassword'];
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashedPassword, $id);

    if ($stmt->execute()) {
        // Fetch user email
        $result = $conn->query("SELECT email FROM users WHERE id = $id");
        $user = $result->fetch_assoc();
        $userEmail = $user['email'];

        // Send email notification
        $subject = "Your ChemistryHub Password Has Been Reset";
        $message = "Hello,\n\nYour password has been reset by the administrator.\n\nYour new password is: $newPassword\n\nPlease log in and change it immediately.\n\n- ChemistryHub Team";
        $headers = "From: chemistryhub@gmail.com";

        mail($userEmail, $subject, $message, $headers);

        header("Location: dashboard.php?reset=1");
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
<head><title>Reset Password</title></head>
<body>
    <h2>Reset Password</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label>New Password:</label>
        <input type="password" name="newPassword" required>
        <button type="submit">Update Password</button>
    </form>
</body>
</html>

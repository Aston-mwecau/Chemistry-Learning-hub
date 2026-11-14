<?php
session_start();
include 'db_connect.php';

// Handle actions
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM messages WHERE id=$id");
    header("Location: messages.php");
    exit();
}

if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $conn->query("UPDATE messages SET status = IF(status='read','unread','read') WHERE id=$id");
    header("Location: messages.php");
    exit();
}

// Fetch messages
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Messages</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background:#f0f2f5; margin:0; padding:0; }
        nav { background:#007BFF; padding:10px; text-align:center; }
        nav a { color:#fff; margin:0 15px; text-decoration:none; font-weight:bold; }
        nav a:hover { text-decoration:underline; }
        h1 { text-align:center; color:#007BFF; margin:20px 0; }
        .container { width:90%; margin:20px auto; }
        table { width:100%; border-collapse: collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #ddd; }
        th { background:#007BFF; color:#fff; }
        tr:nth-child(even) { background:#f9f9f9; }
        tr.unread { font-weight:bold; background:#fff3cd; }
        .actions a { margin-right:8px; padding:6px 10px; border-radius:4px; text-decoration:none; font-size:13px; }
        .btn-toggle { background:#17a2b8; color:#fff; }
        .btn-delete { background:#dc3545; color:#fff; }
        .btn-reply { background:#28a745; color:#fff; }
        .actions a:hover { opacity:0.85; }
        td.message { max-width:300px; word-wrap:break-word; }
    </style>
</head>
<body>
    <nav>
        <a href="dashboard.php">Registered Users</a>
        <a href="messages.php">Messages</a>
    </nav>

    <div class="container">
        <h1>Admin Dashboard - Messages</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>Received At</th>
                <th>Actions</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr class="<?php echo $row['status']=='unread' ? 'unread' : ''; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td class="message"><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td class="actions">
                        <a class="btn-toggle" href="messages.php?toggle=<?php echo $row['id']; ?>">
                            <?php echo $row['status']=='read' ? 'Mark Unread' : 'Mark Read'; ?>
                        </a>
                        <a class="btn-delete" href="messages.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this message?');">Delete</a>
                        <a class="btn-reply" href="mailto:<?php echo $row['email']; ?>?subject=Re: <?php echo urlencode($row['subject']); ?>">Reply</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;">No messages found.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>

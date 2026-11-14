<?php
session_start();
$conn = new mysqli("localhost", "root", "", "chemistryhub");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// USERS
$users = $conn->query("SELECT id, name, email, created_at FROM users ORDER BY created_at DESC");

// ADMINS
$admins = $conn->query("SELECT id, username, created_at FROM admins ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin REGISTERED USERS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background:#f0f2f5; margin:0; padding:0; }
        .container { width:90%; margin:30px auto; }
        h1 { text-align:center; color:#007BFF; margin-bottom:20px; }
        .card { background:#fff; padding:20px; margin-bottom:30px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse: collapse; margin-top:15px; }
        th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
        th { background:#007BFF; color:#fff; }
        tr:nth-child(even) { background:#f9f9f9; }
        .btn { padding:6px 12px; border:none; border-radius:4px; cursor:pointer; text-decoration:none; font-size:14px; }
        .btn-delete { background:#dc3545; color:#fff; }
        .btn-reset { background:#fd7e14; color:#fff; }
        .btn:hover { opacity:0.85; }
        form input { padding:8px; margin-right:10px; border:1px solid #ccc; border-radius:4px; }
        form button { background:#007BFF; color:#fff; }
    </style>
</head>
<body>
    <div class="container">
       

        <!-- USERS -->
        <div class="card">
            <h2>Registered Users</h2>
            <table>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Created At</th><th>Actions</th></tr>
                <?php while($u = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['created_at'] ?></td>
                    <td>
                        <a class="btn btn-delete" href="delete_user.php?id=<?= $u['id'] ?>">Delete</a>
                        <a class="btn btn-reset" href="reset_password.php?id=<?= $u['id'] ?>">Reset</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- ADMINS -->
        <div class="card">
            <h2>Admin Accounts</h2>
            <table>
                <tr><th>ID</th><th>Username</th><th>Created At</th><th>Actions</th></tr>
                <?php if ($admins && $admins->num_rows > 0): ?>
                    <?php while($a = $admins->fetch_assoc()): ?>
                    <tr>
                        <td><?= $a['id'] ?></td>
                        <td><?= htmlspecialchars($a['username']) ?></td>
                        <td><?= $a['created_at'] ?></td>
                        <td><a class="btn btn-delete" href="delete_admin.php?id=<?= $a['id'] ?>">Delete</a></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No admins found</td></tr>
                <?php endif; ?>
            </table>
            <form action="add_admin.php" method="POST" style="margin-top:15px;">
                <input type="text" name="username" placeholder="New Admin Username" required>
                <input type="password" name="password" placeholder="New Admin Password" required>
                <button type="submit" class="btn">Add Admin</button>
            </form>
        </div>
    </div>
</body>
</html>

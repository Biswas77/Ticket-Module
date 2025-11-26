<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers/functions.php';

requireLogin();

$userId = currentUserId();

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE created_by = ? ORDER BY id DESC");
$stmt->execute([$userId]);
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Tickets - CRM</title>
    <style>
        body {
            font-family: Arial;
            background: #eef2f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #0d6efd;
            padding: 15px 25px;
            color: white;
            display: flex;
            justify-content: space-between;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .container {
            width: 85%;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f5f5f5;
        }
        .status {
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            font-size: 13px;
        }
        .pending { background: #6c757d; }
        .inprogress { background: #0d6efd; }
        .completed { background: #198754; }
        .onhold { background: #ffc107; color:black; }

        .success-message {
            background: #d1e7dd;
            border-left: 5px solid #0f5132;
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: #0f5132;
        }
        a.action-btn {
            background: #0d6efd;
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.action-btn:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>CRM Ticket System</b></div>
    <div>
        <a href="index.php">Dashboard</a>
        <a href="tickets_create.php">Create Ticket</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>My Tickets</h2>

    <?php if (!empty($_GET['created'])): ?>
        <div class="success-message">Ticket Created Successfully</div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>

        <?php foreach ($tickets as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['name']) ?></td>
            <td>
                <span class="status <?= $t['status'] ?>"><?= ucfirst($t['status']) ?></span>
            </td>
            <td><?= $t['created_at'] ?></td>
            <td>
                <a class="action-btn" href="tickets_view.php?id=<?= $t['id'] ?>">View</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>

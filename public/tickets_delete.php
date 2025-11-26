<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers/functions.php';

requireLogin();

$id = $_GET['id'] ?? 0;
$userId = currentUserId();

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ? AND created_by = ?");
$stmt->execute([$id, $userId]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "Ticket not found or access denied.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $pdo->prepare("
        DELETE FROM tickets 
        WHERE id = ? AND created_by = ?
    ");
    $stmt->execute([$id, $userId]);

    header("Location: tickets_list.php?deleted=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Ticket - CRM</title>
    <style>
        body {
            font-family: Arial;
            background: #eef2f5;
            margin: 0;
        }

        .navbar {
            background: #dc3545;
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
            width: 450px;
            background: white;
            padding: 30px;
            margin: 60px auto;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .warning {
            color: #dc3545;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .btn-delete {
            width: 100%;
            padding: 12px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 6px;
            margin-top: 15px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-cancel {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #0d6efd;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div><b>CRM Ticket System</b></div>
    <div>
        <a href="index.php">Dashboard</a>
        <a href="tickets_list.php">My Tickets</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <p class="warning">Are you sure you want to delete this ticket?</p>

    <p><strong>Ticket:</strong> <?= htmlspecialchars($ticket['name']) ?></p>
    <p>This action cannot be undone.</p>

    <form method="post">
        <button class="btn-delete" type="submit">Yes, Delete Ticket</button>
    </form>

    <a class="btn-cancel" href="tickets_view.php?id=<?= $ticket['id'] ?>">Cancel</a>
</div>

</body>
</html>

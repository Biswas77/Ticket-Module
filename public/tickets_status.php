<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers/functions.php';

requireLogin();

$id = $_GET['id'] ?? 0;
$userId = currentUserId();

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) {
    echo "Ticket not found.";
    exit;
}

if ($t['assigned_to'] != $userId) {
    echo "Access denied. Only assigned user can update status.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $status = $_POST['status'] ?? $t['status'];

    $stmt = $pdo->prepare("
        UPDATE tickets 
        SET status = ?, 
            updated_at = NOW(),
            completed_at = CASE WHEN ? = 'completed' THEN NOW() ELSE NULL END
        WHERE id = ? AND assigned_to = ?
    ");

    $stmt->execute([$status, $status, $id, $userId]);

    header("Location: tickets_view.php?id=" . $id);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Ticket Status - CRM</title>
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
            width: 450px;
            background: white;
            padding: 25px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        select {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 10px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #198754;
            border: none;
            border-radius: 6px;
            margin-top: 20px;
            cursor: pointer;
            color: white;
        }

        button:hover {
            background: #157347;
        }

        .back {
            margin-top: 15px;
            display: inline-block;
            color: #0d6efd;
            text-decoration: none;
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
    <h2>Update Ticket Status</h2>

    <form method="post">

        <label>Status</label>
        <select name="status">
            <option value="pending"     <?= $t['status']=='pending'?'selected':'' ?>>Pending</option>
            <option value="inprogress" <?= $t['status']=='inprogress'?'selected':'' ?>>In Progress</option>
            <option value="completed"  <?= $t['status']=='completed'?'selected':'' ?>>Completed</option>
            <option value="onhold"     <?= $t['status']=='onhold'?'selected':'' ?>>On Hold</option>
        </select>

        <button type="submit">Update Status</button>
    </form>

    <a class="back" href="tickets_view.php?id=<?= $t['id'] ?>">← Back</a>
</div>

</body>
</html>

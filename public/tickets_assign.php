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

$users = $pdo->query("SELECT id, name FROM users")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $assigned_to = $_POST['assigned_to'] ?? null;

    $stmt = $pdo->prepare("
        UPDATE tickets
        SET assigned_to = ?, updated_at = NOW()
        WHERE id = ? AND created_by = ?
    ");
    $stmt->execute([$assigned_to, $id, $userId]);

    header("Location: tickets_view.php?id=" . $id);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assign Ticket - CRM</title>
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
            margin-top: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0d6efd;
            border: none;
            color: white;
            border-radius: 6px;
            margin-top: 15px;
            cursor: pointer;
        }

        button:hover {
            background: #0b5ed7;
        }

        .back {
            margin-top: 15px;
            display: inline-block;
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
    <h2>Assign Ticket</h2>

    <form method="post">

        <label>Assign To</label>
        <select name="assigned_to">
            <option value="">-- Select User --</option>

            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>"
                    <?= $ticket['assigned_to'] == $u['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Assign</button>
    </form>

    <a class="back" href="tickets_view.php?id=<?= $ticket['id'] ?>">← Back</a>
</div>

</body>
</html>

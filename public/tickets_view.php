<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers/functions.php';

requireLogin();

$id = $_GET['id'] ?? 0;
$userId = currentUserId();

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ? AND created_by = ?");
$stmt->execute([$id, $userId]);
$t = $stmt->fetch();

if (!$t) {
    echo "Ticket not found or access denied.";
    exit;
}

function statusBadge($status) {
    $colors = [
        "pending" => "#6c757d",
        "inprogress" => "#0d6efd",
        "completed" => "#198754",
        "onhold" => "#ffc107"
    ];
    $color = $colors[$status] ?? "#6c757d";
    $textColor = ($status === "onhold") ? "black" : "white";
    return "<span style='background:$color;color:$textColor;padding:6px 12px;border-radius:5px;font-size:13px;'>"
            . ucfirst($status) . "</span>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ticket Details - CRM</title>
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
        .section-title {
            margin-bottom: 15px;
        }
        .details p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .btn-group a {
            display: inline-block;
            padding: 10px 16px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-right: 10px;
            margin-top: 20px;
        }
        .btn-group a:hover {
            background: #0b5ed7;
        }
        .back-btn {
            background: #6c757d !important;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><b>CRM Ticket System</b></div>
    <div>
        <a href="index.php">Dashboard</a>
        <a href="tickets_list.php">My Tickets</a>
        <a href="tickets_create.php">Create Ticket</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2 class="section-title">Ticket Details</h2>

    <div class="details">
        <p><strong>ID:</strong> <?= $t['id'] ?></p>

        <p><strong>Name:</strong> <?= htmlspecialchars($t['name']) ?></p>

        <p><strong>Description:</strong><br>
        <?= nl2br(htmlspecialchars($t['description'])) ?></p>

        <p><strong>Status:</strong> <?= statusBadge($t['status']) ?></p>

        <?php if (!empty($t['file_path'])): ?>
            <p><strong>File:</strong> 
                <a href="../storage/uploads/<?= htmlspecialchars($t['file_path']) ?>" target="_blank">
                    Download File
                </a>
            </p>
        <?php endif; ?>

        <p><strong>Created At:</strong> <?= $t['created_at'] ?></p>

        <p><strong>Updated At:</strong> <?= $t['updated_at'] ?></p>

        <?php if (!empty($t['completed_at'])): ?>
            <p><strong>Completed At:</strong> <?= $t['completed_at'] ?></p>
        <?php endif; ?>
    </div>

    <div class="btn-group">
        <a href="tickets_edit.php?id=<?= $t['id'] ?>">Edit</a>
        <a href="tickets_assign.php?id=<?= $t['id'] ?>">Assign</a>

        <?php if ($t['assigned_to'] == currentUserId()): ?>
            <a href="tickets_status.php?id=<?= $t['id'] ?>">Update Status</a>
        <?php endif; ?>

        <a href="tickets_delete.php?id=<?= $t['id'] ?>" 
     onclick="return confirm('Are you sure you want to delete this ticket?');"
     style="color:red;">Delete</a>


        <a class="back-btn" href="tickets_list.php">Back</a>
    </div>

</div>

</body>
</html>

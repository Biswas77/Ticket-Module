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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = trim($_POST['status'] ?? $t['status']);

    $filePath = $t['file_path'];

    if (!empty($_FILES['file']['name'])) {
        $targetDir = __DIR__ . '/../storage/uploads/';
        $filePath = time() . "_" . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $targetDir . $filePath);
    }

    $completed_at = ($status === "completed") ? date("Y-m-d H:i:s") : $t['completed_at'];

    $stmt = $pdo->prepare("
        UPDATE tickets 
        SET name = ?, 
            description = ?, 
            status = ?, 
            file_path = ?, 
            updated_at = NOW(),
            completed_at = ?
        WHERE id = ? AND created_by = ?
    ");

    $stmt->execute([
        $name,
        $description,
        $status,
        $filePath,
        $completed_at,
        $id,
        $userId
    ]);

    header("Location: tickets_view.php?id=" . $id);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Ticket - CRM</title>
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
            width: 500px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #198754;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            border-radius: 6px;
        }

        button:hover {
            background: #157347;
        }

        a.back {
            display: inline-block;
            margin-top: 15px;
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
    <h2>Edit Ticket</h2>

    <form method="post" enctype="multipart/form-data">

        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($t['name']) ?>">

        <label>Description</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($t['description']) ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending" <?= $t['status']=='pending'?'selected':'' ?>>Pending</option>
            <option value="inprogress" <?= $t['status']=='inprogress'?'selected':'' ?>>In Progress</option>
            <option value="completed" <?= $t['status']=='completed'?'selected':'' ?>>Completed</option>
            <option value="onhold" <?= $t['status']=='onhold'?'selected':'' ?>>On Hold</option>
        </select>

        <label>File (optional)</label>
        <input type="file" name="file">

        <button type="submit">Update Ticket</button>
    </form>

    <a class="back" href="tickets_view.php?id=<?= $t['id'] ?>">← Back</a>
</div>

</body>
</html>

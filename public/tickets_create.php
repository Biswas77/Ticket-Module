<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers/functions.php';

requireLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'pending';
    $created_by = currentUserId();
    $filePath = null;

    $completed_at = null;   
    $updated_at = null;

    if ($status === "completed") {
        $completed_at = date("Y-m-d H:i:s");
    }

    $updated_at = date("Y-m-d H:i:s");

    if ($name === '' || $description === '') {
        $error = "Name and Description are required.";
    } else {

        if (!empty($_FILES['file']['name'])) {

            $targetDir = __DIR__ . '/../storage/uploads/';
            $fileName = time() . "_" . basename($_FILES['file']['name']);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                $filePath = $fileName;
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO tickets 
            (name, description, status, file_path, created_by, created_at, updated_at, completed_at, deleted_at)
            VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, NULL)
        ");

        $stmt->execute([
            $name,
            $description,
            $status,
            $filePath,
            $created_by,
            $updated_at,
            $completed_at
        ]);

        header("Location: tickets_list.php?created=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Ticket - CRM</title>
    <style>
        body { font-family: Arial; background: #eef2f5; padding: 20px; }
        .box {
            width: 480px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
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
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover { background: #0b5ed7; }
        a { text-decoration: none; color: #0d6efd; }
    </style>
</head>
<body>

<div class="box">
    <h2>Create Ticket</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">

        <label>Name</label>
        <input type="text" name="name">

        <label>Description</label>
        <textarea name="description" rows="4"></textarea>

        <label>Status</label>
        <select name="status">
            <option value="pending">Pending</option>
            <option value="inprogress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="onhold">On Hold</option>
        </select>

        <label>File</label>
        <input type="file" name="file">

        <button type="submit">Create Ticket</button>
    </form>

    <p><a href="tickets_list.php">Back</a></p>
</div>

</body>
</html>

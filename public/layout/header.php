<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CRM Ticket System</title>
    <link rel="stylesheet" href="/php_workspace/public/assets/styles.css">
</head>
<body>
<?php if (!empty($_SESSION['user'])): ?>
<nav class="navbar">
    <div class="nav-left">CRM Ticket System</div>
    <div class="nav-right">
        <a href="/php_workspace/public/index.php">Dashboard</a>
        <a href="/php_workspace/public/tickets_list.php">My Tickets</a>
        <a href="/php_workspace/public/tickets_create.php">Create Ticket</a>
        <a href="/php_workspace/public/logout.php" class="logout-btn">Logout</a>
    </div>
</nav>
<?php endif; ?>

<div class="container">

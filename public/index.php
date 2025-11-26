<?php
require_once __DIR__ . '/../src/helpers/functions.php';

if (!isLoggedIn()) {
    require_once __DIR__ . '/layout/header.php';
?>
    <div class="card center-card">
        <h2>CRM Ticket Module</h2>
        <p>Please login or register to continue.</p>
        <div style="margin-top:20px;">
            <a class="btn" href="auth_login.php">Login</a>
            <a class="btn" href="auth_register.php">Register</a>
        </div>
    </div>
<?php
    require_once __DIR__ . '/layout/footer.php';
    exit;
}
?>

<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="card">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
    <p>Your personal dashboard for managing tickets.</p>
</div>

<div class="cards">
    <div class="card">
        <h3>My Tickets</h3>
        <p>View, edit, assign and track your tickets.</p>
        <a class="btn" href="tickets_list.php">View Tickets</a>
    </div>

    <div class="card">
        <h3>Create New Ticket</h3>
        <p>Raise a new support or service request.</p>
        <a class="btn" href="tickets_create.php">Create Ticket</a>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

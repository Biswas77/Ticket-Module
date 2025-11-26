<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers/functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } else {

        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "Email already registered.";
        } else {

            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);

            header("Location: auth_login.php?registered=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - CRM Ticket System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 380px;
            background: #fff;
            margin: 70px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #198754;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #157347;
        }
        .msg {
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #0d6efd;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Create Account</h2>

    <?php if (!empty($error)): ?>
        <p class="msg" style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Name</label>
        <input type="text" name="name" placeholder="Enter full name">

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email address">

        <label>Password</label>
        <input type="password" name="password" placeholder="Create password">

        <button type="submit">Register</button>
    </form>

    <div class="footer">
        <p><a href="auth_login.php">Already have an account? Login</a></p>
    </div>
</div>

</body>
</html>x

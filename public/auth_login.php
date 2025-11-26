<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/helpers/functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = "Email & Password required.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user'] = [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "role" => $user['role']
            ];

            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - CRM Ticket System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Poppins, Arial, sans-serif;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: #ffffff;
            width: 380px;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            margin: 0 0 20px 0;
            text-align: center;
            color: #333;
        }
        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
            color: #444;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 15px;
        }
        input:focus {
            border-color: #0d6efd;
            outline: none;
            box-shadow: 0 0 5px rgba(13,110,253,0.3);
        }
        button {
            width: 100%;
            padding: 12px;
            background: #0d6efd;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0b5ed7;
        }
        .msg {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
        .register-link a {
            color: #0d6efd;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="login-box">
    <h2>Welcome Back</h2>

    <?php if (!empty($_GET['registered'])): ?>
        <p class="msg" style="color:green;">Registration successful. Please login.</p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="msg" style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email">

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password">

        <button type="submit">Login</button>
    </form>

    <div class="register-link">
        <p><a href="auth_register.php">Create a new account</a></p>
    </div>
</div>

</body>
</html>

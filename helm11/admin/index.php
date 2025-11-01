<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 { text-align: center; margin-bottom: 1rem; }
        input {
            width: 100%; padding: 10px; margin-bottom: 1rem;
            border: 1px solid #ddd; border-radius: 5px;
        }
        button {
            width: 100%; padding: 10px;
            background: #007bff; color: white;
            border: none; border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .error { color: red; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Admin</h2>
        <form action="login_process.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <p class="error">Username atau password salah!</p>
        <?php endif; ?>
    </div>
</body>
</html>

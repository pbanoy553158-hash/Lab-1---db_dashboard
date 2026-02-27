<?php
session_start();

// Already logged in? → go to dashboard
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // For real app: query database
    // For now: still using static credentials (you should change this later)
    if ($username === "admin" && $password === "admin123") {   // ← changed password
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = time();
        
        // Optional: regenerate session ID to prevent fixation
        session_regenerate_id(true);
        
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Dental Clinic Admin</title>
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--bg); }
        .login-card { 
            background: linear-gradient(145deg, #1e293b, #1b2437);
            border: 1px solid #2e3a55;
            border-radius: 16px;
            padding: 3rem 2.5rem;
            width: 100%; max-width: 420px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.45);
        }
        .login-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 0.5rem; }
        .login-subtitle { color: #94a3b8; margin-bottom: 2rem; font-size: 0.95rem; }
        .alert-error { 
            background: rgba(239,68,68,0.15); color: #fca5a5; 
            border: 1px solid rgba(239,68,68,0.3); padding: 0.9rem;
            border-radius: 10px; margin-bottom: 1.5rem; text-align: center;
        }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        input { width: 100%; padding: 0.9rem; border: 1px solid #384070; border-radius: 8px; background: #262f53; color: white; }
        input:focus { border-color: #4fc1e9; outline: none; box-shadow: 0 0 0 3px rgba(79,193,233,0.2); }
        .btn { width: 100%; margin-top: 0.5rem; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-title">Admin Login</div>
    <div class="login-subtitle">Sign in to manage the dental clinic</div>

    <?php if ($error): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required autofocus>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

</body>
</html>
<?php
session_start();

// If already logged in → redirect
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === "admin" && $password === "admin123") {
        session_regenerate_id(true);
        $_SESSION['username']     = $username;
        $_SESSION['login_time']   = time();
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
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--bg);
            margin: 0;
            overflow: hidden;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.82);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(59, 69, 94, 0.6);
            border-radius: 20px;
            padding: 3rem 2.6rem 2.8rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 
                0 20px 50px -12px rgba(0,0,0,0.55),
                inset 0 1px 0 rgba(255,255,255,0.06),
                inset 0 -1px 0 rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 
                0 30px 70px -12px rgba(0,0,0,0.65),
                inset 0 1px 0 rgba(255,255,255,0.08);
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #f1f5f9;
            text-align: center;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .login-subtitle {
            color: #94a3b8;
            text-align: center;
            font-size: 0.98rem;
            margin-bottom: 2.2rem;
        }

        .alert-error {
            background: rgba(248, 113, 113, 0.18);
            border: 1px solid rgba(248, 113, 113, 0.35);
            color: #fca5a5;
            padding: 1rem 1.2rem;
            border-radius: 10px;
            margin-bottom: 1.8rem;
            text-align: center;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        label {
            display: block;
            font-size: 0.94rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 0.6rem;
        }

        input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 1px solid #475569;
            border-radius: 10px;
            background: rgba(15, 23, 42, 0.6);
            color: #f1f5f9;
            font-size: 1rem;
            transition: all 0.25s ease;
        }

        input:focus {
            border-color: #60a5fa;
            background: rgba(15, 23, 42, 0.85);
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.22);
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 1.05rem;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.28s ease;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
        }

        .btn:hover {
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(59, 130, 246, 0.45);
        }

        .forgot {
            text-align: center;
            margin-top: 1.4rem;
            font-size: 0.92rem;
        }

        .forgot a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot a:hover {
            color: #60a5fa;
        }
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
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus autocomplete="username">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>

        <button type="submit" class="btn">Sign In</button>

        <div class="forgot">
            <a href="#">Forgot password?</a>
        </div>
    </form>
</div>

</body>
</html>
<?php
// ============================================
// FILE: admin/login.php
// Halaman login admin
// ============================================

session_start();

if (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] === true) {
    header('Location: index.php');
    exit();
}

require_once __DIR__ . '/../includes/koneksi.php';
require_once __DIR__ . '/../includes/fungsi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = bersihkan_login($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi.';
    } else {
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM admin WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);

        if ($admin && $password == $admin['password']) {
            $_SESSION['admin_login'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: index.php');
            exit();
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

function bersihkan_login($data)
{
    return htmlspecialchars(trim(stripslashes($data)));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Portfolio CV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f172a;
            --accent: #6366f1;
            --surface: #1e293b;
            --border: rgba(148, 163, 184, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--primary);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
        }

        .login-logo {
            width: 56px;
            height: 56px;
            background: var(--accent);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin: 0 auto 20px;
        }

        h1 {
            font-size: 22px;
            font-weight: 800;
            color: #f1f5f9;
            text-align: center;
        }

        p.sub {
            font-size: 14px;
            color: #94a3b8;
            text-align: center;
            margin-bottom: 28px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .form-control {
            background: #0f172a;
            border: 1px solid var(--border);
            color: #f1f5f9;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
        }

        .form-control:focus {
            background: #0f172a;
            color: #f1f5f9;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .form-control::placeholder {
            color: #475569;
        }

        .btn-login {
            background: var(--accent);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: #818cf8;
            transform: translateY(-1px);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 10px;
            font-size: 14px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #94a3b8;
            font-size: 13px;
            text-decoration: none;
        }

        .back-link a:hover {
            color: #818cf8;
        }

        .input-group-text {
            background: #0f172a;
            border: 1px solid var(--border);
            color: #94a3b8;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .input-group-text {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-logo"><i class="fas fa-shield-alt"></i></div>
        <h1>Admin Panel</h1>
        <p class="sub">Masuk untuk mengelola portfolio Anda</p>

        <?php if ($error): ?>
            <div class="alert alert-danger mb-3"><i class="fas fa-exclamation-circle me-2"></i><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label>Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label>Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk
            </button>
        </form>

        <div class="back-link">
            <a href="../index.php"><i class="fas fa-arrow-left me-1"></i> Kembali ke Portfolio</a>
        </div>

        <div class="back-link mt-2" style="color:#475569;font-size:12px">
            Default: admin / admin123
        </div>
    </div>
</body>

</html>
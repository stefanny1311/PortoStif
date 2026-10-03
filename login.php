<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Jika sudah login, redirect ke dashboard
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            setFlash('success', 'Selamat datang, ' . $user['name'] . '!');
            redirect('index.php');
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Email tidak ditemukan!';
    }
}

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Klontongan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-wrapper">

        <!-- Panel Branding -->
        <div class="login-brand">
            <div class="login-brand-content">
                <div class="login-logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <h1>Toko Klontongan</h1>
                <p class="login-brand-tagline">Sistem Kasir &amp; Manajemen Toko</p>
                <ul class="login-features">
                    <li>
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span>Kelola produk, stok &amp; kategori</span>
                    </li>
                    <li>
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span>Kasir &amp; transaksi yang cepat</span>
                    </li>
                    <li>
                        <span class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span>Laporan penjualan &amp; piutang</span>
                    </li>
                </ul>
            </div>
            <div class="login-brand-footer">
                <p>&copy; <?= date('Y') ?> Toko Klontongan</p>
            </div>
        </div>

        <!-- Panel Form Login -->
        <div class="login-form-panel">
            <div class="login-form-wrap">
                <div class="login-header">
                    <span class="login-welcome-badge">Selamat Datang</span>
                    <h2>Masuk ke Akun Anda</h2>
                    <p>Silakan masukkan email dan password untuk melanjutkan.</p>
                </div>
            
                <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                    <button class="alert-close" onclick="this.parentElement.remove()">&#10005;</button>
                </div>
                <?php endif; ?>

                <?php if ($error): ?>
                <div class="alert alert-error">
                    <div class="alert-content">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span><?= $error ?></span>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.remove()">&#10005;</button>
                </div>
                <?php endif; ?>

                <form method="POST" class="login-form" id="loginForm">
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <div class="input-icon">
                            <span class="input-icon-left">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                            <input type="email" id="email" name="email" class="form-control" required
                                   placeholder="nama@email.com" value="<?= htmlspecialchars($email) ?>" autocomplete="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-icon">
                            <span class="input-icon-left">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input type="password" id="password" name="password" class="form-control" required
                                   placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;" autocomplete="current-password">
                            <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan atau sembunyikan password">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12A3 3 0 1 1 9.88 9.88"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="login-options">
                        <label class="checkbox">
                            <input type="checkbox" id="remember">
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary login-submit">
                        Masuk
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>
                </form>

                <div class="login-info">
                    <p class="login-info-title">Akun Demo</p>
                    <button type="button" class="demo-account demo-admin" data-email="admin@toko.com" data-password="admin123">
                        <span class="demo-role">Admin</span>
                        <span class="demo-cred">admin@toko.com &#183; admin123</span>
                        <span class="demo-use">Gunakan</span>
                    </button>
                    <button type="button" class="demo-account demo-kasir" data-email="kasir@toko.com" data-password="kasir123">
                        <span class="demo-role">Kasir</span>
                        <span class="demo-cred">kasir@toko.com &#183; kasir123</span>
                        <span class="demo-use">Gunakan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var emailInput = document.getElementById('email');
            var passwordInput = document.getElementById('password');
            var togglePassword = document.getElementById('togglePassword');

            var iconEyeOff = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12A3 3 0 1 1 9.88 9.88"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
            var iconEye = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';

            togglePassword.addEventListener('click', function () {
                var isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                togglePassword.innerHTML = isHidden ? iconEye : iconEyeOff;
            });

            // Ingat saya
            var remember = document.getElementById('remember');
            if (localStorage.getItem('rememberedEmail')) {
                emailInput.value = localStorage.getItem('rememberedEmail');
                remember.checked = true;
            }
            document.getElementById('loginForm').addEventListener('submit', function () {
                if (remember.checked) {
                    localStorage.setItem('rememberedEmail', emailInput.value);
                } else {
                    localStorage.removeItem('rememberedEmail');
                }
            });

            // Isi otomatis akun demo
            var demoButtons = document.querySelectorAll('.demo-account');
            demoButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    emailInput.value = btn.getAttribute('data-email');
                    passwordInput.value = btn.getAttribute('data-password');
                    passwordInput.type = 'password';
                    togglePassword.innerHTML = iconEyeOff;
                });
            });
        })();
    </script>
</body>
</html>
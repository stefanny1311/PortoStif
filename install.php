<?php
// ============================================
// INSTALLER - Setup Database Toko Klontongan
// Jalankan sekali untuk membuat database & data awal.
// Aman dijalankan ulang (tidak merusak data yang sudah ada).
// ============================================

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'toko_klontongan';

$status = 'ready'; // ready | success | installed | error
$message = '';

$conn = @mysqli_connect($host, $user, $pass);

if (!$conn) {
    $status = 'error';
    $message = 'Gagal terhubung ke MySQL: ' . mysqli_connect_error();
} else {
    // Cek apakah tabel users sudah ada
    $check = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = 'users'");
    $exists = false;
    if ($check) {
        $row = mysqli_fetch_assoc($check);
        $exists = (int)$row['c'] > 0;
    }

    if ($exists) {
        $status = 'installed';
    } elseif (isset($_GET['run'])) {
        // Jalankan schema.sql
        $schema = @file_get_contents(__DIR__ . '/schema.sql');
        if ($schema === false) {
            $status = 'error';
            $message = 'File schema.sql tidak ditemukan.';
        } elseif (@mysqli_multi_query($conn, $schema)) {
            while (mysqli_more_results($conn) && mysqli_next_result($conn)) { ; }
            if (mysqli_errno($conn)) {
                $status = 'error';
                $message = 'Error: ' . mysqli_error($conn);
            } else {
                $status = 'success';
            }
        } else {
            $status = 'error';
            $message = 'Gagal menjalankan schema: ' . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalasi - Toko Klontongan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>TK</h1>
                <h2>Instalasi Database</h2>
                <p>Toko Klontongan</p>
            </div>

            <?php if ($status === 'success'): ?>
                <div class="alert alert-success">Database berhasil dibuat dan data awal dimasukkan!</div>
                <div class="login-info">
                    <p><strong>Akun Login:</strong></p>
                    <p>Admin: admin@toko.com / admin123</p>
                    <p>Kasir: kasir@toko.com / kasir123</p>
                </div>
                <a href="login.php" class="btn btn-primary btn-block">Lanjut ke Login</a>
            <?php elseif ($status === 'installed'): ?>
                <div class="alert alert-success">Database sudah terinstal sebelumnya.</div>
                <div class="login-info">
                    <p><strong>Akun Login:</strong></p>
                    <p>Admin: admin@toko.com / admin123</p>
                    <p>Kasir: kasir@toko.com / kasir123</p>
                </div>
                <a href="login.php" class="btn btn-primary btn-block">Lanjut ke Login</a>
            <?php elseif ($status === 'error'): ?>
                <div class="alert alert-error"><?= htmlspecialchars($message) ?></div>
                <a href="install.php" class="btn btn-primary btn-block">Coba Lagi</a>
            <?php else: ?>
                <p class="text-center" style="margin-bottom:16px;">
                    Klik tombol di bawah untuk membuat database dan data awal (produk, kategori, dan 2 akun: admin &amp; kasir).
                </p>
                <a href="install.php?run=1" class="btn btn-primary btn-block">Install Sekarang</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

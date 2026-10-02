<?php
/**
 * Auto-setup: Import database schema + seed data
 * Jalankan via browser: http://localhost/desainhub/auto-setup.php
 * Atau via CLI: c:\Xamppp\php\php.exe auto-setup.php
 */

// Bypass normal routing
define('BASE_URL', 'http://localhost/desainhub/public');
define('APP_ROOT', __DIR__);

// Manual PDO connection
$host = 'localhost';
$db   = 'desainhub';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    // Connect without database first
    $pdo = new PDO("mysql:host={$host};charset={$charset}", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '{$db}' ready<br>";
    
    // Connect to database
    $pdo->exec("USE `{$db}`");
    
    // Import SQL file
    $sqlFile = __DIR__ . '/database/desainhub.sql';
    if (!file_exists($sqlFile)) {
        die("❌ SQL file not found: {$sqlFile}");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Remove CREATE DATABASE and USE statements (already handled)
    $sql = preg_replace('/CREATE DATABASE.*?;\s*/si', '', $sql);
    $sql = preg_replace('/USE\s+desainhub\s*;\s*/i', '', $sql);
    
    // Split by semicolon, skip empty lines
    $statements = array_filter(
        array_map('trim', 
            explode(';', $sql)
        ),
        fn($s) => !empty($s)
    );
    
    $count = 0;
    foreach ($statements as $statement) {
        // Skip pure comments
        if (preg_match('/^\s*--/', $statement)) continue;
        if (empty(trim($statement))) continue;
        
        try {
            $pdo->exec($statement);
            $count++;
        } catch (PDOException $e) {
            // Table already exists, etc — skip
            if (str_contains($e->getMessage(), 'already exists') || 
                str_contains($e->getMessage(), 'Duplicate')) {
                echo "⚠️ Skip (already exists): " . substr($statement, 0, 80) . "...<br>";
                continue;
            }
            echo "❌ Error: " . $e->getMessage() . " — in: " . substr($statement, 0, 100) . "<br>";
        }
    }
    
    echo "<hr>✅ Done! {$count} statements executed.<br>";
    echo "🔑 Login: admin@desainhub.id / password<br>";
    echo "🌐 <a href='http://localhost/desainhub/public'>Buka Aplikasi</a>";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
    echo "<br><br>Pastikan MySQL di XAMPP sudah menyala!";
}
<?php
/**
 * Konfigurasi global aplikasi DesainHub
 */

// Ubah sesuai lokasi folder project di server/local (XAMPP dsb)
define('BASE_URL', 'http://localhost/desainhub/public');

define('APP_NAME', 'DesainHub');
define('APP_ROOT', dirname(__DIR__));

// Folder upload
define('UPLOAD_PORTFOLIO', APP_ROOT . '/public/assets/uploads/portfolio/');
define('UPLOAD_BRIEF', APP_ROOT . '/public/assets/uploads/brief/');
define('UPLOAD_AVATAR', APP_ROOT . '/public/assets/uploads/avatar/');

// Email (opsional — sesuaikan dengan SMTP kamu untuk fitur email notification)
define('MAIL_FROM', 'no-reply@desainhub.id');
define('MAIL_FROM_NAME', APP_NAME);

date_default_timezone_set('Asia/Jakarta');

session_start();

spl_autoload_register(function ($class) {
    $paths = [
        APP_ROOT . '/core/' . $class . '.php',
        APP_ROOT . '/controllers/' . $class . '.php',
        APP_ROOT . '/models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/helpers/functions.php';

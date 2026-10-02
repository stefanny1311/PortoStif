<?php
/**
 * Auth — helper autentikasi & proteksi role
 */

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) return null;
        return [
            'id'    => $_SESSION['user_id'],
            'nama'  => $_SESSION['user_nama'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'role'  => $_SESSION['user_role'] ?? 'customer',
            'avatar'=> $_SESSION['user_avatar'] ?? 'default-avatar.png',
        ];
    }

    public static function login(array $userRow): void
    {
        $_SESSION['user_id']     = $userRow['id'];
        $_SESSION['user_nama']   = $userRow['nama'];
        $_SESSION['user_email']  = $userRow['email'];
        $_SESSION['user_role']   = $userRow['role'];
        $_SESSION['user_avatar'] = $userRow['avatar'];
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
    }

    /** Redirect ke login jika belum login */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /** Redirect jika role tidak sesuai (mis: customer coba akses dashboard admin) */
    public static function requireRole(string|array $roles): void
    {
        self::requireLogin();
        $roles = is_array($roles) ? $roles : [$roles];
        if (!in_array($_SESSION['user_role'], $roles, true)) {
            http_response_code(403);
            require APP_ROOT . '/views/errors/403.php';
            exit;
        }
    }
}

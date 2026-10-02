<?php
/**
 * Kumpulan helper function umum
 */

function rupiah(float $angka): string
{
    return 'Rp' . number_format($angka, 0, ',', '.');
}

function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function old(string $key, string $default = ''): string
{
    return e($_SESSION['old'][$key] ?? $default);
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function timeAgo(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
    if ($diff < 2592000) return floor($diff / 86400) . ' hari lalu';
    return date('d M Y', strtotime($datetime));
}

function generateOrderCode(): string
{
    return 'DH-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}

function statusBadge(string $status): string
{
    $map = [
        'menunggu_pembayaran' => 'warning',
        'dibayar'             => 'info',
        'brief_masuk'         => 'info',
        'proses_desain'       => 'primary',
        'revisi'              => 'warning',
        'menunggu_approval'   => 'primary',
        'selesai'             => 'success',
        'dibatalkan'          => 'danger',
        'active'              => 'success',
        'pending'             => 'warning',
        'suspended'           => 'danger',
        'approved'            => 'success',
        'rejected'            => 'danger',
    ];
    return $map[$status] ?? 'secondary';
}

function statusLabel(string $status): string
{
    return ucwords(str_replace('_', ' ', $status));
}

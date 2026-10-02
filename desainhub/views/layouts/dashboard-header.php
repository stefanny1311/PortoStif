<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — <?= APP_NAME ?></title>
<meta name="description" content="Dashboard DesainHub">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<style>
.dh-dashboard { display: flex; min-height: 100vh; }
.dh-sidebar {
  width: 260px; background: var(--dh-dark); color: #fff;
  padding: 24px 0; position: fixed; top: 0; left: 0; bottom: 0;
  z-index: 100; overflow-y: auto;
}
.dh-sidebar-brand {
  padding: 0 24px 20px; display: block;
  font-family: var(--font-display); font-weight: 800; font-size: 1.3rem; color: #fff;
}
.dh-sidebar-menu { list-style: none; padding: 0; }
.dh-sidebar-menu li a {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 24px; color: rgba(255,255,255,0.65);
  font-size: 0.9rem; font-weight: 500; transition: all 0.2s;
}
.dh-sidebar-menu li a:hover, .dh-sidebar-menu li a.active {
  color: #fff; background: rgba(108,99,255,0.2); border-right: 3px solid var(--dh-primary);
}
.dh-main { margin-left: 260px; flex: 1; background: var(--dh-gray-100); }
.dh-topbar {
  background: #fff; padding: 16px 32px; display: flex;
  justify-content: space-between; align-items: center;
  border-bottom: 1px solid var(--dh-gray-200);
}
.dh-content { padding: 32px; }
@media (max-width: 991px) {
  .dh-sidebar { display: none; }
  .dh-main { margin-left: 0; }
}
</style>
</head>
<body>
<div class="dh-dashboard">
<aside class="dh-sidebar">
  <a class="dh-sidebar-brand" href="<?= url('') ?>">
    <span class="dh-brand-mark">D</span>esainHub
  </a>
  <ul class="dh-sidebar-menu">
    <?php
    $role = $_SESSION['user_role'] ?? 'customer';
    if ($role === 'admin'): ?>
      <li><a href="<?= url('admin') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin') && !str_contains($_SERVER['REQUEST_URI'], '/admin/users') && !str_contains($_SERVER['REQUEST_URI'], '/admin/designers') && !str_contains($_SERVER['REQUEST_URI'], '/admin/orders') && !str_contains($_SERVER['REQUEST_URI'], '/admin/payments') && !str_contains($_SERVER['REQUEST_URI'], '/admin/chat') && !str_contains($_SERVER['REQUEST_URI'], '/admin/reviews') && !str_contains($_SERVER['REQUEST_URI'], '/admin/blog') && !str_contains($_SERVER['REQUEST_URI'], '/admin/statistics') ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li><a href="<?= url('admin/users') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'active' : '' ?>"><i class="bi bi-people"></i> Users</a></li>
      <li><a href="<?= url('admin/designers') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/designers') ? 'active' : '' ?>"><i class="bi bi-person-check"></i> Designers</a></li>
      <li><a href="<?= url('admin/orders') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/orders') ? 'active' : '' ?>"><i class="bi bi-cart"></i> Orders</a></li>
      <li><a href="<?= url('admin/payments') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/payments') ? 'active' : '' ?>"><i class="bi bi-wallet2"></i> Payments</a></li>
      <li><a href="<?= url('admin/chat') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/chat') ? 'active' : '' ?>"><i class="bi bi-chat-dots"></i> Chat</a></li>
      <li><a href="<?= url('admin/statistics') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/statistics') ? 'active' : '' ?>"><i class="bi bi-graph-up"></i> Statistics</a></li>
    <?php elseif ($role === 'designer'): ?>
      <li><a href="<?= url('designer-dashboard') ?>" class="<?= $_SERVER['REQUEST_URI'] === '/designer-dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li><a href="<?= url('designer-dashboard/orders/new') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/orders/new') ? 'active' : '' ?>"><i class="bi bi-inbox"></i> Order Baru</a></li>
      <li><a href="<?= url('designer-dashboard/orders/active') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/orders/active') ? 'active' : '' ?>"><i class="bi bi-hourglass-split"></i> Order Aktif</a></li>
      <li><a href="<?= url('designer-dashboard/chat') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/chat') ? 'active' : '' ?>"><i class="bi bi-chat-dots"></i> Chat</a></li>
      <li><a href="<?= url('designer-dashboard/portfolio') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/portfolio') && !str_contains($_SERVER['REQUEST_URI'], '/orders') ? 'active' : '' ?>"><i class="bi bi-images"></i> Portfolio</a></li>
      <li><a href="<?= url('designer-dashboard/earnings') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/earnings') ? 'active' : '' ?>"><i class="bi bi-cash-stack"></i> Penghasilan</a></li>
    <?php else: ?>
      <li><a href="<?= url('dashboard') ?>" class="<?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li><a href="<?= url('dashboard/orders') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/orders') ? 'active' : '' ?>"><i class="bi bi-cart"></i> Pesanan Saya</a></li>
      <li><a href="<?= url('dashboard/chat') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/chat') ? 'active' : '' ?>"><i class="bi bi-chat-dots"></i> Chat</a></li>
      <li><a href="<?= url('dashboard/profile') ?>" class="<?= str_contains($_SERVER['REQUEST_URI'], '/dashboard/profile') ? 'active' : '' ?>"><i class="bi bi-person"></i> Profil</a></li>
    <?php endif; ?>
  </ul>
</aside>
<div class="dh-main">
<div class="dh-topbar">
  <span style="font-weight:600;">Selamat datang, <?= e($_SESSION['user_nama'] ?? 'User') ?></span>
  <a href="<?= url('logout') ?>" class="btn btn-sm btn-outline-danger">Keluar</a>
</div>
<div class="dh-content">
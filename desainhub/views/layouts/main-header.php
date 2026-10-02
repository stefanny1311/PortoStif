<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= APP_NAME ?> — Desain Logo & Poster Profesional Tanpa Ribet</title>
<meta name="description" content="Marketplace jasa desain logo, poster, dan brand identity. Designer terverifikasi, harga transparan, revisi mudah.">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap 5 & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- AOS Animation -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">

<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<!-- Custom Style -->
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>

<!-- ============ NAVBAR ============ -->
<nav class="navbar navbar-expand-lg dh-navbar fixed-top" id="dhNavbar">
  <div class="container">
    <a class="navbar-brand dh-brand" href="<?= url('') ?>">
      <span class="dh-brand-mark">D</span>esain<span class="dh-brand-accent">Hub</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dhNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="dhNav">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="<?= url('') ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('services') ?>">Layanan</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('portfolio') ?>">Portfolio</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('designer') ?>">Designer</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('pricing') ?>">Harga</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('blog') ?>">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('faq') ?>">FAQ</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2 dh-nav-actions">
        <?php if (Auth::check()): ?>
          <a href="<?= url('dashboard') ?>" class="btn dh-btn-ghost">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
          </a>
        <?php else: ?>
          <a href="<?= url('login') ?>" class="btn dh-btn-ghost">Masuk</a>
          <a href="<?= url('register') ?>" class="btn dh-btn-primary">Pesan Sekarang</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

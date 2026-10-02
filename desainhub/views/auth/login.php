<div class="dh-auth-page">
  <div class="dh-auth-card">
    <h2>Masuk ke DesainHub</h2>
    <p class="dh-auth-sub">Belum punya akun? <a href="<?= url('register') ?>">Daftar di sini</a></p>

    <?php $flash = getFlash(); if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('login') ?>">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div><input type="checkbox" name="remember"> Ingat saya</div>
        <a href="<?= url('forgot-password') ?>" style="font-size:0.85rem;">Lupa password?</a>
      </div>
      <button type="submit" class="btn dh-btn-primary w-100">Masuk</button>
    </form>
  </div>
</div>
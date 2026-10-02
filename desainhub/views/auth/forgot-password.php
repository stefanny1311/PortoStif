<div class="dh-auth-page">
  <div class="dh-auth-card">
    <h2>Lupa Password</h2>
    <p class="dh-auth-sub">Masukkan email kamu, kami akan kirim link reset.</p>
    <?php $flash = getFlash(); if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
    <form method="POST" action="<?= url('forgot-password') ?>">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <button type="submit" class="btn dh-btn-primary w-100">Kirim Link Reset</button>
    </form>
    <p class="text-center mt-3"><a href="<?= url('login') ?>">← Kembali ke login</a></p>
  </div>
</div>
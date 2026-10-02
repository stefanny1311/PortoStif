<div class="dh-auth-page">
  <div class="dh-auth-card">
    <h2>Reset Password</h2>
    <p class="dh-auth-sub">Masukkan password baru kamu.</p>
    <?php $flash = getFlash(); if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
    <form method="POST" action="<?= url('reset-password/' . $token) ?>">
      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn dh-btn-primary w-100">Simpan Password</button>
    </form>
  </div>
</div>
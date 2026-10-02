<div class="dh-auth-page">
  <div class="dh-auth-card">
    <h2>Daftar di DesainHub</h2>
    <p class="dh-auth-sub">Sudah punya akun? <a href="<?= url('login') ?>">Masuk di sini</a></p>

    <?php $flash = getFlash(); if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('register') ?>">
      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" placeholder="Nama kamu" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
      </div>
      <div class="mb-4">
        <label class="form-label">Daftar Sebagai</label>
        <select name="role" class="form-control">
          <option value="customer">Customer (Mencari jasa desain)</option>
          <option value="designer">Designer (Menawarkan jasa desain)</option>
        </select>
      </div>
      <button type="submit" class="btn dh-btn-primary w-100">Daftar Sekarang</button>
    </form>
  </div>
</div>
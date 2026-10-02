<div class="container py-4">
  <h3 class="fw-bold mb-4">Pengaturan Akun</h3>
  <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">
      <form>
        <div class="mb-3"><label class="form-label">Nama</label><input type="text" class="form-control" value="<?= Auth::user()['nama'] ?? '' ?>"></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" value="<?= Auth::user()['email'] ?? '' ?>" disabled></div>
        <div class="mb-3"><label class="form-label">Spesialisasi</label><input type="text" class="form-control" placeholder="Logo & Brand Identity"></div>
        <div class="mb-3"><label class="form-label">Bio</label><textarea class="form-control" rows="3"></textarea></div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </form>
    </div>
  </div>
</div>
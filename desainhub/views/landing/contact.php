<div class="dh-page-header">
  <div class="container">
    <h1>Hubungi Kami</h1>
    <p>Punya pertanyaan? Tim kami siap membantu.</p>
  </div>
</div>
<div class="dh-page-content">
  <div class="container" style="max-width:700px;">
    <?php $flash = getFlash(); if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    <div class="dh-card-simple p-4">
      <form method="POST" action="<?= url('contact') ?>">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Pesan</label>
          <textarea name="pesan" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn dh-btn-primary">Kirim Pesan</button>
      </form>
    </div>
    <div class="row mt-4 g-3 text-center">
      <div class="col-md-4"><div class="dh-card-simple"><i class="bi bi-envelope" style="font-size:1.5rem;color:var(--dh-primary);"></i><p class="mt-2 mb-0">halo@desainhub.id</p></div></div>
      <div class="col-md-4"><div class="dh-card-simple"><i class="bi bi-whatsapp" style="font-size:1.5rem;color:#25D366;"></i><p class="mt-2 mb-0">+62 812-3456-7890</p></div></div>
      <div class="col-md-4"><div class="dh-card-simple"><i class="bi bi-geo-alt" style="font-size:1.5rem;color:var(--dh-secondary);"></i><p class="mt-2 mb-0">Jakarta, Indonesia</p></div></div>
    </div>
  </div>
</div>
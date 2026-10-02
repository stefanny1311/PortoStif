<div class="dh-page-header">
  <div class="container">
    <h1>Designer Terverifikasi</h1>
    <p>Bekerja dengan designer profesional pilihan yang sudah melalui proses verifikasi.</p>
  </div>
</div>

<div class="dh-page-content">
  <div class="container">
    <?php if (!empty($designers)): ?>
    <div class="row g-4">
      <?php foreach ($designers as $d): ?>
      <div class="col-md-6 col-lg-3">
        <div class="dh-designer-card">
          <?php if (!empty($d['verified'])): ?>
            <span class="dh-verified-badge" title="Verified"><i class="bi bi-patch-check-fill"></i></span>
          <?php endif; ?>
          <img src="https://placehold.co/120x120/8B5CF6/FFFFFF?text=<?= urlencode(substr(e($d['nama']), 0, 1)) ?>" class="dh-designer-avatar" alt="<?= e($d['nama']) ?>">
          <h6><?= e($d['nama']) ?></h6>
          <span class="dh-designer-spec"><?= e($d['spesialisasi']) ?></span>
          <div class="dh-designer-rating">
            <i class="bi bi-star-fill"></i> <?= number_format((float)$d['rating_rata'], 1) ?>
            <span class="dh-designer-projects">(<?= (int)$d['jumlah_project'] ?> project)</span>
          </div>
          <a href="<?= url('designer/' . $d['id']) ?>" class="btn dh-btn-outline btn-sm w-100 mt-3">Lihat Profil</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
      <div style="font-size:3rem;">👨‍🎨</div>
      <h3>Designer sedang bergabung</h3>
      <p class="text-muted">Tunggu kehadiran mereka ya!</p>
    </div>
    <?php endif; ?>
  </div>
</div>
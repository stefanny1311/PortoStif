<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Portfolio Saya</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPortfolioModal">
      <i class="bi bi-plus-circle me-1"></i> Tambah Karya
    </button>
  </div>

  <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>

  <?php if (!empty($myPortfolio)): ?>
    <div class="row g-4">
      <?php foreach ($myPortfolio as $item): ?>
      <div class="col-md-4 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="<?= asset('uploads/portfolio/' . $item['gambar']) ?>" class="card-img-top" alt="<?= e($item['judul']) ?>" style="height:200px;object-fit:cover" onerror="this.src='https://placehold.co/400x300/6C63FF/FFFFFF?text=No+Image'">
          <div class="card-body">
            <h6 class="card-title"><?= e($item['judul']) ?></h6>
            <p class="card-text small text-muted"><?= e($item['deskripsi'] ?? 'Tidak ada deskripsi') ?></p>
            <span class="badge bg-<?= statusBadge($item['status']) ?>"><?= statusLabel($item['status']) ?></span>
            <small class="text-muted d-block mt-1"><?= timeAgo($item['created_at']) ?></small>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="text-center py-5">
      <i class="bi bi-image text-muted" style="font-size:4rem"></i>
      <p class="text-muted mt-3">Belum ada portfolio. Silakan tambahkan karya desain terbaikmu.</p>
    </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="addPortfolioModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tambah Portfolio</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form action="<?= url('designer-dashboard/portfolio') ?>" method="POST" enctype="multipart/form-data">
          <div class="mb-3"><label class="form-label">Judul</label><input type="text" name="judul" class="form-control" required></div>
          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>"><?= e($cat['nama']) ?></option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="1">Logo</option>
                <option value="2">Poster</option>
                <option value="3">Brand Identity</option>
                <option value="4">Packaging</option>
                <option value="5">Social Media</option>
              <?php endif; ?>
            </select>
          </div>
          <div class="mb-3"><label class="form-label">Gambar</label><input type="file" name="gambar" class="form-control" accept="image/*" required></div>
          <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"></textarea></div>
          <button type="submit" class="btn btn-primary w-100">Upload Portfolio</button>
        </form>
      </div>
    </div>
  </div>
</div>
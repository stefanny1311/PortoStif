<?php $d = $designer; ?>
<div class="dh-page-header">
  <div class="container">
    <h1><?= e($d['nama']) ?></h1>
    <p><?= e($d['spesialisasi']) ?></p>
  </div>
</div>

<div class="dh-page-content">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <div class="dh-card-simple text-center">
          <img src="https://placehold.co/200x200/8B5CF6/FFFFFF?text=<?= urlencode(substr(e($d['nama']), 0, 1)) ?>" class="dh-designer-avatar mx-auto d-block mb-3" alt="<?= e($d['nama']) ?>" style="width:150px;height:150px;border-radius:50%;">
          <h4><?= e($d['nama']) ?></h4>
          <p class="text-muted"><?= e($d['spesialisasi']) ?></p>
          <div class="mb-3">
            <i class="bi bi-star-fill" style="color:#FFD166;"></i> <?= number_format((float)$d['rating_rata'], 1) ?> (<?= (int)$d['jumlah_project'] ?> project)
          </div>
          <p><?= e($d['bio'] ?? 'Designer profesional di DesainHub.') ?></p>
          <a href="<?= url('register') ?>" class="btn dh-btn-primary w-100">Pesan Desain</a>
        </div>
      </div>
      <div class="col-lg-8">
        <!-- Portfolio Section -->
        <?php if (!empty($portfolio)): ?>
        <h4 class="fw-bold mb-3">Portfolio</h4>
        <div class="row g-3 mb-4">
          <?php foreach ($portfolio as $p): ?>
          <div class="col-6 col-lg-4">
            <div class="dh-gallery-card">
              <img src="<?= filter_var($p['gambar'], FILTER_VALIDATE_URL) ? e($p['gambar']) : asset('uploads/portfolio/' . e($p['gambar'])) ?>" alt="<?= e($p['judul']) ?>" onerror="this.src='https://placehold.co/400x400/6C63FF/FFFFFF?text=No+Image'" style="height:180px;object-fit:cover;width:100%">
              <div class="dh-gallery-overlay"><h6><?= e($p['judul']) ?></h6></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h4 class="fw-bold mb-3">Pesan Desain dari <?= e($d['nama']) ?></h4>
        <?php if ($flash = getFlash()): ?>
          <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <form action="<?= url('designer/' . $d['id'] . '/order') ?>" method="POST" enctype="multipart/form-data" class="dh-card-simple p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Kategori Desain</label>
            <select name="category_id" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <option value="1">Logo</option>
              <option value="2">Poster</option>
              <option value="3">Brand Identity</option>
              <option value="4">Packaging</option>
              <option value="5">Social Media</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Paket</label>
            <select name="package_id" class="form-select" required>
              <option value="">-- Pilih Paket --</option>
              <option value="1">Basic — Rp150.000 (2x Revisi, 3 hari)</option>
              <option value="2">Standard — Rp350.000 (5x Revisi, 4 hari)</option>
              <option value="3">Premium — Rp650.000 (Unlimited Revisi, 5 hari)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi Kebutuhan (Brief)</label>
            <textarea name="deskripsi" class="form-control" rows="5" placeholder="Jelaskan kebutuhan desainmu... misal: warna favorit, gaya desain, referensi, target audience, dll." required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">File Referensi (opsional)</label>
            <input type="file" name="referensi" class="form-control" accept="image/*,.pdf">
            <small class="text-muted">Upload gambar referensi atau brief detail (max 5MB)</small>
          </div>
          <button type="submit" class="btn dh-btn-primary btn-lg w-100">
            <i class="bi bi-send me-2"></i>Kirim Pesanan
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
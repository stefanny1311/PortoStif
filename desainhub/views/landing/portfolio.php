<div class="dh-page-header">
  <div class="container">
    <h1>Portfolio</h1>
    <p>Lihat hasil karya designer profesional kami.</p>
  </div>
</div>

<div class="dh-page-content">
  <div class="container">
    <?php if (!empty($galeri)): ?>
    <div class="row g-4">
      <?php foreach ($galeri as $item): ?>
      <div class="col-6 col-lg-3">
        <div class="dh-gallery-card">
          <img src="<?= filter_var($item['gambar'], FILTER_VALIDATE_URL) ? e($item['gambar']) : asset('uploads/portfolio/' . e($item['gambar'])) ?>" alt="<?= e($item['judul']) ?>" onerror="this.src='https://placehold.co/400x400/6C63FF/FFFFFF?text=<?= urlencode(e($item['judul'])) ?>'" style="height:240px;object-fit:cover;width:100%">
          <div class="dh-gallery-overlay">
            <span class="dh-gallery-cat"><?= e($item['kategori'] ?? 'Desain') ?></span>
            <h6><?= e($item['judul']) ?></h6>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5">
      <div style="font-size:3rem;">🎨</div>
      <h3 class="mt-3">Portfolio Segera Hadir</h3>
      <p class="text-muted">Designer kami sedang menyiapkan karya terbaiknya.</p>
    </div>
    <?php endif; ?>
  </div>
</div>
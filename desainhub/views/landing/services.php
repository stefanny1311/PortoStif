<div class="dh-page-header">
  <div class="container">
    <h1>Layanan Kami</h1>
    <p>Pilih jenis desain sesuai kebutuhan bisnis dan brand Anda.</p>
  </div>
</div>

<div class="dh-page-content">
  <div class="container">
    <div class="row g-4">
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
        <div class="col-md-6 col-lg-4">
          <div class="dh-card-simple text-center">
            <div style="font-size:2.5rem;color:var(--dh-primary);"><i class="bi <?= e($cat['icon']) ?>"></i></div>
            <h4 class="mt-3"><?= e($cat['nama']) ?></h4>
            <p><?= e($cat['deskripsi']) ?></p>
            <a href="<?= url('register') ?>" class="btn dh-btn-primary btn-sm mt-2">Pesan Sekarang</a>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php
        $layanan = [
          ['icon' => 'bi-vector-pen', 'nama' => 'Logo', 'desc' => 'Desain logo profesional untuk brand kamu.'],
          ['icon' => 'bi-image', 'nama' => 'Poster', 'desc' => 'Poster promosi, event, hingga campaign.'],
          ['icon' => 'bi-palette', 'nama' => 'Brand Identity', 'desc' => 'Paket identitas visual lengkap.'],
          ['icon' => 'bi-box-seam', 'nama' => 'Packaging', 'desc' => 'Desain kemasan produk.'],
          ['icon' => 'bi-instagram', 'nama' => 'Social Media', 'desc' => 'Konten visual untuk media sosial.'],
        ];
        ?>
        <?php foreach ($layanan as $l): ?>
        <div class="col-md-6 col-lg-4">
          <div class="dh-card-simple text-center">
            <div style="font-size:2.5rem;color:var(--dh-primary);"><i class="bi <?= $l['icon'] ?>"></i></div>
            <h4 class="mt-3"><?= $l['nama'] ?></h4>
            <p><?= $l['desc'] ?></p>
            <a href="<?= url('register') ?>" class="btn dh-btn-primary btn-sm mt-2">Pesan Sekarang</a>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
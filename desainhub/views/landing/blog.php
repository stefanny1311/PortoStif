<div class="dh-page-header">
  <div class="container">
    <h1>Blog</h1>
    <p>Tips, tren, dan inspirasi seputar desain grafis.</p>
  </div>
</div>

<div class="dh-page-content">
  <div class="container">
    <div class="row g-4">
      <?php
      $posts = [
        ['judul' => 'Tips Memilih Logo yang Tepat untuk UMKM', 'desc' => 'Logo adalah wajah brand kamu. Simak tips memilih logo yang tepat...', 'tgl' => '15 Jun 2026'],
        ['judul' => 'Tren Desain Grafis 2026', 'desc' => 'Tahun ini banyak tren desain baru yang fresh dan modern...', 'tgl' => '10 Jun 2026'],
        ['judul' => 'Cara Membuat Brief Desain yang Efektif', 'desc' => 'Brief yang jelas membantu designer memahami kebutuhanmu...', 'tgl' => '5 Jun 2026'],
      ];
      foreach ($posts as $post): ?>
      <div class="col-md-6 col-lg-4">
        <div class="dh-card-simple">
          <div style="height:180px;background:var(--dh-gradient);border-radius:12px;margin-bottom:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;">📝</div>
          <span style="font-size:0.8rem;color:var(--dh-gray-500);"><?= $post['tgl'] ?></span>
          <h5 class="mt-2"><?= $post['judul'] ?></h5>
          <p style="font-size:0.9rem;color:var(--dh-gray-500);"><?= $post['desc'] ?></p>
          <a href="#" class="btn dh-btn-ghost btn-sm">Baca Selengkapnya →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
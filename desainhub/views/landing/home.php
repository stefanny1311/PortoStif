<!-- ============ HERO ============ -->
<section class="dh-hero">
  <div class="container">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6" data-aos="fade-up">
        <span class="dh-eyebrow"><i class="bi bi-patch-check-fill"></i> Dipercaya 500+ UMKM &amp; Brand</span>
        <h1 class="dh-hero-title">Desain Logo Profesional <span class="dh-underline">Tanpa Ribet</span></h1>
        <p class="dh-hero-sub">Pesan logo, poster, branding, dan identitas visual hanya dalam beberapa klik. Konsultasi gratis, harga transparan, designer terverifikasi.</p>
        <div class="d-flex flex-wrap gap-3 dh-hero-cta">
          <a href="<?= url('register') ?>" class="btn dh-btn-primary btn-lg">
            Pesan Sekarang <i class="bi bi-arrow-right ms-1"></i>
          </a>
          <a href="<?= url('portfolio') ?>" class="btn dh-btn-outline btn-lg">
            <i class="bi bi-play-circle me-1"></i> Lihat Portfolio
          </a>
        </div>
        <div class="dh-hero-trust">
          <div class="dh-avatar-stack">
            <span></span><span></span><span></span><span></span>
          </div>
          <span>50+ designer siap membantu proyekmu</span>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
        <!-- Signature element: stack kartu desain melayang, merepresentasikan galeri portfolio -->
        <div class="dh-hero-visual">
          <div class="dh-float-card dh-card-1">
            <div class="dh-card-thumb dh-thumb-1"></div>
            <div class="dh-card-meta"><i class="bi bi-vector-pen"></i> Logo Minimalis</div>
          </div>
          <div class="dh-float-card dh-card-2">
            <div class="dh-card-thumb dh-thumb-2"></div>
            <div class="dh-card-meta"><i class="bi bi-image"></i> Poster Event</div>
          </div>
          <div class="dh-float-card dh-card-3">
            <div class="dh-card-thumb dh-thumb-3"></div>
            <div class="dh-card-meta"><i class="bi bi-palette"></i> Brand Identity</div>
          </div>
          <div class="dh-hero-badge">
            <i class="bi bi-star-fill"></i>
            <div><strong>4.9/5</strong><span>Rating Customer</span></div>
          </div>
          <div class="dh-hero-blob"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ TRUSTED / STATISTIK ============ -->
<section class="dh-stats">
  <div class="container">
    <div class="row g-4">
      <div class="col-6 col-lg-3" data-aos="fade-up">
        <div class="dh-stat-card">
          <div class="dh-stat-number">1000+</div>
          <div class="dh-stat-label">Logo dibuat</div>
        </div>
      </div>
      <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="dh-stat-card">
          <div class="dh-stat-number">500+</div>
          <div class="dh-stat-label">UMKM terbantu</div>
        </div>
      </div>
      <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
        <div class="dh-stat-card">
          <div class="dh-stat-number">50+</div>
          <div class="dh-stat-label">Designer verified</div>
        </div>
      </div>
      <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
        <div class="dh-stat-card">
          <div class="dh-stat-number">98%</div>
          <div class="dh-stat-label">Customer puas</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ MASALAH CUSTOMER (Pain Point) ============ -->
<section class="dh-section">
  <div class="container">
    <div class="dh-section-head" data-aos="fade-up">
      <span class="dh-eyebrow-dark">Kami Mengerti Kesulitanmu</span>
      <h2 class="dh-section-title">Sering Merasa Begini Saat Butuh Desain?</h2>
    </div>
    <div class="row g-4">
      <?php
        $masalah = [
          ['icon' => 'bi-brush', 'title' => 'Tidak bisa desain', 'desc' => 'Kamu punya ide, tapi bingung cara mewujudkannya secara visual.'],
          ['icon' => 'bi-search', 'title' => 'Sulit menemukan designer terpercaya', 'desc' => 'Banyak tawaran jasa desain, tapi ragu soal kualitas dan kredibilitasnya.'],
          ['icon' => 'bi-cash-coin', 'title' => 'Harga tidak transparan', 'desc' => 'Sering harus nego panjang tanpa tahu standar harga yang wajar.'],
          ['icon' => 'bi-question-circle', 'title' => 'Bingung menentukan konsep', 'desc' => 'Tidak tahu harus mulai dari mana untuk membuat brief desain.'],
          ['icon' => 'bi-laptop', 'title' => 'Website terlalu rumit', 'desc' => 'Platform lain terasa ribet untuk orang yang kurang familiar teknologi.'],
        ];
      ?>
      <?php foreach ($masalah as $i => $m): ?>
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
        <div class="dh-problem-card">
          <div class="dh-problem-icon"><i class="bi <?= $m['icon'] ?>"></i></div>
          <h5><?= $m['title'] ?></h5>
          <p><?= $m['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ SOLUSI ============ -->
<section class="dh-section dh-section-tint">
  <div class="container">
    <div class="dh-section-head" data-aos="fade-up">
      <span class="dh-eyebrow-dark">Solusi Dari Kami</span>
      <h2 class="dh-section-title">DesainHub Menjawab Semua Itu</h2>
    </div>
    <div class="row g-4">
      <?php
        $solusi = [
          ['icon' => 'bi-chat-dots', 'title' => 'Konsultasi Gratis', 'desc' => 'Diskusikan kebutuhanmu bersama tim sebelum memulai project.'],
          ['icon' => 'bi-lightbulb', 'title' => 'Template Inspirasi', 'desc' => 'Bingung konsep? Lihat referensi desain untuk memantapkan ide.'],
          ['icon' => 'bi-patch-check', 'title' => 'Designer Verified', 'desc' => 'Semua designer melalui proses verifikasi portfolio dan identitas.'],
          ['icon' => 'bi-tags', 'title' => 'Harga Transparan', 'desc' => 'Paket harga jelas sejak awal, tanpa biaya tersembunyi.'],
          ['icon' => 'bi-bar-chart-steps', 'title' => 'Progress Project', 'desc' => 'Pantau status pesanan secara real-time dari brief sampai selesai.'],
          ['icon' => 'bi-arrow-repeat', 'title' => 'Unlimited Revision', 'desc' => 'Tersedia paket dengan revisi tanpa batas hingga kamu puas.'],
        ];
      ?>
      <?php foreach ($solusi as $i => $s): ?>
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
        <div class="dh-solution-card">
          <div class="dh-solution-icon"><i class="bi <?= $s['icon'] ?>"></i></div>
          <h5><?= $s['title'] ?></h5>
          <p><?= $s['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ CARA KERJA ============ -->
<section class="dh-section">
  <div class="container">
    <div class="dh-section-head" data-aos="fade-up">
      <span class="dh-eyebrow-dark">Alur Pemesanan</span>
      <h2 class="dh-section-title"></h2>
    </div>
    <div class="dh-timeline">
      <?php
        $steps = [
          ['num' => '1', 'title' => 'Pilih Layanan', 'desc' => 'Tentukan jenis desain yang kamu butuhkan.'],
          ['num' => '2', 'title' => 'Isi Brief', 'desc' => 'Ceritakan kebutuhan & referensi desainmu.'],
          ['num' => '3', 'title' => 'Pilih Designer', 'desc' => 'Bandingkan portfolio, rating, dan harga.'],
          ['num' => '4', 'title' => 'Pembayaran', 'desc' => 'Bayar aman lewat metode pilihanmu.'],
          ['num' => '5', 'title' => 'Designer Bekerja', 'desc' => 'Pantau progress project secara langsung.'],
          ['num' => '6', 'title' => 'Download File', 'desc' => 'Terima hasil akhir & file sumber desain.'],
        ];
      ?>
      <?php foreach ($steps as $i => $s): ?>
      <div class="dh-timeline-item" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
        <div class="dh-timeline-num"><?= $s['num'] ?></div>
        <h6><?= $s['title'] ?></h6>
        <p><?= $s['desc'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ DESIGNER ============ -->
<section class="dh-section">
  <div class="container">
    <div class="dh-section-head" data-aos="fade-up">
      <span class="dh-eyebrow-dark">Talenta Pilihan</span>
      <h2 class="dh-section-title">Designer Terverifikasi</h2>
    </div>
    <div class="row g-4">
      <?php
        // fallback dummy jika database masih kosong, agar tampilan tetap premium
        $designers = $topDesigners ?: [
          ['nama' => 'Rian Pratama', 'avatar' => 'default-avatar.png', 'spesialisasi' => 'Logo & Brand Identity', 'rating_rata' => 4.9, 'jumlah_project' => 128, 'verified' => 1],
          ['nama' => 'Sinta Wulandari', 'avatar' => 'default-avatar.png', 'spesialisasi' => 'Poster & Social Media', 'rating_rata' => 4.8, 'jumlah_project' => 94, 'verified' => 1],
          ['nama' => 'Bagas Saputra', 'avatar' => 'default-avatar.png', 'spesialisasi' => 'Packaging Design', 'rating_rata' => 4.9, 'jumlah_project' => 76, 'verified' => 1],
          ['nama' => 'Nabila Putri', 'avatar' => 'default-avatar.png', 'spesialisasi' => 'Brand Identity', 'rating_rata' => 5.0, 'jumlah_project' => 61, 'verified' => 1],
        ];
      ?>
      <?php foreach ($designers as $i => $d): ?>
      <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
        <div class="dh-designer-card">
          <?php if (!empty($d['verified'])): ?>
            <span class="dh-verified-badge" title="Designer Verified"><i class="bi bi-patch-check-fill"></i></span>
          <?php endif; ?>
          <img src="https://placehold.co/120x120/8B5CF6/FFFFFF?text=<?= substr(e($d['nama']), 0, 1) ?>" class="dh-designer-avatar" alt="<?= e($d['nama']) ?>">
          <h6><?= e($d['nama']) ?></h6>
          <span class="dh-designer-spec"><?= e($d['spesialisasi']) ?></span>
          <div class="dh-designer-rating">
            <i class="bi bi-star-fill"></i> <?= number_format((float)$d['rating_rata'], 1) ?>
            <span class="dh-designer-projects">(<?= (int)$d['jumlah_project'] ?> project)</span>
          </div>
          <a href="<?= url('designer') ?>" class="btn dh-btn-outline btn-sm w-100 mt-3">Lihat Profil</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ PRICING ============ -->
<section class="dh-section dh-section-tint">
  <div class="container">
    <div class="dh-section-head" data-aos="fade-up">
      <span class="dh-eyebrow-dark">Paket Harga</span>
      <h2 class="dh-section-title">Transparan, Tanpa Biaya Tersembunyi</h2>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-md-6 col-lg-4" data-aos="fade-up">
        <div class="dh-price-card">
          <h5>Basic</h5>
          <div class="dh-price-amount">Rp150K<span>/project</span></div>
          <ul class="dh-price-list">
            <li><i class="bi bi-check-circle-fill"></i> 2x Revisi</li>
            <li><i class="bi bi-check-circle-fill"></i> File PNG &amp; JPG</li>
            <li class="dh-price-off"><i class="bi bi-x-circle"></i> File AI</li>
            <li class="dh-price-off"><i class="bi bi-x-circle"></i> Brand Guideline</li>
          </ul>
          <a href="<?= url('register') ?>" class="btn dh-btn-outline w-100">Pilih Paket</a>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="dh-price-card dh-price-featured">
          <span class="dh-price-tag">Paling Populer</span>
          <h5>Standard</h5>
          <div class="dh-price-amount">Rp350K<span>/project</span></div>
          <ul class="dh-price-list">
            <li><i class="bi bi-check-circle-fill"></i> 5x Revisi</li>
            <li><i class="bi bi-check-circle-fill"></i> File AI, PNG &amp; JPG</li>
            <li><i class="bi bi-check-circle-fill"></i> Konsultasi Prioritas</li>
            <li class="dh-price-off"><i class="bi bi-x-circle"></i> Brand Guideline</li>
          </ul>
          <a href="<?= url('register') ?>" class="btn dh-btn-primary w-100">Pilih Paket</a>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
        <div class="dh-price-card">
          <h5>Premium</h5>
          <div class="dh-price-amount">Rp650K<span>/project</span></div>
          <ul class="dh-price-list">
            <li><i class="bi bi-check-circle-fill"></i> Revisi Unlimited</li>
            <li><i class="bi bi-check-circle-fill"></i> File AI, PNG, JPG &amp; PSD</li>
            <li><i class="bi bi-check-circle-fill"></i> Brand Guideline</li>
            <li><i class="bi bi-check-circle-fill"></i> Konsultasi Prioritas</li>
          </ul>
          <a href="<?= url('register') ?>" class="btn dh-btn-outline w-100">Pilih Paket</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ TESTIMONI ============ -->
<section class="dh-section">
  <div class="container">
    <div class="dh-section-head" data-aos="fade-up">
      <span class="dh-eyebrow-dark">Kata Mereka</span>
      <h2 class="dh-section-title">Testimoni Customer</h2>
    </div>
    <div class="swiper dh-testi-swiper" data-aos="fade-up">
      <div class="swiper-wrapper">
        <?php
          $testimoni = [
            ['nama' => 'Dewi Anggraini', 'peran' => 'Owner, Kopi Senja', 'rating' => 5, 'komentar' => 'Prosesnya cepat dan hasil logonya sesuai banget dengan konsep UMKM saya. Timnya juga responsif menjawab pertanyaan.'],
            ['nama' => 'Fajar Ramadhan', 'peran' => 'Content Creator', 'rating' => 5, 'komentar' => 'Poster untuk konten YouTube saya jadi lebih menarik. Harganya juga jelas sejak awal, tidak ada biaya tambahan.'],
            ['nama' => 'Melati Ayu', 'peran' => 'Founder, Melati Craft', 'rating' => 4, 'komentar' => 'Suka fitur tracking progress-nya, jadi tidak perlu bolak-balik tanya update ke designer.'],
          ];
        ?>
        <?php foreach ($testimoni as $t): ?>
        <div class="swiper-slide">
          <div class="dh-testi-card">
            <div class="dh-testi-rating">
              <?php for ($i = 0; $i < 5; $i++): ?>
                <i class="bi <?= $i < $t['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
              <?php endfor; ?>
            </div>
            <p>"<?= $t['komentar'] ?>"</p>
            <div class="dh-testi-author">
              <img src="https://placehold.co/48x48/6C63FF/FFFFFF?text=<?= substr($t['nama'], 0, 1) ?>" alt="<?= $t['nama'] ?>">
              <div>
                <strong><?= $t['nama'] ?></strong>
                <span><?= $t['peran'] ?></span>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="swiper-pagination mt-4"></div>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="dh-section dh-section-tint">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="dh-section-head" data-aos="fade-up">
          <span class="dh-eyebrow-dark">Pertanyaan Umum</span>
          <h2 class="dh-section-title">Masih Ada Pertanyaan?</h2>
        </div>
        <div class="accordion dh-accordion" id="faqAccordion" data-aos="fade-up">
          <?php
            $faqs = [
              ['q' => 'Berapa lama proses pengerjaan desain?', 'a' => 'Estimasi waktu tergantung paket yang dipilih, mulai dari 3 hari untuk paket Basic hingga 5 hari untuk paket Premium.'],
              ['q' => 'Bagaimana jika saya tidak puas dengan hasil desain?', 'a' => 'Kamu bisa mengajukan revisi sesuai jumlah yang tersedia di paketmu, atau unlimited revisi jika memilih paket Premium.'],
              ['q' => 'Apakah designer di DesainHub sudah terverifikasi?', 'a' => 'Ya, setiap designer melalui proses verifikasi portfolio dan identitas oleh tim admin sebelum bisa menerima pesanan.'],
              ['q' => 'Metode pembayaran apa saja yang tersedia?', 'a' => 'Kami mendukung transfer bank, e-wallet, virtual account, dan kartu kredit.'],
            ];
          ?>
          <?php foreach ($faqs as $i => $f): ?>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                <?= $f['q'] ?>
              </button>
            </h2>
            <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
              <div class="accordion-body"><?= $f['a'] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="dh-cta">
  <div class="container">
    <div class="dh-cta-box" data-aos="zoom-in">
      <h2>Ayo Bangun Brand Anda Sekarang</h2>
      <p>Mulai project desainmu hari ini bersama designer profesional pilihan.</p>
      <a href="<?= url('register') ?>" class="btn dh-btn-white btn-lg">
        Mulai Sekarang <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>

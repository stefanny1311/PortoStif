<!-- ============ FOOTER ============ -->
<footer class="dh-footer">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-4">
        <a class="dh-brand dh-brand-footer" href="<?= url('') ?>">
          <span class="dh-brand-mark">D</span>esain<span class="dh-brand-accent">Hub</span>
        </a>
        <p class="dh-footer-desc">
          Marketplace jasa desain logo & poster. Menghubungkan kamu dengan designer
          terverifikasi, harga transparan, tanpa ribet.
        </p>
        <div class="d-flex gap-2 dh-social">
          <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
          <a href="#" aria-label="Email"><i class="bi bi-envelope"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-6">
        <h6 class="dh-footer-title">Menu</h6>
        <ul class="dh-footer-links">
          <li><a href="<?= url('about') ?>">Tentang Kami</a></li>
          <li><a href="<?= url('services') ?>">Layanan</a></li>
          <li><a href="<?= url('portfolio') ?>">Portfolio</a></li>
          <li><a href="<?= url('designer') ?>">Designer</a></li>
          <li><a href="<?= url('pricing') ?>">Harga</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-6">
        <h6 class="dh-footer-title">Bantuan</h6>
        <ul class="dh-footer-links">
          <li><a href="<?= url('faq') ?>">FAQ</a></li>
          <li><a href="<?= url('blog') ?>">Blog</a></li>
          <li><a href="<?= url('contact') ?>">Kontak</a></li>
        </ul>
      </div>
      <div class="col-lg-4">
        <h6 class="dh-footer-title">Kontak</h6>
        <ul class="dh-footer-links">
          <li><i class="bi bi-envelope me-2"></i>halo@desainhub.id</li>
          <li><i class="bi bi-whatsapp me-2"></i>+62 812-3456-7890</li>
          <li><i class="bi bi-geo-alt me-2"></i>Jakarta, Indonesia</li>
        </ul>
      </div>
    </div>
    <hr class="dh-footer-divider">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <span class="dh-footer-copy">&copy; <?= date('Y') ?> DesainHub. Semua hak dilindungi.</span>
      <span class="dh-footer-copy">Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk UMKM Indonesia</span>
    </div>
  </div>
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
<!-- SwiperJS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
<div class="dh-page-header">
  <div class="container">
    <h1>FAQ</h1>
    <p>Pertanyaan yang sering ditanyakan tentang DesainHub.</p>
  </div>
</div>
<div class="dh-section">
  <div class="container">
    <div class="row justify-content-center"><div class="col-lg-8">
    <div class="accordion dh-accordion" id="faqAccordion">
      <?php
        $faqs = [
          ['q'=>'Berapa lama proses desain?','a'=>'Estimasi 3-5 hari tergantung paket.'],
          ['q'=>'Bagaimana jika tidak puas?','a'=>'Kamu bisa mengajukan revisi sesuai paket.'],
          ['q'=>'Apakah designer terverifikasi?','a'=>'Ya, semua designer melalui verifikasi admin.'],
          ['q'=>'Metode pembayaran apa saja?','a'=>'Transfer bank, e-wallet, virtual account.'],
        ];
        foreach ($faqs as $i => $f): ?>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button <?= $i>0?'collapsed':'' ?>" data-bs-toggle="collapse" data-bs-target="#faq<?=$i?>"><?= $f['q'] ?></button>
          </h2>
          <div id="faq<?=$i?>" class="accordion-collapse collapse <?= $i===0?'show':'' ?>" data-bs-parent="#faqAccordion">
            <div class="accordion-body"><?= $f['a'] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
    </div>
    </div></div>
  </div>
</div>
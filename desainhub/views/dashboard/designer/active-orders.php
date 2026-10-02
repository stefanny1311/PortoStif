<div class="container py-4">
  <h3 class="fw-bold mb-4">Pesanan Aktif</h3>
  <?php if (!empty($orders)): ?>
  <div class="row g-4">
    <?php foreach ($orders as $o): ?>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <h6><?= e($o['kode_order']) ?></h6>
            <span class="badge bg-<?= statusBadge($o['status']) ?>"><?= statusLabel($o['status']) ?></span>
          </div>
          <p class="mb-1"><strong><?= e($o['customer_nama']) ?></strong> — <?= e($o['nama_paket']) ?></p>
          <p class="fw-bold text-primary"><?= rupiah($o['total_harga']) ?></p>
          <div class="progress" style="height:6px"><div class="progress-bar" style="width:<?= $o['progress_percent'] ?>%"></div></div>
          <small class="text-muted"><?= $o['progress_percent'] ?>% · <?= date('d M Y', strtotime($o['updated_at'])) ?></small>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <p class="text-muted text-center py-5">Belum ada pesanan aktif.</p>
  <?php endif; ?>
</div>
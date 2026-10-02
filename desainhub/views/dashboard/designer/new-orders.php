<div class="container py-4">
  <h3 class="fw-bold mb-4">Pesanan Baru</h3>
  <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <?php if (!empty($orders)): ?>
  <div class="row g-4">
    <?php foreach ($orders as $o): ?>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <h6 class="mb-0"><?= e($o['kode_order']) ?></h6>
            <span class="badge bg-<?= statusBadge($o['status']) ?>"><?= statusLabel($o['status']) ?></span>
          </div>
          <p class="mb-1"><strong><?= e($o['customer_nama']) ?></strong> — <?= e($o['nama_paket']) ?></p>
          <p class="fw-bold text-primary"><?= rupiah($o['total_harga']) ?></p>
          <small class="text-muted"><?= date('d M Y', strtotime($o['created_at'])) ?></small>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <p class="text-muted text-center py-5">Belum ada pesanan baru.</p>
  <?php endif; ?>
</div>
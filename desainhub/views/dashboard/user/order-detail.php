<div class="container py-4">
  <a href="<?= url('dashboard/orders') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>Kembali</a>

  <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>

  <?php if (!$order): ?>
    <p class="text-muted">Pesanan tidak ditemukan.</p>
  <?php else: ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold"><?= e($order['kode_order']) ?></h5>
          <span class="badge bg-<?= statusBadge($order['status']) ?> fs-6"><?= statusLabel($order['status']) ?></span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6"><small class="text-muted">Designer</small><br><strong><?= e($order['designer_nama']) ?></strong> — <?= e($order['spesialisasi']) ?></div>
            <div class="col-md-6"><small class="text-muted">Paket</small><br><strong><?= e($order['nama_paket']) ?></strong> — <?= rupiah($order['harga']) ?></div>
            <div class="col-md-6"><small class="text-muted">Revisi</small><br><?= $order['jumlah_revisi'] == -1 ? 'Unlimited' : $order['jumlah_revisi'] . 'x' ?></div>
            <div class="col-md-6"><small class="text-muted">Estimasi</small><br><?= $order['estimasi_hari'] ?> hari</div>
          </div>
          <hr>
          <h6>Progress</h6>
          <div class="progress mb-3" style="height:10px">
            <div class="progress-bar" style="width:<?= $order['progress_percent'] ?>%"></div>
          </div>
          <small class="text-muted"><?= $order['progress_percent'] ?>% selesai</small>
        </div>
      </div>

      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><h5 class="mb-0 fw-bold">Brief & Timeline</h5></div>
        <div class="card-body">
          <?php if (!empty($details)): ?>
            <?php foreach ($details as $det): ?>
            <div class="border-start border-primary ps-3 mb-3">
              <small class="text-muted"><?= date('d M Y H:i', strtotime($det['created_at'])) ?> — <?= e($det['tipe']) ?></small>
              <div><?= nl2br(e($det['deskripsi'] ?? '')) ?></div>
              <?php if ($det['file_path']): ?>
                <a href="<?= asset('uploads/brief/' . $det['file_path']) ?>" class="btn btn-sm btn-outline-secondary mt-1" target="_blank"><i class="bi bi-download me-1"></i>Download File</a>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-muted">Belum ada detail.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><h5 class="mb-0 fw-bold">Info Pembayaran</h5></div>
        <div class="card-body">
          <div class="mb-3"><small class="text-muted">Total</small><h4 class="text-primary"><?= rupiah($order['total_harga']) ?></h4></div>
          <div class="mb-3"><small class="text-muted">Status</small><br><span class="badge bg-<?= statusBadge($order['status']) ?>"><?= statusLabel($order['status']) ?></span></div>
          <div class="mb-3"><small class="text-muted">Deadline</small><br><?= $order['deadline'] ? date('d M Y', strtotime($order['deadline'])) : 'Belum ditentukan' ?></div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
<div class="container py-4">
  <h3 class="fw-bold mb-4">Pesanan Saya</h3>

  <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>

  <?php if (!empty($orders)): ?>
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>Kode</th><th>Designer</th><th>Paket</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
          <tr>
            <td><strong><?= e($o['kode_order']) ?></strong></td>
            <td><?= e($o['designer_nama']) ?></td>
            <td><?= e($o['nama_paket']) ?></td>
            <td><?= rupiah($o['total_harga']) ?></td>
            <td><span class="badge bg-<?= statusBadge($o['status']) ?>"><?= statusLabel($o['status']) ?></span></td>
            <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
            <td><a href="<?= url('dashboard/orders/' . $o['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php else: ?>
  <div class="text-center py-5">
    <i class="bi bi-cart-x text-muted" style="font-size:3rem"></i>
    <h5 class="mt-3">Belum ada pesanan</h5>
    <p class="text-muted">Pesan desain dari designer pilihanmu.</p>
    <a href="<?= url('designer') ?>" class="btn dh-btn-primary">Cari Designer</a>
  </div>
  <?php endif; ?>
</div>
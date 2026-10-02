<div class="container py-4">
  <h3 class="fw-bold mb-4">Kelola Pesanan</h3>
  <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <?php if (!empty($orders)): ?>
  <div class="card border-0 shadow-sm"><div class="table-responsive">
    <table class="table table-hover mb-0"><thead class="table-light"><tr><th>Kode</th><th>Customer</th><th>Designer</th><th>Paket</th><th>Status</th><th>Total</th><th>Tanggal</th></tr></thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
      <tr>
        <td><strong><?= e($o['kode_order']) ?></strong></td>
        <td><?= e($o['customer_nama']) ?></td>
        <td><?= e($o['designer_nama']) ?></td>
        <td><?= e($o['nama_paket']) ?></td>
        <td><span class="badge bg-<?= statusBadge($o['status']) ?>"><?= statusLabel($o['status']) ?></span></td>
        <td><?= rupiah($o['total_harga']) ?></td>
        <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody></table>
  </div></div>
  <?php else: ?>
  <p class="text-muted text-center py-5">Belum ada pesanan.</p>
  <?php endif; ?>
</div>
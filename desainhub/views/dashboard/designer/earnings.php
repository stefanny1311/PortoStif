<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Penghasilan Saya</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#withdrawModal">
      <i class="bi bi-wallet2 me-1"></i> Tarik Dana
    </button>
  </div>
  <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <div class="row g-4 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">Saldo Tersedia</small><h4 class="mb-0">Rp0</h4></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">Total Pendapatan</small><h4 class="mb-0">Rp0</h4></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><small class="text-muted">Total Project</small><h4 class="mb-0">0</h4></div></div></div>
  </div>
  <p class="text-muted">Belum ada riwayat pendapatan.</p>
</div>

<div class="modal fade" id="withdrawModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tarik Dana</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form action="/designer-dashboard/withdraw" method="POST">
          <div class="mb-3"><label class="form-label">Jumlah Penarikan</label><input type="number" name="jumlah" class="form-control" required min="50000" placeholder="Minimal Rp50.000"></div>
          <div class="mb-3"><label class="form-label">Metode</label><select name="metode" class="form-select"><option>Transfer Bank</option><option>E-Wallet</option></select></div>
          <button type="submit" class="btn btn-primary w-100">Ajukan Penarikan</button>
        </form>
      </div>
    </div>
  </div>
</div>
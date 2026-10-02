<div class="container py-4">
  <?php if ($flash = getFlash()): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show"><?= $flash['message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <h3 class="fw-bold mb-4">Manajemen Designer</h3>
  <div class="card border-0 shadow-sm"><div class="card-body">
    <table class="table"><thead><tr><th>ID</th><th>Nama</th><th>Spesialisasi</th><th>Status</th><th>Rating</th><th>Project</th><th>Aksi</th></tr></thead><tbody>
    <tr><td colspan="7" class="text-center text-muted">Belum ada designer.</td></tr>
    </tbody></table>
  </div></div>
</div>
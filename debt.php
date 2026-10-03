<?php
require_once 'includes/header.php';

$search = $_GET['search'] ?? '';
$customers = getCustomers($search);
?>

<div class="page-header">
    <h1>Bon / Piutang Pelanggan</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="search" placeholder="Cari pelanggan..." 
                           value="<?= htmlspecialchars($search) ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-secondary">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>No. HP</th>
                    <th>Batas Bon</th>
                    <th>Total Hutang</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $has_debt = false;
                while ($row = mysqli_fetch_assoc($customers)): 
                    if ($row['current_debt'] > 0 || $row['max_debt_limit'] > 0):
                        $has_debt = true;
                        $debt_percentage = $row['max_debt_limit'] > 0 ? ($row['current_debt'] / $row['max_debt_limit'] * 100) : 0;
                        $status_class = $debt_percentage >= 100 ? 'badge-danger' : ($debt_percentage >= 75 ? 'badge-warning' : 'badge-success');
                        $status_text = $debt_percentage >= 100 ? 'Limit Tercapai' : ($debt_percentage >= 75 ? 'Mendekati Limit' : 'Aman');
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                    <td><?= htmlspecialchars($row['phone'] ?: '-') ?></td>
                    <td><?= formatRupiah($row['max_debt_limit']) ?></td>
                    <td>
                        <span class="badge <?= $row['current_debt'] > 0 ? 'badge-danger' : '' ?> badge-lg">
                            <?= formatRupiah($row['current_debt']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $status_class ?>">
                            <?= $status_text ?> (<?= round($debt_percentage) ?>%)
                        </span>
                        <?php if ($row['current_debt'] > 0): ?>
                        <div class="progress-bar mt-1">
                            <div class="progress-fill" style="width: <?= min($debt_percentage, 100) ?>%; 
                                background: <?= $debt_percentage >= 100 ? '#e74c3c' : ($debt_percentage >= 75 ? '#f39c12' : '#2ecc71') ?>"></div>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="table-actions">
                        <a href="customer_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Detail</a>
                        <?php if ($row['phone'] && $row['current_debt'] > 0): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $row['phone']) ?>?text=<?= urlencode("Halo {$row['name']},\n\nIni pengingat dari Toko Klontongan. Anda memiliki bon sebesar " . formatRupiah($row['current_debt']) . ".\n\nMohon segera dilunasi. Terima kasih!") ?>" 
                           target="_blank" class="btn btn-sm btn-warning">WA</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    endif;
                endwhile; 
                if (!$has_debt):
                ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data piutang</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
<?php
require_once 'includes/header.php';

$start_date = $_GET['start_date'] ?? date('Y-m-d');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$payment_method = $_GET['payment_method'] ?? '';
$payment_status = $_GET['payment_status'] ?? '';

$transactions = getTransactions($start_date, $end_date, $payment_method, $payment_status);
?>

<div class="page-header">
    <h1>Riwayat Transaksi</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Dari</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                </div>
                <div class="form-group">
                    <label>Sampai</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                </div>
                <div class="form-group">
                    <label>Metode</label>
                    <select name="payment_method" class="form-control">
                        <option value="">Semua</option>
                        <option value="cash" <?= $payment_method == 'cash' ? 'selected' : '' ?>>Tunai</option>
                        <option value="qris" <?= $payment_method == 'qris' ? 'selected' : '' ?>>QRIS</option>
                        <option value="transfer" <?= $payment_method == 'transfer' ? 'selected' : '' ?>>Transfer</option>
                        <option value="kasbon" <?= $payment_method == 'kasbon' ? 'selected' : '' ?>>Kasbon</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="payment_status" class="form-control">
                        <option value="">Semua</option>
                        <option value="lunas" <?= $payment_status == 'lunas' ? 'selected' : '' ?>>Lunas</option>
                        <option value="pending" <?= $payment_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="dibayar_sebagian" <?= $payment_status == 'dibayar_sebagian' ? 'selected' : '' ?>>Dibayar Sebagian</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($transactions) > 0): ?>
                    <?php 
                    $total_sales = 0;
                    while ($row = mysqli_fetch_assoc($transactions)): 
                        $total_sales += $row['grand_total'];
                    ?>
                    <tr>
                        <td><code><?= $row['invoice_no'] ?></code></td>
                        <td><?= formatTanggal($row['created_at']) ?></td>
                        <td><?= htmlspecialchars($row['user_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['customer_name'] ?? '-') ?></td>
                        <td><strong><?= formatRupiah($row['grand_total']) ?></strong></td>
                        <td>
                            <span class="badge">
                                <?= strtoupper($row['payment_method']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $row['payment_status'] == 'lunas' ? 'badge-success' : 'badge-warning' ?>">
                                <?= ucfirst(str_replace('_', ' ', $row['payment_status'])) ?>
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="transaction_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="table-footer">
                        <td colspan="4"><strong>Total Penjualan</strong></td>
                        <td><strong><?= formatRupiah($total_sales) ?></strong></td>
                        <td colspan="3"></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada transaksi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
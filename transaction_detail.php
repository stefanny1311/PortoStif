<?php
require_once 'includes/header.php';

$id = (int)$_GET['id'];
$transaction = getTransactionById($id);

if (!$transaction) {
    setFlash('error', 'Transaksi tidak ditemukan!');
    redirect('transactions.php');
}

$details = getTransactionDetails($id);
?>

<div class="page-header">
    <h1>Detail Transaksi</h1>
    <div>
        <a href="transactions.php" class="btn btn-secondary">Kembali</a>
        <button class="btn btn-primary" onclick="printReceipt()">Cetak</button>
    </div>
</div>

<div class="two-col">
    <div class="card">
        <div class="card-header"><h3>Informasi Transaksi</h3></div>
        <div class="card-body">
            <table class="table-info">
                <tr><td><strong>Invoice</strong></td><td><code><?= $transaction['invoice_no'] ?></code></td></tr>
                <tr><td><strong>Tanggal</strong></td><td><?= formatTanggal($transaction['created_at']) ?></td></tr>
                <tr><td><strong>Kasir</strong></td><td><?= htmlspecialchars($transaction['user_name'] ?? '-') ?></td></tr>
                <tr><td><strong>Pelanggan</strong></td><td><?= htmlspecialchars($transaction['customer_name'] ?? 'Umum') ?></td></tr>
                <?php if ($transaction['customer_phone']): ?>
                <tr><td><strong>No. HP</strong></td><td><?= htmlspecialchars($transaction['customer_phone']) ?></td></tr>
                <?php endif; ?>
                <tr><td><strong>Metode</strong></td><td><?= strtoupper($transaction['payment_method']) ?></td></tr>
                <tr><td><strong>Status</strong></td><td>
                    <span class="badge <?= $transaction['payment_status'] == 'lunas' ? 'badge-success' : 'badge-warning' ?>">
                        <?= ucfirst(str_replace('_', ' ', $transaction['payment_status'])) ?>
                    </span>
                </td></tr>
                <?php if ($transaction['notes']): ?>
                <tr><td><strong>Catatan</strong></td><td><?= htmlspecialchars($transaction['notes']) ?></td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Ringkasan Pembayaran</h3></div>
        <div class="card-body">
            <table class="table-info">
                <tr><td><strong>Total</strong></td><td><?= formatRupiah($transaction['total_amount']) ?></td></tr>
                <tr><td><strong>Diskon</strong></td><td>- <?= formatRupiah($transaction['discount']) ?></td></tr>
                <tr><td><strong>Grand Total</strong></td><td><strong style="font-size:1.2em;"><?= formatRupiah($transaction['grand_total']) ?></strong></td></tr>
                <tr><td><strong>Dibayar</strong></td><td><?= formatRupiah($transaction['payment_amount']) ?></td></tr>
                <tr><td><strong>Kembalian</strong></td><td><?= formatRupiah($transaction['change_amount']) ?></td></tr>
            </table>
        </div>
    </div>
</div>

<div class="card" id="receiptContent">
    <div class="card-header"><h3>Item Dibeli</h3></div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Barcode</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($d = mysqli_fetch_assoc($details)): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><code><?= $d['barcode'] ?: '-' ?></code></td>
                    <td><?= htmlspecialchars($d['product_name'] ?? 'Produk dihapus') ?></td>
                    <td><?= $d['qty'] ?></td>
                    <td><?= formatRupiah($d['sell_price']) ?></td>
                    <td><strong><?= formatRupiah($d['subtotal']) ?></strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function printReceipt() {
    window.print();
}
</script>

<?php require_once 'includes/footer.php'; ?>
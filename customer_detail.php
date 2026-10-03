<?php
require_once 'includes/header.php';

$id = (int)$_GET['id'];
$customer = getCustomerById($id);

if (!$customer) {
    setFlash('error', 'Pelanggan tidak ditemukan!');
    redirect('customers.php');
}

$debt_history = getCustomerDebtHistory($id);

// Handle debt payment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'pay_debt') {
    $amount = (float)$_POST['amount'];
    $payment_method = cleanInput($_POST['payment_method']);
    $notes = cleanInput($_POST['notes']);

    if ($amount > $customer['current_debt']) {
        setFlash('error', 'Jumlah pembayaran melebihi hutang!');
    } elseif ($amount > 0) {
        mysqli_begin_transaction($conn);
        try {
            // Insert payment record
            mysqli_query($conn, "INSERT INTO debt_payments (customer_id, amount, payment_method, notes) 
                VALUES ($id, $amount, '$payment_method', '$notes')");
            
            // Update customer debt
            $new_debt = $customer['current_debt'] - $amount;
            mysqli_query($conn, "UPDATE customers SET current_debt = $new_debt WHERE id = $id");
            
            mysqli_commit($conn);
            setFlash('success', 'Pembayaran bon berhasil dicatat!');
        } catch (Exception $e) {
            mysqli_rollback($conn);
            setFlash('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
        redirect("customer_detail.php?id=$id");
    }
}
?>

<div class="page-header">
    <h1>Detail Pelanggan</h1>
    <a href="customers.php" class="btn btn-secondary">Kembali</a>
</div>

<div class="two-col">
    <div class="card">
        <div class="card-header">
            <h3>Informasi Pelanggan</h3>
        </div>
        <div class="card-body">
            <table class="table-info">
                <tr><td><strong>Nama</strong></td><td><?= htmlspecialchars($customer['name']) ?></td></tr>
                <tr><td><strong>No. HP</strong></td><td><?= htmlspecialchars($customer['phone'] ?: '-') ?></td></tr>
                <tr><td><strong>Alamat</strong></td><td><?= htmlspecialchars($customer['address'] ?: '-') ?></td></tr>
                <tr><td><strong>Batas Bon</strong></td><td><?= formatRupiah($customer['max_debt_limit']) ?></td></tr>
                <tr>
                    <td><strong>Hutang Saat Ini</strong></td>
                    <td>
                        <span class="badge <?= $customer['current_debt'] > 0 ? 'badge-danger' : 'badge-success' ?> badge-lg">
                            <?= formatRupiah($customer['current_debt']) ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Bayar Bon / Cicilan</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="action" value="pay_debt">
                <div class="form-group">
                    <label>Jumlah Pembayaran (Rp) *</label>
                    <input type="number" name="amount" class="form-control" required 
                           min="1" max="<?= $customer['current_debt'] ?>" 
                           value="<?= $customer['current_debt'] ?>">
                    <small>Sisa hutang: <?= formatRupiah($customer['current_debt']) ?></small>
                </div>
                <div class="form-group">
                    <label>Metode</label>
                    <select name="payment_method" class="form-control">
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-success btn-block" 
                        <?= $customer['current_debt'] <= 0 ? 'disabled' : '' ?>>
                    Bayar Bon
                </button>
            </form>

            <!-- WhatsApp Reminder Link -->
            <?php if ($customer['current_debt'] > 0 && $customer['phone']): ?>
            <div class="mt-3">
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $customer['phone']) ?>?text=<?= urlencode("Halo {$customer['name']},\n\nIni pengingat dari Toko Klontongan. Anda memiliki bon sebesar " . formatRupiah($customer['current_debt']) . ".\n\nMohon segera dilunasi. Terima kasih!") ?>"
                   target="_blank" class="btn btn-warning btn-block">
                    Kirim Pengingat via WhatsApp
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Riwayat Pembayaran -->
<div class="card">
    <div class="card-header">
        <h3>Riwayat Pembayaran Bon</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Invoice</th>
                    <th>Jumlah</th>
                    <th>Metode</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($debt_history) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($debt_history)): ?>
                    <tr>
                        <td><?= formatTanggal($row['created_at']) ?></td>
                        <td><code><?= $row['invoice_no'] ?: '-' ?></code></td>
                        <td><?= formatRupiah($row['amount']) ?></td>
                        <td><?= ucfirst($row['payment_method']) ?></td>
                        <td><?= htmlspecialchars($row['notes'] ?: '-') ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada riwayat pembayaran</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
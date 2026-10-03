<?php
require_once 'includes/header.php';

$search = $_GET['search'] ?? '';

// Handle add/edit customer
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = cleanInput($_POST['name']);
    $phone = cleanInput($_POST['phone']);
    $address = cleanInput($_POST['address']);
    $max_debt_limit = (float)$_POST['max_debt_limit'];

    if (isset($_POST['id']) && $_POST['id']) {
        $id = (int)$_POST['id'];
        mysqli_query($conn, "UPDATE customers SET name='$name', phone='$phone', address='$address', max_debt_limit=$max_debt_limit WHERE id=$id");
        setFlash('success', 'Pelanggan berhasil diupdate!');
    } else {
        mysqli_query($conn, "INSERT INTO customers (name, phone, address, max_debt_limit) VALUES ('$name', '$phone', '$address', $max_debt_limit)");
        setFlash('success', 'Pelanggan berhasil ditambahkan!');
    }
    redirect('customers.php');
}

// Handle delete
if (isset($_GET['delete']) && isAdmin()) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM customers WHERE id = $id");
    setFlash('success', 'Pelanggan berhasil dihapus!');
    redirect('customers.php');
}

$customers = getCustomers($search);
$edit_customer = null;
if (isset($_GET['edit'])) {
    $edit_customer = getCustomerById((int)$_GET['edit']);
}
?>

<div class="page-header">
    <h1>Manajemen Pelanggan</h1>
    <button class="btn btn-primary" onclick="openModal('customerModal')">+ Tambah Pelanggan</button>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="search" placeholder="Cari nama / no HP..." 
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
                    <th>#</th>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Batas Bon</th>
                    <th>Hutang Saat Ini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($customers) > 0): ?>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($customers)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($row['address'] ?: '-') ?></td>
                        <td><?= formatRupiah($row['max_debt_limit']) ?></td>
                        <td>
                            <span class="badge <?= $row['current_debt'] > 0 ? 'badge-danger' : 'badge-success' ?>">
                                <?= formatRupiah($row['current_debt']) ?>
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="customer_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Detail</a>
                            <button class="btn btn-sm btn-secondary" 
                                    onclick="editCustomer(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['phone'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($row['address'] ?? '', ENT_QUOTES) ?>', <?= $row['max_debt_limit'] ?>)">Edit</button>
                            <?php if (isAdmin()): ?>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Hapus pelanggan ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data pelanggan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Pelanggan -->
<div class="modal" id="customerModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Pelanggan</h3>
            <button class="modal-close" onclick="closeModal('customerModal')">x</button>
        </div>
        <form method="POST">
            <input type="hidden" name="id" id="custId">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama *</label>
                    <input type="text" name="name" id="custName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="phone" id="custPhone" class="form-control">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="address" id="custAddress" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Batas Maksimal Bon (Rp)</label>
                    <input type="number" name="max_debt_limit" id="custDebtLimit" class="form-control" value="0" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('customerModal')">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Tambah Pelanggan';
    document.getElementById('custId').value = '';
    document.getElementById('custName').value = '';
    document.getElementById('custPhone').value = '';
    document.getElementById('custAddress').value = '';
    document.getElementById('custDebtLimit').value = '0';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function editCustomer(id, name, phone, address, debtLimit) {
    document.getElementById('modalTitle').innerText = 'Edit Pelanggan';
    document.getElementById('custId').value = id;
    document.getElementById('custName').value = name;
    document.getElementById('custPhone').value = phone;
    document.getElementById('custAddress').value = address;
    document.getElementById('custDebtLimit').value = debtLimit;
    openModal('customerModal');
}
</script>

<?php require_once 'includes/footer.php'; ?>
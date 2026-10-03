<?php
require_once 'includes/functions.php';
requireAdmin();
require_once 'includes/header.php';

$search = $_GET['search'] ?? '';
$category_id = $_GET['category_id'] ?? '';
$filter = $_GET['filter'] ?? '';

if ($filter == 'low_stock') {
    $products = getLowStockProducts();
} else {
    $products = getProducts($search, $category_id);
}
$categories = getCategories();

// Handle delete
if (isset($_GET['delete']) && isAdmin()) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    setFlash('success', 'Produk berhasil dihapus!');
    redirect('products.php');
}
?>

<div class="page-header">
    <h1>Manajemen Barang</h1>
    <a href="product_form.php" class="btn btn-primary">+ Tambah Barang</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="search" placeholder="Cari nama / barcode..." 
                           value="<?= htmlspecialchars($search) ?>" class="form-control">
                </div>
                <div class="form-group">
                    <select name="category_id" class="form-control">
                        <option value="">Semua Kategori</option>
                        <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Barcode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Expired</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($products) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($products)): 
                        $stockClass = $row['stock'] <= $row['min_stock'] ? 'badge-danger' : 'badge-success';
                    ?>
                    <tr>
                        <td><code><?= htmlspecialchars($row['barcode'] ?: '-') ?></code></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($row['category_name'] ?? '-') ?></span></td>
                        <td><?= formatRupiah($row['buy_price']) ?></td>
                        <td><?= formatRupiah($row['sell_price']) ?></td>
                        <td><span class="badge <?= $stockClass ?>"><?= $row['stock'] ?></span></td>
                        <td><?= $row['unit'] ?></td>
                        <td><?= $row['expired_date'] ?: '-' ?></td>
                        <td class="table-actions">
                            <a href="product_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <?php if (isAdmin()): ?>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Hapus produk ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">Tidak ada data barang</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
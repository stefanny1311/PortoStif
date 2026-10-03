<?php
require_once 'includes/functions.php';
requireAdmin();
require_once 'includes/header.php';

$id = $_GET['id'] ?? null;
$is_edit = $id !== null;
$product = null;

if ($is_edit) {
    $product = getProductById($id);
    if (!$product) {
        setFlash('error', 'Produk tidak ditemukan!');
        redirect('products.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barcode = cleanInput($_POST['barcode']);
    $name = cleanInput($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $buy_price = (float)$_POST['buy_price'];
    $sell_price = (float)$_POST['sell_price'];
    $stock = (float)$_POST['stock'];
    $min_stock = (float)$_POST['min_stock'];
    $unit = cleanInput($_POST['unit']);
    $expired_date = $_POST['expired_date'] ? "'" . cleanInput($_POST['expired_date']) . "'" : "NULL";

    if ($is_edit) {
        $sql = "UPDATE products SET 
            barcode = '$barcode', name = '$name', category_id = $category_id,
            buy_price = $buy_price, sell_price = $sell_price, stock = $stock,
            min_stock = $min_stock, unit = '$unit', expired_date = $expired_date
            WHERE id = $id";
    } else {
        $sql = "INSERT INTO products (barcode, name, category_id, buy_price, sell_price, stock, min_stock, unit, expired_date) 
                VALUES ('$barcode', '$name', $category_id, $buy_price, $sell_price, $stock, $min_stock, '$unit', $expired_date)";
    }

    if (mysqli_query($conn, $sql)) {
        setFlash('success', $is_edit ? 'Produk berhasil diupdate!' : 'Produk berhasil ditambahkan!');
        redirect('products.php');
    } else {
        setFlash('error', 'Gagal menyimpan produk: ' . mysqli_error($conn));
    }
}

$categories = getCategories();
?>

<div class="page-header">
    <h1><?= $is_edit ? 'Edit Barang' : 'Tambah Barang' ?></h1>
    <a href="products.php" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" class="form">
            <div class="form-row">
                <div class="form-group">
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="form-control" 
                           value="<?= htmlspecialchars($product['barcode'] ?? '') ?>" 
                           placeholder="Scan / ketik barcode">
                </div>
                <div class="form-group">
                    <label>Nama Barang *</label>
                    <input type="text" name="name" class="form-control" required
                           value="<?= htmlspecialchars($product['name'] ?? '') ?>" 
                           placeholder="Nama barang">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category_id" class="form-control">
                        <option value="">Pilih Kategori</option>
                        <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $cat['id'] ?>" 
                            <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="unit" class="form-control" 
                           value="<?= htmlspecialchars($product['unit'] ?? 'pcs') ?>" 
                           placeholder="pcs / kg / dus / liter">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Harga Beli (Rp)</label>
                    <input type="number" name="buy_price" class="form-control" 
                           value="<?= $product['buy_price'] ?? 0 ?>" step="1" min="0">
                </div>
                <div class="form-group">
                    <label>Harga Jual (Rp) *</label>
                    <input type="number" name="sell_price" class="form-control" required
                           value="<?= $product['sell_price'] ?? 0 ?>" step="1" min="0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stock" class="form-control" 
                           value="<?= $product['stock'] ?? 0 ?>" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Stok Minimum (Alert)</label>
                    <input type="number" name="min_stock" class="form-control" 
                           value="<?= $product['min_stock'] ?? 5 ?>" step="0.01" min="0">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Kadaluarsa</label>
                    <input type="date" name="expired_date" class="form-control" 
                           value="<?= $product['expired_date'] ?? '' ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="products.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
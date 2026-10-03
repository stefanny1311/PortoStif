<?php
require_once 'includes/functions.php';
requireAdmin();
require_once 'includes/header.php';

$categories = getCategories();

// Handle add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = cleanInput($_POST['name']);
    $slug = cleanInput(strtolower(str_replace(' ', '-', $_POST['name'])));
    mysqli_query($conn, "INSERT INTO categories (name, slug) VALUES ('$name', '$slug')");
    setFlash('success', 'Kategori berhasil ditambahkan!');
    redirect('categories.php');
}

// Handle edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = (int)$_POST['id'];
    $name = cleanInput($_POST['name']);
    $slug = cleanInput(strtolower(str_replace(' ', '-', $_POST['name'])));
    mysqli_query($conn, "UPDATE categories SET name = '$name', slug = '$slug' WHERE id = $id");
    setFlash('success', 'Kategori berhasil diupdate!');
    redirect('categories.php');
}

// Handle delete
if (isset($_GET['delete']) && isAdmin()) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
    setFlash('success', 'Kategori berhasil dihapus!');
    redirect('categories.php');
}

$edit_category = null;
if (isset($_GET['edit'])) {
    $edit_category = getCategoryById((int)$_GET['edit']);
}
?>

<div class="page-header">
    <h1>Manajemen Kategori</h1>
</div>

<div class="two-col">
    <div class="card">
        <div class="card-header">
            <h3><?= $edit_category ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
        </div>
        <div class="card-body">
            <form method="POST" class="form">
                <input type="hidden" name="action" value="<?= $edit_category ? 'edit' : 'add' ?>">
                <?php if ($edit_category): ?>
                <input type="hidden" name="id" value="<?= $edit_category['id'] ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="name" class="form-control" required 
                           value="<?= htmlspecialchars($edit_category['name'] ?? '') ?>" 
                           placeholder="Nama kategori">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <?php if ($edit_category): ?>
                <a href="categories.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Daftar Kategori</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($categories) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($categories)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><code><?= $row['slug'] ?></code></td>
                            <td class="table-actions">
                                <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <?php if (isAdmin()): ?>
                                <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada kategori</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
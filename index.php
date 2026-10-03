<?php
require_once 'includes/header.php';

$today_sales = getTodaySales();
$today_transactions = getTodayTransactionsCount();
$total_products = getTotalProducts();
$total_customers = getTotalCustomers();
$total_debt = getTotalDebt();
$low_stock = getLowStockProducts();
$expiring = getExpiringProducts(30);
$best_sellers = getBestSellingProducts(date('Y-m-01'), date('Y-m-d'), 5);
?>

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Ringkasan operasional toko hari ini</p>
</div>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">Rp</div>
        <div class="stat-info">
            <span class="stat-label">Penjualan Hari Ini</span>
            <span class="stat-value"><?= formatRupiah($today_sales) ?></span>
        </div>
    </div>
    <div class="stat-card stat-success">
        <div class="stat-icon">=</div>
        <div class="stat-info">
            <span class="stat-label">Transaksi Hari Ini</span>
            <span class="stat-value"><?= $today_transactions ?> Transaksi</span>
        </div>
    </div>
    <div class="stat-card stat-info">
        <div class="stat-icon">[]</div>
        <div class="stat-info">
            <span class="stat-label">Total Produk</span>
            <span class="stat-value"><?= $total_products ?> Item</span>
        </div>
    </div>
    <div class="stat-card stat-warning">
        <div class="stat-icon">(i)</div>
        <div class="stat-info">
            <span class="stat-label">Pelanggan</span>
            <span class="stat-value"><?= $total_customers ?> Orang</span>
        </div>
    </div>
    <div class="stat-card stat-danger">
        <div class="stat-icon">$</div>
        <div class="stat-info">
            <span class="stat-label">Total Piutang</span>
            <span class="stat-value"><?= formatRupiah($total_debt) ?></span>
        </div>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Stok Menipis -->
    <div class="card">
        <div class="card-header">
            <h3>Stok Menipis</h3>
            <a href="products.php?filter=low_stock" class="btn btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body">
            <?php if (mysqli_num_rows($low_stock) > 0): ?>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Stok</th>
                        <th>Min</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($low_stock)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><span class="badge badge-danger"><?= $row['stock'] ?></span></td>
                        <td><?= $row['min_stock'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-muted">Tidak ada stok menipis</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Kadaluarsa -->
    <div class="card">
        <div class="card-header">
            <h3>Mendekati Kadaluarsa (30 Hari)</h3>
        </div>
        <div class="card-body">
            <?php if (mysqli_num_rows($expiring) > 0): ?>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Expired</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($expiring)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><span class="badge badge-warning"><?= $row['expired_date'] ?></span></td>
                        <td><?= $row['stock'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-muted">Tidak ada produk mendekati kadaluarsa</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Best Seller -->
    <div class="card card-full">
        <div class="card-header">
            <h3>Produk Terlaris Bulan Ini</h3>
        </div>
        <div class="card-body">
            <?php if (mysqli_num_rows($best_sellers) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Terjual</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($best_sellers)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['total_qty'] ?></td>
                        <td><?= formatRupiah($row['total_sales']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-muted">Belum ada data penjualan bulan ini</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
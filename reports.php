<?php
require_once 'includes/functions.php';
requireAdmin();
require_once 'includes/header.php';

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

$sales_report = getSalesReport($start_date, $end_date);
$profit = getProfitSummary($start_date, $end_date);
$best_sellers = getBestSellingProducts($start_date, $end_date, 10);
$profit_row = mysqli_fetch_assoc($profit);
?>

<div class="page-header">
    <h1>Laporan Keuangan</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                </div>
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Profit Summary -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">Rp</div>
        <div class="stat-info">
            <span class="stat-label">Total Pendapatan</span>
            <span class="stat-value"><?= formatRupiah($profit_row['total_pendapatan']) ?></span>
        </div>
    </div>
    <div class="stat-card stat-info">
        <div class="stat-icon">H</div>
        <div class="stat-info">
            <span class="stat-label">Total Modal (HPP)</span>
            <span class="stat-value"><?= formatRupiah($profit_row['total_modal']) ?></span>
        </div>
    </div>
    <div class="stat-card stat-success">
        <div class="stat-icon">+</div>
        <div class="stat-info">
            <span class="stat-label">Laba Kotor</span>
            <span class="stat-value"><?= formatRupiah($profit_row['laba_kotor']) ?></span>
        </div>
    </div>
</div>

<div class="two-col">
    <!-- Daily Sales -->
    <div class="card">
        <div class="card-header">
            <h3>Penjualan Harian</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $chart_labels = [];
                    $chart_data = [];
                    if (mysqli_num_rows($sales_report) > 0): 
                        while ($row = mysqli_fetch_assoc($sales_report)):
                            $chart_labels[] = date('d/m', strtotime($row['tanggal']));
                            $chart_data[] = $row['total_penjualan'];
                    ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        <td><?= $row['jumlah_transaksi'] ?></td>
                        <td><strong><?= formatRupiah($row['total_penjualan']) ?></strong></td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Tidak ada data penjualan</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Best Sellers -->
    <div class="card">
        <div class="card-header">
            <h3>10 Produk Terlaris</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($best_sellers) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($best_sellers)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= $row['total_qty'] ?></td>
                            <td><strong><?= formatRupiah($row['total_sales']) ?></strong></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Simple Chart Visualization -->
<?php if (!empty($chart_labels)): ?>
<div class="card">
    <div class="card-header">
        <h3>Grafik Penjualan</h3>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <div class="bar-chart">
                <?php 
                $max_val = max($chart_data);
                foreach ($chart_labels as $i => $label): 
                    $height = $max_val > 0 ? ($chart_data[$i] / $max_val * 100) : 0;
                ?>
                <div class="bar-item">
                    <div class="bar-value"><?= formatRupiah($chart_data[$i]) ?></div>
                    <div class="bar" style="height: <?= $height ?>%">
                        <div class="bar-fill"></div>
                    </div>
                    <div class="bar-label"><?= $label ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
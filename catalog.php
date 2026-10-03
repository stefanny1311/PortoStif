<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$search = $_GET['search'] ?? '';
$category_id = $_GET['category_id'] ?? '';

$products = getProducts($search, $category_id);
$categories = getCategories();

// Prepare WhatsApp message for checkout
if (isset($_GET['checkout'])) {
    $checkout_items = json_decode($_GET['checkout'], true);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog - Toko Klontongan</title>
    <link rel="stylesheet" href="assets/css/catalog.css">
</head>
<body>
    <!-- Header -->
    <header class="catalog-header">
        <div class="container">
            <div class="header-top">
                <a href="catalog.php" class="logo">[TK] Toko Klontongan</a>
                <div class="header-actions">
                    <a href="login.php" class="btn btn-outline">Login Kasir</a>
                    <button class="btn btn-cart" onclick="toggleCart()">
                        Cart <span id="cartCount">0</span>
                    </button>
                </div>
            </div>
            <form method="GET" class="header-search">
                <input type="text" name="search" placeholder="Cari barang..." 
                       value="<?= htmlspecialchars($search) ?>" class="search-input">
            </form>
        </div>
    </header>

    <!-- Category Nav -->
    <nav class="category-nav">
        <div class="container">
            <a href="catalog.php" class="cat-item <?= !$category_id ? 'active' : '' ?>">Semua</a>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
            <a href="?category_id=<?= $cat['id'] ?>" 
               class="cat-item <?= $category_id == $cat['id'] ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
            <?php endwhile; ?>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <div class="catalog-layout">
            <!-- Product Grid -->
            <div class="product-grid">
                <?php if (mysqli_num_rows($products) > 0): ?>
                    <?php while ($p = mysqli_fetch_assoc($products)): 
                        $out_of_stock = $p['stock'] <= 0;
                    ?>
                    <div class="product-card <?= $out_of_stock ? 'out-of-stock' : '' ?>">
                        <div class="product-image">
                            <?php if ($p['image']): ?>
                            <img src="uploads/<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                            <?php else: ?>
                            <div class="product-image-placeholder">[Item]</div>
                            <?php endif; ?>
                            <?php if ($out_of_stock): ?>
                            <div class="stock-badge sold-out">Habis</div>
                            <?php elseif ($p['stock'] <= $p['min_stock']): ?>
                            <div class="stock-badge low-stock">Stok Terbatas</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <span class="product-category"><?= htmlspecialchars($p['category_name'] ?? 'Umum') ?></span>
                            <h3 class="product-name"><?= htmlspecialchars($p['name']) ?></h3>
                            <div class="product-price"><?= formatRupiah($p['sell_price']) ?></div>
                            <div class="product-stock">Stok: <?= $p['stock'] ?> <?= $p['unit'] ?></div>
                            <?php if (!$out_of_stock): ?>
                            <button class="btn btn-primary btn-block btn-sm" 
                                    onclick="addToCart(<?= $p['id'] ?>, '<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>', <?= $p['sell_price'] ?>, <?= $p['stock'] ?>)">
                                + Keranjang
                            </button>
                            <?php else: ?>
                            <button class="btn btn-disabled btn-block btn-sm" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h2>---</h2>
                        <p>Tidak ada produk ditemukan</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Cart Sidebar -->
            <div class="cart-sidebar" id="cartSidebar">
                <div class="cart-panel">
                    <div class="cart-header">
                        <h3>Keranjang</h3>
                        <button class="cart-close" onclick="toggleCart()">x</button>
                    </div>
                    <div class="cart-body" id="cartBody">
                        <p class="text-muted text-center">Keranjang kosong</p>
                    </div>
                    <div class="cart-footer" id="cartFooter" style="display:none;">
                        <div class="cart-total">
                            <span>Total</span>
                            <strong id="cartTotalAmount">Rp 0</strong>
                        </div>
                        <div class="customer-info">
                            <label>Nama Anda</label>
                            <input type="text" id="customerName" class="form-control" placeholder="Nama pemesan">
                        </div>
                        <div class="order-method">
                            <label>Metode Pengambilan</label>
                            <select id="orderMethod" class="form-control">
                                <option value="pickup">Ambil di Toko (Pick-up)</option>
                                <option value="delivery">Diantar (Delivery)</option>
                            </select>
                        </div>
                        <div id="deliveryAddress" style="display:none;">
                            <label>Alamat Pengantaran</label>
                            <textarea id="address" class="form-control" rows="2" placeholder="Alamat lengkap..."></textarea>
                        </div>
                        <button class="btn btn-success btn-block" onclick="checkoutWhatsApp()">
                            Pesan via WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="catalog-footer">
        <div class="container">
            <p>Toko Klontongan - Buka Setiap Hari 07:00 - 22:00</p>
            <p>Jl. Contoh No. 123, Kota Anda | 0812-3456-7890</p>
        </div>
    </footer>

    <script>
        let cart = [];

        function addToCart(id, name, price, stock) {
            let existing = cart.find(item => item.id === id);
            if (existing) {
                if (existing.qty >= stock) {
                    alert('Stok tidak mencukupi!');
                    return;
                }
                existing.qty++;
            } else {
                cart.push({id, name, price, qty: 1, stock});
            }
            renderCart();
            document.getElementById('cartSidebar').classList.add('open');
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function updateQty(index, delta) {
            let item = cart[index];
            let newQty = item.qty + delta;
            if (newQty < 1) {
                removeFromCart(index);
                return;
            }
            if (newQty > item.stock) {
                alert('Stok tidak mencukupi!');
                return;
            }
            item.qty = newQty;
            renderCart();
        }

        function renderCart() {
            let body = document.getElementById('cartBody');
            let footer = document.getElementById('cartFooter');
            let count = document.getElementById('cartCount');

            count.innerText = cart.length;

            if (cart.length === 0) {
                body.innerHTML = '<p class="text-muted text-center">Keranjang kosong</p>';
                footer.style.display = 'none';
            } else {
                let html = '';
                cart.forEach((item, index) => {
                    html += `<div class="cart-item">
                        <div class="cart-item-info">
                            <strong>${item.name}</strong>
                            <small>${formatRupiah(item.price)} x ${item.qty}</small>
                            <small>${formatRupiah(item.price * item.qty)}</small>
                        </div>
                        <div class="cart-item-actions">
                            <button class="btn btn-xs" onclick="updateQty(${index}, -1)">-</button>
                            <span>${item.qty}</span>
                            <button class="btn btn-xs" onclick="updateQty(${index}, 1)">+</button>
                            <button class="btn btn-xs btn-danger" onclick="removeFromCart(${index})">x</button>
                        </div>
                    </div>`;
                });
                body.innerHTML = html;

                let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                document.getElementById('cartTotalAmount').innerText = formatRupiah(total);
                footer.style.display = 'block';
            }
        }

        function toggleCart() {
            document.getElementById('cartSidebar').classList.toggle('open');
        }

        function checkoutWhatsApp() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }
            
            let name = document.getElementById('customerName').value || 'Pelanggan';
            let method = document.getElementById('orderMethod').value;
            let address = document.getElementById('address').value || '-';
            let total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            let message = `*Halo Toko Klontongan!*\n\n`;
            message += `Saya ingin memesan:\n\n`;
            cart.forEach((item, index) => {
                message += `${index + 1}. *${item.name}* - ${item.qty}pcs (${formatRupiah(item.price * item.qty)})\n`;
            });
            message += `\n*Total: ${formatRupiah(total)}*\n\n`;
            message += `*Nama:* ${name}\n`;
            message += `*Metode:* ${method === 'pickup' ? 'Ambil di Toko' : 'Diantar'}\n`;
            if (method === 'delivery') {
                message += `*Alamat:* ${address}\n`;
            }

            let waNumber = '6281234567890'; // Ganti dengan nomor WhatsApp toko
            let url = `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }

        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        // Show/hide address field based on order method
        document.getElementById('orderMethod')?.addEventListener('change', function() {
            document.getElementById('deliveryAddress').style.display = 
                this.value === 'delivery' ? 'block' : 'none';
        });

        // Initialize
        renderCart();
    </script>
</body>
</html>
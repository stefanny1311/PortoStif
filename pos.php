<?php
require_once 'includes/header.php';

$categories = getCategories();

// Handle transaction submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'checkout') {
    $cart_items = json_decode($_POST['cart_items'], true);
    $payment_method = cleanInput($_POST['payment_method']);
    $customer_id = $_POST['customer_id'] ? (int)$_POST['customer_id'] : null;
    $payment_amount = (float)$_POST['payment_amount'];
    $discount = (float)$_POST['discount'];
    $notes = cleanInput($_POST['notes'] ?? '');

    if (empty($cart_items)) {
        setFlash('error', 'Keranjang belanja kosong!');
        redirect('pos.php');
    }

    mysqli_begin_transaction($conn);
    try {
        $total_amount = 0;
        foreach ($cart_items as $item) {
            $subtotal = $item['qty'] * $item['sell_price'];
            $total_amount += $subtotal;
        }

        $grand_total = $total_amount - $discount;
        if ($grand_total < 0) $grand_total = 0;

        $payment_status = 'lunas';
        $change_amount = 0;

        if ($customer_id && $payment_method == 'kasbon') {
            $customer = getCustomerById($customer_id);
            $new_debt = $customer['current_debt'] + $grand_total;
            if ($new_debt > $customer['max_debt_limit'] && $customer['max_debt_limit'] > 0) {
                throw new Exception('Batas bon pelanggan terlampaui! Maks: ' . formatRupiah($customer['max_debt_limit']));
            }
            $change_amount = 0;
        } elseif ($payment_method == 'cash') {
            if ($payment_amount < $grand_total) {
                throw new Exception('Pembayaran kurang!');
            }
            $change_amount = $payment_amount - $grand_total;
        } else {
            $payment_amount = $grand_total;
            $change_amount = 0;
        }

        $invoice_no = generateInvoiceNo();
        $user_id = $_SESSION['user_id'];

        $sql = "INSERT INTO transactions (invoice_no, user_id, customer_id, total_amount, discount, grand_total, 
                payment_amount, change_amount, payment_method, payment_status, notes) 
                VALUES ('$invoice_no', $user_id, " . ($customer_id ? $customer_id : "NULL") . ", 
                $total_amount, $discount, $grand_total, $payment_amount, $change_amount, '$payment_method', '$payment_status', '$notes')";
        mysqli_query($conn, $sql);
        $transaction_id = mysqli_insert_id($conn);

        foreach ($cart_items as $item) {
            $product_id = (int)$item['product_id'];
            $qty = (float)$item['qty'];
            $buy_price = (float)$item['buy_price'];
            $sell_price = (float)$item['sell_price'];
            $subtotal = $qty * $sell_price;

            mysqli_query($conn, "INSERT INTO transaction_details (transaction_id, product_id, qty, buy_price, sell_price, subtotal) 
                VALUES ($transaction_id, $product_id, $qty, $buy_price, $sell_price, $subtotal)");

            mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = $product_id");
        }

        if ($customer_id && $payment_method == 'kasbon') {
            mysqli_query($conn, "UPDATE customers SET current_debt = current_debt + $grand_total WHERE id = $customer_id");
        }

        mysqli_commit($conn);

        $_SESSION['last_transaction_id'] = $transaction_id;
        setFlash('success', "Transaksi berhasil! Invoice: $invoice_no");
        redirect('pos.php?receipt=' . $transaction_id);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        setFlash('error', 'Transaksi gagal: ' . $e->getMessage());
        redirect('pos.php');
    }
}

// Get products for search
$search_result = null;
if (isset($_GET['search_product']) && $_GET['search_product']) {
    $q = cleanInput($_GET['search_product']);
    $sql = "SELECT * FROM products WHERE barcode = '$q' OR name LIKE '%$q%' LIMIT 10";
    $search_result = mysqli_query($conn, $sql);
}
?>

<div class="page-header">
    <h1>Kasir (Point of Sale)</h1>
</div>

<div class="pos-layout">
    <!-- Product Selection Panel -->
    <div class="pos-products-panel">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="pos-search">
                    <input type="text" name="search_product" id="searchProduct" 
                           class="form-control form-control-lg" 
                           placeholder="Scan barcode atau cari barang..." autofocus
                           value="<?= htmlspecialchars($_GET['search_product'] ?? '') ?>">
                </form>
            </div>
        </div>

        <!-- Search Results -->
        <?php if ($search_result && mysqli_num_rows($search_result) > 0): ?>
        <div class="card">
            <div class="card-header"><h3>Hasil Pencarian</h3></div>
            <div class="card-body">
                <div class="product-grid-mini">
                    <?php while ($p = mysqli_fetch_assoc($search_result)): ?>
                    <div class="product-card-mini" onclick="addToCart(<?= $p['id'] ?>, '<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>', <?= $p['sell_price'] ?>, <?= $p['buy_price'] ?>, <?= $p['stock'] ?>)">
                        <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                        <div class="product-price"><?= formatRupiah($p['sell_price']) ?></div>
                        <div class="product-stock">Stok: <?= $p['stock'] ?></div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Product Grid by Category -->
        <?php 
        mysqli_data_seek($categories, 0);
        while ($cat = mysqli_fetch_assoc($categories)): 
            $cat_products = getProducts('', $cat['id']);
            if (mysqli_num_rows($cat_products) > 0):
        ?>
        <div class="card">
            <div class="card-header collapsible" onclick="toggleCategory(this)">
                <h3><?= htmlspecialchars($cat['name']) ?></h3>
                <span class="toggle-icon">v</span>
            </div>
            <div class="card-body category-body">
                <div class="product-grid-mini">
                    <?php while ($p = mysqli_fetch_assoc($cat_products)): ?>
                    <div class="product-card-mini <?= $p['stock'] <= 0 ? 'out-of-stock' : '' ?>" 
                         onclick="addToCart(<?= $p['id'] ?>, '<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>', <?= $p['sell_price'] ?>, <?= $p['buy_price'] ?>, <?= $p['stock'] ?>)">
                        <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                        <div class="product-price"><?= formatRupiah($p['sell_price']) ?></div>
                        <div class="product-stock <?= $p['stock'] <= $p['min_stock'] ? 'text-danger' : '' ?>">
                            Stok: <?= $p['stock'] ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php 
            endif;
        endwhile; 
        ?>
    </div>

    <!-- Cart Panel -->
    <div class="pos-cart-panel">
        <div class="card pos-cart-card">
            <div class="card-header">
                <h3>Keranjang Belanja</h3>
                <button class="btn btn-sm btn-danger" onclick="clearCart()">Kosongkan</button>
            </div>
            <div class="card-body" id="cartContainer">
                <div id="cartItems">
                    <p class="text-muted text-center">Keranjang kosong</p>
                </div>
                <div id="cartSummary" style="display:none;">
                    <hr>
                    <div class="cart-total-row">
                        <span>Total</span>
                        <strong id="cartTotal">Rp 0</strong>
                    </div>
                    <div class="cart-total-row">
                        <span>Diskon</span>
                        <input type="number" id="cartDiscount" class="form-control form-control-sm" 
                               value="0" min="0" style="width:120px;text-align:right;" 
                               onchange="updateCartSummary()" onkeyup="updateCartSummary()">
                    </div>
                    <div class="cart-total-row cart-grand-total">
                        <span>Grand Total</span>
                        <strong id="cartGrandTotal">Rp 0</strong>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <form method="POST" id="checkoutForm" onsubmit="return prepareCheckout()">
                    <input type="hidden" name="action" value="checkout">
                    <input type="hidden" name="cart_items" id="cartItemsInput">
                    
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="payment_method" id="paymentMethod" class="form-control" 
                                onchange="togglePaymentFields()">
                            <option value="cash">[Rp] Tunai</option>
                            <option value="qris">[Q] QRIS</option>
                            <option value="transfer">[T] Transfer</option>
                            <option value="kasbon">[B] Kasbon / Bon</option>
                        </select>
                    </div>

                    <div id="customerField" style="display:none;">
                        <div class="form-group">
                            <label>Pelanggan (Bon)</label>
                            <select name="customer_id" class="form-control">
                                <option value="">Pilih pelanggan...</option>
                                <?php 
                                $cust_result = mysqli_query($conn, "SELECT * FROM customers ORDER BY name");
                                while ($c = mysqli_fetch_assoc($cust_result)): 
                                ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars($c['name']) ?> (Sisa: <?= formatRupiah($c['max_debt_limit'] - $c['current_debt']) ?>)
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div id="cashField">
                        <div class="form-group">
                            <label>Jumlah Bayar (Rp)</label>
                            <input type="number" name="payment_amount" id="paymentAmount" 
                                   class="form-control" value="0" min="0">
                        </div>
                        <div class="form-group">
                            <label>Kembalian</label>
                            <input type="text" id="changeAmount" class="form-control" readonly 
                                   value="Rp 0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Diskon (Rp)</label>
                        <input type="number" name="discount" id="discountInput" 
                               class="form-control" value="0" min="0" 
                               onchange="updateCartSummary()" onkeyup="updateCartSummary()">
                    </div>

                    <div class="form-group">
                        <label>Catatan</label>
                        <input type="text" name="notes" class="form-control" 
                               placeholder="Catatan transaksi...">
                    </div>

                    <button type="submit" class="btn btn-success btn-block btn-lg" id="checkoutBtn">
                        Bayar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<?php if (isset($_GET['receipt'])): 
    $receipt_id = (int)$_GET['receipt'];
    $transaction = getTransactionById($receipt_id);
    $details = getTransactionDetails($receipt_id);
    if ($transaction):
?>
<div class="modal" id="receiptModal" style="display:flex;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Struk Pembayaran</h3>
            <button class="modal-close" onclick="document.getElementById('receiptModal').style.display='none'; window.location='pos.php'">x</button>
        </div>
        <div class="modal-body" id="receiptContent">
            <div class="receipt">
                <div class="receipt-header">
                    <h2>Toko Klontongan</h2>
                    <p><?= $transaction['invoice_no'] ?></p>
                    <p><?= formatTanggal($transaction['created_at']) ?></p>
                    <p>Kasir: <?= htmlspecialchars($transaction['user_name']) ?></p>
                    <?php if ($transaction['customer_name']): ?>
                    <p>Pelanggan: <?= htmlspecialchars($transaction['customer_name']) ?></p>
                    <?php endif; ?>
                    <hr>
                </div>
                <table class="receipt-table">
                    <thead>
                        <tr><th>Barang</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($d = mysqli_fetch_assoc($details)): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['product_name']) ?></td>
                            <td><?= $d['qty'] ?></td>
                            <td><?= formatRupiah($d['sell_price']) ?></td>
                            <td><?= formatRupiah($d['subtotal']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <hr>
                <div class="receipt-summary">
                    <div class="receipt-row">
                        <span>Total</span><span><?= formatRupiah($transaction['total_amount']) ?></span>
                    </div>
                    <div class="receipt-row">
                        <span>Diskon</span><span><?= formatRupiah($transaction['discount']) ?></span>
                    </div>
                    <div class="receipt-row receipt-total">
                        <span>Grand Total</span><span><?= formatRupiah($transaction['grand_total']) ?></span>
                    </div>
                    <div class="receipt-row">
                        <span>Bayar</span><span><?= formatRupiah($transaction['payment_amount']) ?></span>
                    </div>
                    <div class="receipt-row">
                        <span>Kembalian</span><span><?= formatRupiah($transaction['change_amount']) ?></span>
                    </div>
                    <div class="receipt-row">
                        <span>Metode</span><span><?= strtoupper($transaction['payment_method']) ?></span>
                    </div>
                    <div class="receipt-row">
                        <span>Status</span><span><?= strtoupper($transaction['payment_status']) ?></span>
                    </div>
                </div>
                <hr>
                <p class="text-center">Terima kasih!</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="printReceipt()">Cetak Struk</button>
            <button class="btn btn-primary" onclick="document.getElementById('receiptModal').style.display='none'; window.location='pos.php'">Tutup</button>
        </div>
    </div>
</div>
<?php endif; endif; ?>

<script>
// Cart management
let cart = [];

function addToCart(id, name, sellPrice, buyPrice, stock) {
    if (stock <= 0) {
        alert('Stok habis!');
        return;
    }

    let existing = cart.find(item => item.product_id === id);
    if (existing) {
        if (existing.qty >= stock) {
            alert('Stok tidak mencukupi!');
            return;
        }
        existing.qty++;
    } else {
        cart.push({
            product_id: id,
            name: name,
            sell_price: sellPrice,
            buy_price: buyPrice,
            qty: 1,
            stock: stock
        });
    }
    renderCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

function updateQty(index, change) {
    let item = cart[index];
    let newQty = item.qty + change;
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

function clearCart() {
    if (confirm('Kosongkan keranjang?')) {
        cart = [];
        renderCart();
    }
}

function renderCart() {
    let container = document.getElementById('cartItems');
    let summary = document.getElementById('cartSummary');
    
    if (cart.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">Keranjang kosong</p>';
        summary.style.display = 'none';
    } else {
        let html = '<table class="table table-sm cart-table"><thead><tr><th>Item</th><th>Qty</th><th>Harga</th><th></th></tr></thead><tbody>';
        cart.forEach((item, index) => {
            html += `<tr>
                <td>${item.name}</td>
                <td>
                    <button class="btn btn-xs" onclick="updateQty(${index}, -1)">-</button>
                    ${item.qty}
                    <button class="btn btn-xs" onclick="updateQty(${index}, 1)">+</button>
                </td>
                <td>${formatRupiah(item.sell_price)}</td>
                <td><button class="btn btn-xs btn-danger" onclick="removeFromCart(${index})">x</button></td>
            </tr>`;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
        summary.style.display = 'block';
    }
    updateCartSummary();
}

function updateCartSummary() {
    let total = cart.reduce((sum, item) => sum + (item.sell_price * item.qty), 0);
    let discount = parseFloat(document.getElementById('cartDiscount')?.value || 0) || 0;
    let grandTotal = Math.max(0, total - discount);
    
    document.getElementById('cartTotal').innerText = formatRupiah(total);
    document.getElementById('cartGrandTotal').innerText = formatRupiah(grandTotal);
    document.getElementById('discountInput').value = discount;
    
    let paymentAmount = parseFloat(document.getElementById('paymentAmount').value) || 0;
    let change = Math.max(0, paymentAmount - grandTotal);
    document.getElementById('changeAmount').value = formatRupiah(change);
}

function togglePaymentFields() {
    let method = document.getElementById('paymentMethod').value;
    document.getElementById('customerField').style.display = method === 'kasbon' ? 'block' : 'none';
    document.getElementById('cashField').style.display = method === 'cash' ? 'block' : 'none';
}

function prepareCheckout() {
    if (cart.length === 0) {
        alert('Keranjang kosong!');
        return false;
    }
    document.getElementById('cartItemsInput').value = JSON.stringify(cart);
    return true;
}

function toggleCategory(el) {
    let body = el.nextElementSibling;
    body.style.display = body.style.display === 'none' ? 'block' : 'none';
    let icon = el.querySelector('.toggle-icon');
    icon.textContent = body.style.display === 'none' ? '>' : 'v';
}

function printReceipt() {
    let content = document.getElementById('receiptContent').innerHTML;
    let win = window.open('', '', 'width=300,height=600');
    win.document.write('<html><head><title>Struk</title><style>');
    win.document.write('body{font-family:monospace;font-size:12px;padding:10px;width:280px;}');
    win.document.write('h2{text-align:center;margin:0;}p{text-align:center;margin:2px 0;}');
    win.document.write('table{width:100%;border-collapse:collapse;}');
    win.document.write('th,td{text-align:left;padding:2px 0;font-size:11px;}');
    win.document.write('hr{border:none;border-top:1px dashed #000;margin:8px 0;}');
    win.document.write('.receipt-row{display:flex;justify-content:space-between;}');
    win.document.write('.receipt-total{font-weight:bold;font-size:14px;}');
    win.document.write('</style></head><body>');
    win.document.write(content + '</body></html>');
    win.document.close();
    win.print();
}

function formatRupiah(angka) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
}

document.getElementById('paymentAmount')?.addEventListener('keyup', updateCartSummary);
document.getElementById('paymentAmount')?.addEventListener('change', updateCartSummary);

renderCart();
togglePaymentFields();

document.getElementById('searchProduct')?.addEventListener('keyup', function(e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        this.form.submit();
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
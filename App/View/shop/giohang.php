<?php
// Kiểm tra session, nếu chưa start thì start
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Giỏ hàng
$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
$shipping = 30000;

// Tính subtotal
foreach ($cart as $product) {
    $subtotal += $product['price'] * $product['quantity'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Giỏ hàng - 5SV Sport</title>
<style>
/* === CSS giống layout cũ của bạn === */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,sans-serif; background:#f9f9f9; }

.page-header { padding:15px 20px; margin-bottom:50px; }
.header-content { max-width:1400px; margin:0 auto; display:flex; justify-content:center; align-items:center; position:relative; }
.step { display:inline-flex; align-items:center; gap:15px; font-weight:600; font-size:17px; }
.step span { width:40px; height:40px; border-radius:50%; background:#eee; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:16px; }
.step span.active { background:#d60000; color:white; }
.order-history-link { position:absolute; right:20px; top:50%; transform:translateY(-50%); background:#222; color:white; padding:14px 34px; border-radius:50px; text-decoration:none; font-weight:600; font-size:15px; transition:all .3s; box-shadow:0 5px 18px rgba(0,0,0,0.25); }
.order-history-link:hover { background:#d60000; transform:translateY(-50%) scale(1.05); }

.cart-container { max-width:1400px; margin:0 auto 50px; padding:0 20px; display:flex; gap:30px; flex-wrap:wrap; }
.product-box { flex:1; background:white; border-radius:16px; padding:25px; }
.product { position:relative; display:flex; align-items:center; gap:25px; padding:25px 0; border-bottom:10px solid #f8f8f8ff; }
.product:last-child { border-bottom:none; }
.product img { width:160px; height:160px; object-fit:cover; border-radius:14px; border:2px solid #f0f0f0; }
.btn-remove { position: absolute; top: 1px; right: -1px; width: 32px; height: 32px; background: #ff4c3b; color: white; border: none; border-radius: 50%; font-size: 18px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 12px rgba(255,76,59,0.45); transition: all .3s; z-index: 10; }
.btn-remove:hover { background: #e6392a; transform: scale(1.15); }
.product-info h3 { font-size:18px; margin-bottom:10px; }
.product-info p { margin:6px 0; color:#555; font-size:15px; }
.product-info p strong { color:#d60000; }
.qty-box { display:flex; align-items:center; gap:10px; margin-top:15px; }
.qty-box button { width:40px; height:40px; border:1px solid #ddd; background:#fff; font-size:18px; cursor:pointer; border-radius:8px; }
.qty-box input { width:70px; height:40px; text-align:center; border:1px solid #ddd; border-radius:8px; }
.summary-box { width:380px; background:white; border-radius:16px; padding:30px; box-shadow:0 8px 25px rgba(255, 255, 255, 0.08); height:fit-content; position:sticky; top:20px; }
.summary-box h3 { text-align:center; margin-bottom:20px; font-size:20px; }
.summary-box p { display:flex; justify-content:space-between; margin:15px 0; }
.summary-box hr { border:none; border-top:2px dashed #ddd; margin:25px 0; }
.btn-checkout { display:block; background:#d60000; color:white; padding:12px 0; text-align:center; border-radius:12px; font-weight:bold; font-size:17px; text-decoration:none; margin-top:20px; transition:.3s; }
.btn-checkout:hover { background:#c40000; }
.empty-cart { text-align:center; padding:80px 20px; color:#999; font-size:20px; }
.empty-cart a { color:#d60000; text-decoration:underline; }
</style>
</head>
<body>

<div class="page-header">
    <div class="header-content">
        <div class="step">
            <span class="active">1</span> Giỏ hàng >
            <span class="">2</span> Thanh toán >
            <span class="">3</span> Hoàn tất đơn hàng
        </div>
        <a href="index.php?page=order_history" class="order-history-link">Lịch sử đơn hàng</a>
        <?php if(isset($_SESSION['username'])): ?>
            <a href="index.php?page=order_history" class="order-history-link">Lịch sử mua hàng</a>
        <?php endif; ?>

        </div>

    </div>
</div>

<div class="cart-container">

    <div class="product-box">
        <?php if(empty($cart)): ?>
            <div class="empty-cart">
                <p>Giỏ hàng trống!</p>
                <p><a href="?page=product">Mua sắm ngay nào!</a></p>
            </div>
        <?php else: ?>
            <?php foreach($cart as $id => $product): ?>
                <div class="product" data-id="<?= htmlspecialchars($id) ?>" data-price="<?= $product['price'] ?>">
                    <img src="App/public/img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <button class="btn-remove" title="Xóa sản phẩm">×</button>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p>Size: <strong><?= $product['size'] ?></strong></p>
                        <p>Giá: <strong><?= number_format($product['price']) ?> ₫</strong></p>
                        <div class="qty-box">
                            <button class="qty-minus">-</button>
                            <input type="number" class="qty-input" value="<?= $product['quantity'] ?>" min="1">
                            <button class="qty-plus">+</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if(!empty($cart)): ?>
    <div class="summary-box">
        <h3>Tổng cộng giỏ hàng</h3>
        <p>Tạm tính: <b id="subtotal"><?= number_format($subtotal) ?> ₫</b></p>
        <p>Vận chuyển: <b><?= number_format($shipping) ?> ₫</b></p>
        <hr>
        <p style="font-size:22px;color:#d60000"><strong>Tổng: <span id="total"><?= number_format($subtotal + $shipping) ?></span></strong></p>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="index.php?page=order" class="btn-checkout">Tiến hành thanh toán</a>
        <?php else: ?>
            <a href="index.php?page=account" class="btn-checkout" onclick="alert('Bạn cần đăng nhập trước khi thanh toán!'); return false;">Đăng nhập để thanh toán</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<script>
const shipping = <?= $shipping ?>;

function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.product').forEach(p => {
        const price = parseInt(p.dataset.price) || 0;
        const qty = parseInt(p.querySelector('.qty-input').value) || 1;
        subtotal += price * qty;
    });
    document.getElementById('subtotal').textContent = subtotal.toLocaleString() + ' ₫';
    document.getElementById('total').textContent = (subtotal + shipping).toLocaleString() + ' ₫';
}

function updateCartOnServer(id, qty) {
    fetch("index.php?page=cart&action=update", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `id=${id}&qty=${qty}`
    });
}

function removeFromCartOnServer(id) {
    fetch("index.php?page=cart&action=remove", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `id=${id}`
    }).then(() => {
        const products = document.querySelectorAll('.product');
        if (products.length === 0) {
            document.querySelector('.empty-cart').style.display = 'block';
            document.querySelector('.summary-box').style.display = 'none';
        }
    });
}

document.querySelectorAll('.product').forEach(p => {
    const id = p.dataset.id;
    const input = p.querySelector('.qty-input');
    p.querySelector('.qty-minus').onclick = () => { if(input.value>1) input.value--; updateTotals(); updateCartOnServer(id, input.value); };
    p.querySelector('.qty-plus').onclick  = () => { input.value++; updateTotals(); updateCartOnServer(id, input.value); };
    input.onchange = () => { if(input.value<1) input.value=1; updateTotals(); updateCartOnServer(id, input.value); };
    p.querySelector('.btn-remove').onclick = () => { p.remove(); updateTotals(); removeFromCartOnServer(id); };
});

updateTotals();
</script>

</body>
</html>

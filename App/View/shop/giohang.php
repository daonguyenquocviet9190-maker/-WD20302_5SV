<?php
$subtotal = 0;
$shipping = 30000;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            .step { display: inline-flex; align-items: center; gap: 10px; ;margin: 30px auto; margin-left: 550px;  margin-bottom: 20px; font-weight: 600; }
        .step span { background: #eee; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .step span.active { background: #d60000; color: white; }
    </style>
</head>
<body>
    

<div class="step">
            <span class="active">1</span> Giỏ hàng >
            <span class="">2</span> Thanh toán >
            <span>3</span> Đơn hàng
        </div>
<div class="cart-container">

    <div class="product-box" style="position: relative;">
        <?php foreach($cart as $id => $product): ?>
            <?php $subtotal += $product['price'] * $product['quantity']; ?>
            <div class="product" data-id="<?= htmlspecialchars($id) ?>" data-price="<?= htmlspecialchars($product['price']) ?>">
                <img src="App/public/img/<?= $product['image'] ?>" alt="">
                <div class="product-info">
                    <h3><?= $product['name'] ?></h3>
                    <p>Size: <?= $product['size'] ?></p>
                    <p>Giá: <b><?= number_format($product['price']) ?> đ</b></p>

                    <div class="qty-box">
                        <button class="qty-minus">-</button>
                        <input type="number" class="qty-input" value="<?= $product['quantity'] ?>" min="1">
                        <button class="qty-plus">+</button>
                    </div>

                    <button class="btn-remove" style="width: 25%;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    background-color: #ff4c3b;
    color: white;
">X</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="summary-box">
        <h3>Tổng cộng giỏ hàng</h3>
        <p>Tạm tính: <b id="subtotal"><?= number_format($subtotal) ?> đ</b></p>
        <p>Vận chuyển: <b id="shipping"><?= number_format($shipping) ?> đ</b></p>
        <hr>
        <p><b>Tổng: <span id="total"><?= number_format($subtotal + $shipping) ?></span> đ</b></p>
        <a href="index.php?page=order" class="btn-checkout">Tiến hành thanh toán</a>
    </div>
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
    document.getElementById('subtotal').textContent = subtotal.toLocaleString() + ' đ';
    document.getElementById('total').textContent = (subtotal + shipping).toLocaleString() + ' đ';
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
    });
}

document.querySelectorAll('.product').forEach(p => {
    const id = p.dataset.id;
    const minusBtn = p.querySelector('.qty-minus');
    const plusBtn = p.querySelector('.qty-plus');
    const qtyInput = p.querySelector('.qty-input');
    const removeBtn = p.querySelector('.btn-remove');

    minusBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if(val > 1) qtyInput.value = val - 1;
        updateTotals();
        updateCartOnServer(id, qtyInput.value);
    });

    plusBtn.addEventListener('click', () => {
        qtyInput.value = parseInt(qtyInput.value) + 1;
        updateTotals();
        updateCartOnServer(id, qtyInput.value);
    });

    qtyInput.addEventListener('change', () => {
        if(qtyInput.value < 1) qtyInput.value = 1;
        updateTotals();
        updateCartOnServer(id, qtyInput.value);
    });

    removeBtn.addEventListener('click', () => {
        p.remove();
        updateTotals();
        removeFromCartOnServer(id);
    });
});

updateTotals();
</script>
</body>
</html>
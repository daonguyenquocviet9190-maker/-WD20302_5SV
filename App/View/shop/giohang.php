<h2>Giỏ hàng</h2>
<div class="cart-container">

    <!-- DANH SÁCH SẢN PHẨM -->
    <div class="product-box">
        <?php
        $subtotal = 0;
        foreach($_SESSION['cart'] as $product):
            $subtotal += $product['price'] * $product['quantity'];
        ?>
        <div class="product">
            <img src="App/public/img/<?= $product['image'] ?>" alt="">
            <div class="product-info">
                <h3><?= $product["name"] ?></h3>
                <p>Size: <?= $product["size"] ?></p>
                <p><b><?= number_format($product["price"]) ?> đ</b></p>

                <div class="qty-box">
    <button class="qty-minus">-</button>
    <input type="number" class="qty-input" value="<?= $product['quantity'] ?>" min="1">
    <button class="qty-plus">+</button>
</div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- TỔNG TIỀN -->
    <?php $shipping = 30000; $total = $subtotal + $shipping; ?>
    <div class="summary-box">
        <h3>Tổng cộng giỏ hàng</h3>
        <p>Tạm tính: <b><?= number_format($subtotal) ?> đ</b></p>
        <p>Vận chuyển: <b><?= number_format($shipping) ?> đ</b></p>
        <hr>
        <p><b>Tổng: <?= number_format($total) ?> đ</b></p>

        <button class="btn-checkout">Tiến hành thanh toán</button>
        <a href="index.php?page=home">Tiếp tục xem sản phẩm</a>
    </div>
</div>



<h2>THƯỜNG MUA CÙNG</h2>
<div class="product-scroll">
    <?php foreach($deal111k as $sp): ?>
        <div class="product-card">
            <div class="product-img-wrapper">
                <img src="App/public/img/<?= $sp['img'] ?>" class="product-img">
                <div class="product-icons">
                    <a href="#" class="icon"><i class="fa fa-heart"></i></a>
                    <a href="#" class="icon"><i class="fa fa-shopping-cart"></i></a>
                    <a href="#" class="icon"><i class="fa fa-search"></i></a>
                </div>

                <?php if($sp['sale_price'] < 200000): ?>
                    <span class="sale-badge">SALE</span>
                <?php endif; ?>
            </div>

            <div class="product-name"><?= $sp['Name'] ?></div>
            <div class="product-price"><del style="color:#8E8E8E; font-size: 15px;"><?= number_format($sp['Price']) ?>₫</del>
             <?= number_format($sp['sale_price']) ?>₫</div>
        </div>
    <?php endforeach; ?>
</div>

<script>
const products = document.querySelectorAll('.product');
const subtotalEl = document.querySelector('.summary-box p:nth-child(2) b'); // Tạm tính
const totalEl = document.querySelector('.summary-box p:nth-child(4) b'); // Tổng
const shipping = 30000;

function updateTotals() {
    let subtotal = 0;
    products.forEach(p => {
        const price = parseInt(p.dataset.price);
        const qty = parseInt(p.querySelector('.qty-input').value);
        subtotal += price * qty;
    });
    subtotalEl.textContent = subtotal.toLocaleString() + ' đ';
    totalEl.textContent = (subtotal + shipping).toLocaleString() + ' đ';
}

// Thêm sự kiện cho nút +
products.forEach(p => {
    const minusBtn = p.querySelector('.qty-minus');
    const plusBtn = p.querySelector('.qty-plus');
    const qtyInput = p.querySelector('.qty-input');

    minusBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if(val > 1) {
            qtyInput.value = val - 1;
            updateTotals();
        }
    });

    plusBtn.addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        qtyInput.value = val + 1;
        updateTotals();
    });

    qtyInput.addEventListener('change', () => {
        if(qtyInput.value < 1) qtyInput.value = 1;
        updateTotals();
    });
});

// Khởi tạo lần đầu
updateTotals();
</script>

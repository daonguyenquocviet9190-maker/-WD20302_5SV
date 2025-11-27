
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Giỏ hàng</title>
<style>
    body { font-family: Arial; padding: 20px; }
    .cart-container { max-width: 900px; margin: auto; display: flex; gap: 30px; }
    .product-box { width: 60%; }
    .summary-box { width: 40%; border-left: 1px solid #ddd; padding-left: 20px; }
    .product { display: flex; gap: 15px; }
    .product img { width: 140px; border-radius: 8px; }
    .qty-box button { padding: 3px 10px; }
    .btn-checkout { background: red; color: #fff; padding: 10px 20px; border: none; width: 100%; margin-top: 10px; }
</style>
</head>
<body>

<h2>Giỏ hàng</h2>

<div class="cart-container">

    <!-- SẢN PHẨM -->
    <div class="product-box">
        <div class="product">
            <img src="<?= $product['image'] ?>" alt="">
            <div>
                <h3><?= $product["name"] ?></h3>
                <p>Màu sắc: <?= $product["color"] ?></p>
                <p>Size: <?= $product["size"] ?></p>
                <p><b><?= number_format($product["price"]) ?> đ</b></p>

                <div class="qty-box">
                    <button>-</button>
                    <?= $product["quantity"] ?>
                    <button>+</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TỔNG TIỀN -->
    <div class="summary-box">
        <h3>Tổng cộng giỏ hàng</h3>

        <p>Tạm tính: <b><?= number_format($subtotal) ?> đ</b></p>
        <p>Vận chuyển: <b><?= number_format($shipping) ?> đ</b></p>
        <hr>
        <p><b>Tổng: <?= number_format($total) ?> đ</b></p>

        <button class="btn-checkout">Tiến hành thanh toán</button>
        <p style="text-align:center; margin-top:10px;">Tiếp tục xem sản phẩm</p>
    </div>
</div>

<h2>SẢN PHẨM LIÊN QUAN</h2>
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
</body>
</html>

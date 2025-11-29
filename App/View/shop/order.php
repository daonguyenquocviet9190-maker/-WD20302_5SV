<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng - 5SV Sport</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 40px auto; display: grid; grid-template-columns: 1fr 420px; gap: 30px; }
        .left, .right { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h2 { font-size: 22px; margin-bottom: 20px; color: #d60000; }
        .step { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 30px; font-weight: 600; }
        .step span { background: #eee; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .step span.active { background: #d60000; color: white; }
        input, select, textarea { width: 100%; padding: 12px; margin: 0px 0 0px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; }
        label { font-weight: 500; color: #333; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

        .order-summary { position: sticky; top: 20px; }
        .product { display: flex; gap: 15px; padding: 20px 0; border-bottom: 1px solid #eee; }
        .product img { width: 90px; height: 90px; object-fit: cover; border-radius: 8px; }
        .product-info h4 { margin: 0; font-size: 16px; }
        .product-info p { margin: 5px 0 0; color: #666; font-size: 14px; }

        .price-row { display: flex; justify-content: space-between; padding: 12px 0; }
        .total { font-size: 20px; font-weight: 700; color: #d60000; border-top: 2px solid #eee; padding-top: 15px; margin-top: 15px; }

        .btn { background: #000; color: white; padding: 16px; text-align: center; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; }
        .btn:hover { background: #333; }

        /* Radio đẹp */
        .payment-section input[type="radio"] {
            position: absolute; opacity: 0;
        }
        .payment-section label {
            display: flex; align-items: center; font-size: 16px;
            font-weight: 500; color: #333; margin: 18px 0;
            cursor: pointer; padding-left: 38px; position: relative;
        }
        .payment-section label::before {
            content: ''; position: absolute; left: 0; top: 50%;
            transform: translateY(-50%); width: 24px; height: 24px;
            border: 2px solid #ccc; border-radius: 50%; transition: .3s;
        }
        .payment-section label::after {
            content: ''; position: absolute; left: 4px; top: 50%;
            transform: translateY(-50%) scale(0); width: 18px; height: 18px;
            background: #d60000; border-radius: 50%; transition: .3s;
        }
        .payment-section input[type="radio"]:checked + label::before {
            border-color: #d60000; box-shadow: 0 0 0 4px rgba(214,0,0,0.1);
        }
        .payment-section input[type="radio"]:checked + label::after {
            transform: translateY(-50%) scale(1);
        }
    </style>
</head>
<body>

<div class="container">

    <!-- CỘT TRÁI - FORM -->
    <div class="left">
        <div class="step">
            <span class="active">1</span> Giỏ hàng >
            <span class="active">2</span> Thanh toán >
            <span>3</span> Đơn hàng
        </div>

        <h2>THÔNG TIN MUA HÀNG</h2>

        <form method="POST">

            <label>Họ và tên *</label>
            <input type="text" name="fullname" required>

            <div class="row">
                <div>
                    <label>Số điện thoại *</label>
                    <input type="text" name="phone" required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
            </div>

            <label>Địa chỉ *</label>
            <input type="text" name="address" required>

            <label>Ghi chú đơn hàng</label>
            <textarea name="note" rows="3"></textarea>

            <div class="payment-section">
                <h2>PHƯƠNG THỨC THANH TOÁN</h2>

                <input type="radio" name="payment" value="cod" id="cod" checked>
                <label for="cod">Thanh toán khi nhận hàng (COD)</label>

                <input type="radio" name="payment" value="bank" id="bank">
                <label for="bank">Chuyển khoản ngân hàng</label>

                <input type="radio" name="payment" value="momo" id="momo">
                <label for="momo">Thanh toán qua Momo</label>
            </div>

            <button class="btn" type="submit" name="submit">ĐẶT HÀNG</button>
        </form>
    </div>

    <!-- CỘT PHẢI → GIỎ HÀNG -->
    <div class="right order-summary">
        <h2>Đơn hàng của bạn</h2>

        <div class="product">
            <img id="cart_img" src="">
            <div class="product-info">
                <h4 id="cart_name"></h4>
                <p id="cart_detail"></p>
            </div>
        </div>

        <div class="price-row"><span>Tạm tính</span> <strong id="cart_subtotal">0đ</strong></div>
        <div class="price-row"><span>Vận chuyển</span> <strong id="cart_ship">10.000đ</strong></div>
        <div class="price-row total"><span>Tổng</span> <strong id="cart_total">0đ</strong></div>
    </div>

</div>

<!-- ================= JS: LẤY GIỎ HÀNG LOCALSTORAGE ================= -->
<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];

if (cart.length > 0) {

    let p = cart[0]; // chỉ demo 1 sản phẩm

    // Hiển thị
    document.getElementById("cart_img").src = p.img;
    document.getElementById("cart_name").textContent = p.name;
    document.getElementById("cart_detail").textContent =
        `Màu sắc: ${p.color} / Size: ${p.size}`;

    let subtotal = p.price * p.quantity;
    let shipping = 10000;
    let total = subtotal + shipping;

    document.getElementById("cart_subtotal").textContent = subtotal.toLocaleString() + "đ";
    document.getElementById("cart_total").textContent = total.toLocaleString() + "đ";

    // Gửi sang php
    document.querySelector("form").innerHTML += `
        <input type="hidden" name="product_name" value="${p.name}">
        <input type="hidden" name="product_price" value="${p.price}">
        <input type="hidden" name="product_size" value="${p.size}">
        <input type="hidden" name="product_color" value="${p.color}">
        <input type="hidden" name="product_quantity" value="${p.quantity}">
        <input type="hidden" name="cart_total" value="${total}">
    `;
}
</script>

<?php
/* ================= XỬ LÝ PHP ================= */
if(isset($_POST['submit'])) {

    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $note = $_POST['note'];

    // Sản phẩm
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $size = $_POST['product_size'];
    $color = $_POST['product_color'];
    $qty = $_POST['product_quantity'];
    $total = $_POST['cart_total'];

    echo "<script>
        alert('Đặt hàng thành công! $fullname đã mua $name. Tổng tiền: $total đ');
    </script>";
}
?>

</body>
</html>

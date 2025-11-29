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

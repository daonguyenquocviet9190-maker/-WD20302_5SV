<?php
session_start();

// Kết nối database
require_once __DIR__ . "/../../Model/database.php";
$db = new Database("localhost", "5svcode", "root", "");
$pdo = $db->connect();

// Nếu giỏ hàng trống thì về trang chủ
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<script>alert('Giỏ hàng trống!'); window.location='index.php';</script>";
    exit;
}

// Tính tổng
$subtotal = 0;
$shipping = 30000;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal + $shipping;

// Khi submit
if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $phone    = $_POST['phone'];
    $email    = $_POST['email'];
    $address  = $_POST['address'];
    $note     = $_POST['note'];
    $payment  = $_POST['payment'];

    // Lưu đơn hàng
    $sql_order = "INSERT INTO donhang (fullname, phone, email, address, note, payment, subtotal, shipping, total, ngay_mua)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql_order);
    $stmt->execute([$fullname, $phone, $email, $address, $note, $payment, $subtotal, $shipping, $total]);
    $order_id = $pdo->lastInsertId();

    // Lưu chi tiết đơn hàng
    foreach ($_SESSION['cart'] as $item) {
        $sql_detail = "INSERT INTO chitiet_donhang (id_dh, id_SP, size, soluong, giamua)
                       VALUES (?, ?, ?, ?, ?)";
        $stmt_detail = $pdo->prepare($sql_detail);
        $stmt_detail->execute([
            $order_id,
            $item['id'],
            $item['size'],
            $item['quantity'],
            $item['price']
        ]);
    }

    unset($_SESSION['cart']);
    header("Location: index.php?page=order_info&id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đặt hàng - 5SV Sport</title>

<style>

.container {
    max-width: 1180px;
    margin: 10px sauto;
    display: flex;
    gap: 30px;
}

.left, .right {
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.07);
}

.left {
    flex: 2;
}

.right {
    flex: 1;
    position: sticky;
    top: 20px;
    height: fit-content;
}

h2 {
    font-size: 24px;
    margin-bottom: 22px;
    font-weight: 600;
}

/* Label */
.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 6px;
    font-size: 15px;
    font-weight: 600;
}

/* INPUT */
input, select, textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #d8d8d8;
    border-radius: 10px;
    font-size: 15px;
    transition: 0.25s;
    background: #fafafa;
}

input:focus, select:focus, textarea:focus {
    border-color: #000;
    background: #fff;
    box-shadow: 0 0 6px rgba(0,0,0,0.12);
    outline: none;
}

/* Nút đặt hàng */
.btn {
    width: 100%;
    background: #f80000ff;
    padding: 15px;
    border-radius: 10px;
    color: #fff;
    font-size: 16px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
    font-weight: 600;
    transition: 0.25s;
}

.btn:hover {
    background: #ff2727ff;
}

/* Sản phẩm bên phải */
.product {
    display: flex;
    gap: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    margin-bottom: 15px;
}

.product img {
    width: 85px;
    height: 85px;
    object-fit: cover;
    border-radius: 10px;
}

.product b {
    font-size: 15px;
}

/* Tổng */
.right p {
    font-size: 15px;
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.right p:last-child {
    font-weight: bold;
    font-size: 18px;
}

hr {
    margin: 18px 0;
    border: none;
    border-top: 1px solid #e5e5e5;
}
</style>

</head>
<body>

<div class="container">

    <!-- FORM -->
    <div class="left">
        <h2>Thông tin mua hàng</h2>

        <form method="POST">
            
            <div class="form-group">
                <label>Họ và tên *</label>
                <input type="text" name="fullname" required>
            </div>

            <div class="form-group">
                <label>Số điện thoại *</label>
                <input type="text" name="phone" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email">
            </div>

            <div class="form-group">
                <label>Địa chỉ *</label>
                <input type="text" name="address" required>
            </div>

            <div class="form-group">
                <label>Ghi chú</label>
                <textarea name="note" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Phương thức thanh toán</label>
                <select name="payment">
                    <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                    <option value="bank">Chuyển khoản ngân hàng</option>
                    <option value="momo">Thanh toán qua Momo</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn">Đặt hàng</button>
        </form>
    </div>

    <!-- GIỎ HÀNG -->
    <div class="right">
        <h2>Đơn hàng của bạn</h2>

        <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="product">
                <img src="App/public/img/<?= $item['image'] ?>">
                <div>
                    <b><?= $item['name'] ?></b><br>
                    Size: <?= $item['size'] ?><br>
                    SL: <?= $item['quantity'] ?><br>
                    Giá: <?= number_format($item['price']) ?> đ
                </div>
            </div>
        <?php endforeach; ?>

        <hr>

        <p><span>Tạm tính:</span><span><?= number_format($subtotal) ?> đ</span></p>
        <p><span>Vận chuyển:</span><span><?= number_format($shipping) ?> đ</span></p>
        <p><span>Tổng cộng:</span><span><?= number_format($total) ?> đ</span></p>
    </div>

</div>

</body>
</html>

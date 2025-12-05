<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../../Model/database.php";

$db = new Database("localhost", "5svcode", "root", "");
$pdo = $db->connect();

// Kiểm tra ID
if (!isset($_GET['id'])) {
    echo "<script>alert('Không tìm thấy ID đơn hàng!'); window.location='index.php';</script>";
    exit;
}

$order_id = intval($_GET['id']);

// Lấy thông tin đơn
$sql_order = "SELECT * FROM donhang WHERE id_dh = ?";
$stmt = $pdo->prepare($sql_order);
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<script>alert('Đơn hàng không tồn tại!'); window.location='index.php';</script>";
    exit;
}

// Lấy chi tiết đơn hàng + JOIN sản phẩm
$sql_items = "
    SELECT c.*, s.Name, s.img 
    FROM chitiet_donhang c 
    JOIN sanpham s ON c.id_SP = s.id_SP
    WHERE c.id_dh = ?
";
$stmt_items = $pdo->prepare($sql_items);
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đơn hàng thành công</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body { font-family: Poppins, Arial; background: #f2f3f7; padding: 25px; }
    .wrapper { max-width: 1150px; margin: 30px auto; display: flex; gap: 25px; }

    /* Cột trái */
    .left-box {
        flex: 2;
        background: #fff;
        padding: 35px;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    }

    .success-icon {
        font-size: 55px;
        color: #28a745;
        margin-bottom: 15px;
    }

    .left-box h2 {
        margin-top: 5px;
        font-size: 25px;
        font-weight: 700;
    }

    .desc { color: #555; margin-bottom: 25px; }

    .section-title {
        margin: 10px 0 15px;
        font-weight: bold;
        font-size: 17px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #444;
    }

    /* Sản phẩm */
    .product-item {
        display: flex;
        gap: 15px;
        background: #fafafa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 12px;
        border: 1px solid #eee;
    }

    .product-item img {
        width: 85px;
        height: 85px;
        object-fit: cover;
        border-radius: 10px;
    }

    .product-info h4 { margin: 0; font-size: 16px; }
    .product-info p { margin: 4px 0; color: #666; font-size: 14px; }

    /* Cột phải */
    .right-box {
        flex: 1.1;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .card {
        background: #fff;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    }

    .card h3 {
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 15px;
    }

    .info p {
        margin: 6px 0;
        color: #444;
        font-size: 15px;
    }

    .btn-buy {
        background: #d90000;
        color: #fff;
        padding: 14px 22px;
        text-align: center;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: block;
        transition: 0.25s;
    }
    .btn-buy:hover { background: #b30000; }

</style>
</head>
<body>

<div class="wrapper">

    <!-- CỘT TRÁI -->
    <div class="left-box">
        <i class="fas fa-check-circle success-icon" style="text-align: center; margin: 5px auto; width:100%;"></i>
        <h2>Mua hàng thành công!</h2>
        <p class="desc">Cảm ơn bạn đã mua sắm tại 5SV Sport. Đơn hàng của bạn đã được ghi nhận.</p>

        <h3 class="section-title"><i class="fas fa-shopping-bag"></i> Sản phẩm đã mua</h3>

        <?php foreach ($items as $item): ?>
        <div class="product-item">
            <img src="App/public/img/<?= $item['img'] ?>" alt="Sản phẩm">
            <div class="product-info">
                <h4><?= $item['Name'] ?></h4>
                <p>Size: <?= $item['size'] ?></p>
                <p>Số lượng: <?= $item['soluong'] ?></p>
                <p>Giá: <b><?= number_format($item['giamua']) ?> đ</b></p>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- CỘT PHẢI -->
    <div class="right-box">

        <div class="card">
            <h3><i class="fas fa-receipt"></i> Thông tin đơn hàng</h3>
            <div class="info">
                <p>Mã đơn hàng: <b>#<?= $order['id_dh'] ?></b></p>
                <p>Ngày đặt: <?= date("d/m/Y - H:i", strtotime($order['ngay_mua'])) ?></p>
                <p>Tổng cộng: <b><?= number_format($order['total']) ?> đ</b></p>
                <p>Thanh toán: 
                    <b>
                    <?php 
                        if ($order['payment'] == 'cod') echo "Thanh toán khi nhận hàng (COD)";
                        elseif ($order['payment'] == 'bank') echo "Chuyển khoản ngân hàng";
                        else echo "Momo";
                    ?>
                    </b>
                </p>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-user"></i> Thông tin người nhận</h3>
            <div class="info">
                <p>Người nhận: <?= $order['fullname'] ?></p>
                <p>Địa chỉ: <?= $order['address'] ?></p>
                <p>Điện thoại: <?= $order['phone'] ?></p>
                <p>Email: <?= $order['email'] ?></p>
            </div>
        </div>

        <a href="index.php?page=home" class="btn-buy">Tiếp tục mua sắm</a>

    </div>

</div>

</body>
</html>

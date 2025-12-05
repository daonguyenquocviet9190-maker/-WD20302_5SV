<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('APP_PATH')) die('No direct access');

// KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem lịch sử đơn hàng'); window.location='index.php?page=account';</script>";
    exit;
}

$id_User = $_SESSION['user_id'];

// KẾT NỐI DB
require_once __DIR__ . "/../../Model/database.php";
$db = new Database("localhost", "5svcode", "root", "");
$pdo = $db->connect();

// LẤY DANH SÁCH ĐƠN
$sql = "SELECT * FROM donhang WHERE id_User = ? ORDER BY id_dh DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_User]);
$list_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$orders = [];

foreach ($list_orders as $order) {

    // LẤY CHI TIẾT THEO ĐÚNG TÊN CỘT
    $sql_items = "SELECT ctdh.*, sp.Name, sp.img 
                  FROM chitiet_donhang ctdh
                  JOIN sanpham sp ON sp.id_SP = ctdh.id_SP
                  WHERE ctdh.id_dh = ?";
    $stmt_items = $pdo->prepare($sql_items);
    $stmt_items->execute([$order['id_dh']]);
    $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

    $orders[] = [
        'id_dh' => $order['id_dh'],
        'ngay_mua' => $order['ngay_mua'],
        'total' => $order['total'],
        'shipping' => $order['shipping'],
        'status' => $order['status'],
        'items' => $items
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng - 5SV Sport</title>

    <style>
        body {
            background: #f5f5f7;
            font-family: Arial, sans-serif;
        }

        .order-history-page {
            max-width: 920px;
            margin: 40px auto;
            padding: 5px;
        }

        .page-title {
            font-size: 30px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
            color: #222;
        }

        .order-card {
            background: #fff;
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 35px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: 0.25s;
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        /* HEADER */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .order-header h3 {
            font-size: 22px;
            color: #e60023;
            margin-bottom: 3px;
        }

        .order-header .date {
            color: #777;
            font-size: 14px;
        }

        .status {
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-cho-xu-ly {
            background: #ffe9c7;
            color: #cc7700;
        }

        .order-items {
            border-top: 1px solid #eee;
            margin-top: 10px;
            padding-top: 15px;
        }

        .order-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item img {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #eee;
        }

        .item-info .name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .item-info .sub {
            font-size: 14px;
            color: #777;
            margin-bottom: 3px;
        }

        .item-info .price {
            font-weight: 700;
            font-size: 15px;
            color: #d10000;
        }

        /* FOOTER */
        .order-footer {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #eee;
            text-align: right;
        }

        .total-text {
            font-size: 19px;
            font-weight: 700;
        }

        .total-text span {
            color: #d10000;
        }

        .ship-text {
            font-size: 14px;
            margin-top: 4px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="order-history-page">
        <h2 class="page-title">Lịch sử đơn hàng</h2>

        <?php if (empty($orders)): ?>
            <p style="text-align:center; font-size:17px; color:#666;">Bạn chưa có đơn hàng nào.</p>

        <?php else: foreach ($orders as $order): ?>
                <div class="order-card">

                    <div class="order-header">
                        <div>
                            <h3>Đơn hàng #<?= $order['id_dh'] ?></h3>
                            <p class="date">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['ngay_mua'])) ?></p>
                        </div>

                        <span class="status status-cho-xu-ly">
                            <?= $order['status'] ?>
                        </span>
                    </div>

                    <div class="order-items">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="order-item">
                                <img src="App/public/img/<?= $item['img'] ?>" alt="sp">

                                <div class="item-info">
                                    <p class="name"><?= $item['Name'] ?></p>

                                    <p class="sub">
                                        Size: <?= $item['size'] ?> |
                                        SL: <?= $item['soluong'] ?>
                                    </p>

                                    <p class="price"><?= number_format($item['giamua']) ?>đ</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-footer">
                        <p class="total-text">
                            Tổng thanh toán:
                            <span><?= number_format($order['total']) ?>đ</span>
                        </p>
                        <p class="ship-text">
                            Đã gồm phí vận chuyển: <?= number_format($order['shipping']) ?>đ
                        </p>
                    </div>

                </div>
        <?php endforeach; endif; ?>
    </div>

</body>
</html>

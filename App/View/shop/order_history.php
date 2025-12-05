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
    <title>Lịch sử đơn hàng</title>

    <style>
        body {
            background: #f1f2f6;
            font-family: "Segoe UI", sans-serif;
        }

        .order-history {
            width: 80%;
            margin: 40px auto;
        }

        .order-title {
            text-align: center;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #222;
        }

        /* THẺ ĐƠN HÀNG */
        .order-card {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 40px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.07);
            border: 1px solid #e5e5e5;
            transition: .25s ease;
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.12);
        }

        /* HEADER */
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .order-header h3 {
            font-size: 20px;
            color: #0b63c5;
            margin-bottom: 6px;
        }

        .order-header .date {
            font-size: 14px;
            color: #666;
        }

        /* STATUS */
        .status {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-cho-xu-ly {
            background: #fff3cd;
            color: #856404;
        }

        .status-dang-giao {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-hoan-thanh {
            background: #d4edda;
            color: #155724;
        }

        /* ITEMS */
        .order-items {
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .order-item {
            display: flex;
            margin-bottom: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid #eee;
            gap: 15px;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .item-info .name {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .item-info .sub {
            color: #555;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .item-info .price {
            font-size: 16px;
            color: #e0001b;
            font-weight: 700;
        }

        /* FOOTER */
        .order-footer {
            text-align: right;
            border-top: 1px solid #eee;
            padding-top: 18px;
            margin-top: 20px;
        }

        .order-footer .total {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .order-footer .total span {
            color: #e0001b;
        }

        .order-footer .ship {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="order-history">

    <h2 class="order-title">Lịch sử đơn hàng</h2>

    <?php if (empty($orders)): ?>
        <p style="text-align:center; color:#666; font-size:17px;">Bạn chưa có đơn hàng nào</p>

    <?php else: ?>

        <?php foreach ($orders as $order): ?>

            <div class="order-card">

                <!-- HEADER -->
                <div class="order-header">
                    <div>
                        <h3>Đơn hàng #<?= $order['id_dh'] ?></h3>
                        <p class="date">Ngày đặt: <?= $order['ngay_mua'] ?></p>
                    </div>

                    <?php $statusClass = "status-" . str_replace(" ", "-", strtolower($order['status'])); ?>
                    <span class="status <?= $statusClass ?>">
                        <?= $order['status'] ?>
                    </span>
                </div>

                <!-- ORDER ITEMS -->
                <div class="order-items">

                    <?php foreach ($order['items'] as $item): ?>
                        <div class="order-item">
                            <img src="App/public/img/<?= $item['img'] ?>" alt="">

                            <div class="item-info">
                                <p class="name"><?= $item['Name'] ?></p>
                                <p class="sub">Size: <?= $item['size'] ?> — SL: <?= $item['soluong'] ?></p>
                                <p class="price"><?= number_format($item['giamua'], 0, ',', '.') ?>đ</p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <!-- FOOTER -->
                <div class="order-footer">
                    <p class="total">
                        Tổng cộng: <span><?= number_format($order['total'], 0, ',', '.') ?>đ</span>
                    </p>
                    <p class="ship">
                        Phí vận chuyển: <?= number_format($order['shipping'], 0, ',', '.') ?>đ
                    </p>
                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>


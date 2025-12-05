<?php defined('APP_PATH') or die('No direct access'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng - 5SV Sport</title>
    <style>
        .container { max-width: 1100px; margin: 40px auto; padding: 20px; font-family: Arial, sans-serif; }
        .order-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 25px; overflow: hidden; }
        .header { background: #f8f8f8; padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; }
        .header .id { font-weight: bold; color: #d60000; font-size: 18px; }
        .header .date { color: #666; }
        .status { background: #fff2e6; color: #e67e22; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 13px; }
        .items { padding: 20px; }
        .item { display: flex; gap: 15px; padding: 12px 0; border-bottom: 1px dashed #eee; }
        .item img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; }
        .item-info h4 { margin: 0; font-size: 15px; }
        .item-info p { margin: 4px 0; color: #666; font-size: 14px; }
        .total { padding: 20px; background: #f8f8f8; text-align: right; font-size: 19px; font-weight: bold; color: #d60000; }
        .no-order { text-align: center; padding: 60px; color: #999; font-size: 18px; }
        .btn { display: inline-block; margin-top: 15px; padding: 12px 30px; background: #333; color: #fff; text-decoration: none; border-radius: 8px; }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2 style="text-align:center; margin-bottom:30px;">Lịch sử đơn hàng</h2>

    <?php if (empty($orders)): ?>
        <div class="no-order">
            <p>Chưa có đơn hàng nào.</p>
            <a href="index.php" class="btn">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="header">
                    <div>
                        <span class="id">Đơn hàng #<?= str_pad($order['id_dh'], 6, '0', STR_PAD_LEFT) ?></span>
                        <span class="date">Ngày: <?= date('d/m/Y H:i', strtotime($order['ngay_mua'])) ?></span>
                    </div>
                    <span class="status">Chờ xử lý</span>
                </div>

                <div class="items">
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="item">
                            <img src="App/public/img/<?= htmlspecialchars($item['img']) ?>" alt="">
                            <div class="item-info">
                                <h4><?= htmlspecialchars($item['Name']) ?></h4>
                                <p>Size: <?= $item['size'] ?> | SL: <?= $item['soluong'] ?></p>
                                <p>Giá: <?= number_format($item['giamua']) ?>₫</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="total">
                    Tổng thanh toán: <?= number_format($order['total']) ?>₫
                    <small style="display:block; color:#666; font-weight:normal;">
                        (Đã bao gồm phí ship <?= number_format($order['shipping']) ?>₫)
                    </small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

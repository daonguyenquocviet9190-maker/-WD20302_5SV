<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua hàng - 5SV Sport</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial,sans-serif; background:#f9f9f9; line-height:1.6; }

        :root {
            --primary-color: #d60000; /* Màu đỏ chủ đạo */
            --text-color: #222;
        }

        /* === CẤU TRÚC HEADER MỚI === */
        .page-header {
            padding:15px 20px;
            margin-bottom: 30px; /* Thêm khoảng cách phía dưới header */
        }
        .header-content {
            max-width:1400px;
            margin:0 auto;
            display:flex;
            justify-content:space-between;
            align-items:center;
            position:relative;
        }

        /* NÚT QUAY LẠI TRANG CHỦ (Căn trái) */
        .back-to-home {
            display: inline-block;
            text-decoration: none;
            color: #555;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .back-to-home:hover {
            color: var(--primary-color);
        }

        /* NÚT QUAY LẠI GIỎ HÀNG (Căn phải, style nổi bật) */
        .history-back-to-cart {
            background:var(--text-color); 
            color:white; 
            padding:12px 30px; 
            border-radius:50px;
            text-decoration:none; 
            font-weight:600; 
            font-size:15px;
            transition:all .3s; 
            box-shadow:0 5px 18px rgba(0,0,0,0.25);
        }
        .history-back-to-cart:hover {
            background:var(--primary-color); 
            transform: scale(1.05);
        }
        
        /* === NỘI DUNG CHÍNH === */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        h1 { text-align: center; color: #333; margin-bottom: 40px; font-size: 28px; }

        /* === DANH SÁCH ĐƠN HÀNG === */
        .order-list { display: flex; flex-direction: column; gap: 30px; }
        .order-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 25px;
            transition: all 0.3s;
            border-left: 5px solid var(--primary-color);
        }
        .order-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        /* === THÔNG TIN TỔNG QUAN ĐƠN HÀNG === */
        .order-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .order-summary h2 { font-size: 18px; color: var(--primary-color); }
        .order-summary p { font-size: 14px; color: #555; }
        .order-summary strong { color: #222; }

        .order-status {
            background: #ffebeb;
            color: var(--primary-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 13px;
        }

        /* === CHI TIẾT SẢN PHẨM === */
        .order-items-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 10px 0;
            border-bottom: 1px dashed #f0f0f0;
        }
        .item:last-child { border-bottom: none; }
        .item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }
        .item-info { flex-grow: 1; }
        .item-info h4 { font-size: 16px; margin-bottom: 5px; color: #333; }
        .item-info p { font-size: 14px; color: #777; }
        .item-info span { font-weight: bold; color: var(--primary-color); }

        /* === KHÔNG CÓ ĐƠN HÀNG === */
        .no-orders {
            text-align: center;
            padding: 80px 20px;
            color: #999;
            font-size: 18px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .no-orders a { color: var(--primary-color); text-decoration: underline; }
        
        /* Responsive */
        @media (max-width: 600px) {
            .header-content { flex-direction: column; gap: 15px; }
            .history-back-to-cart { width: 100%; text-align: center; padding: 10px; }
        }
    </style>
</head>
<body>

<div class="page-header">
    <div class="header-content">
        <a href="index.php" class="back-to-home">← Quay lại trang chủ</a>
        
        <a href="index.php?page=giohang" class="history-back-to-cart">Quay lại Giỏ hàng</a>
    </div>
</div>

<div class="container">
    <h1>Lịch sử mua hàng</h1>

    <?php if (empty($orders)): ?>
        <div class="no-orders">
            <p>Bạn chưa có đơn hàng nào.</p>
            <p><a href="?page=product">Bắt đầu mua sắm ngay!</a></p>
        </div>
    <?php else: ?>
        <div class="order-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-summary">
                        <div>
                            <h2>Đơn hàng #<?= htmlspecialchars($order['id_dh']) ?></h2>
                            <p>Ngày mua: <strong><?= date('d/m/Y H:i', strtotime($order['ngay_mua'])) ?></strong></p>
                            <p>Tổng tiền: <strong><?= number_format($order['tong_tien']) ?> ₫</strong></p>
                        </div>
                        <span class="order-status">
                            <?= htmlspecialchars($order['trang_thai'] ?? 'Đang xử lý') ?>
                        </span>
                    </div>

                    <div class="order-items">
                        <p class="order-items-title">Chi tiết sản phẩm đã mua:</p>
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="item">
                                <img src="App/public/img/<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['Name']) ?>">
                                <div class="item-info">
                                    <h4><?= htmlspecialchars($item['Name']) ?></h4>
                                    <p>Size: <?= htmlspecialchars($item['size']) ?> | SL: <?= htmlspecialchars($item['so_luong']) ?></p>
                                    <p>Giá: <span><?= number_format($item['gia_mua']) ?> ₫</span></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
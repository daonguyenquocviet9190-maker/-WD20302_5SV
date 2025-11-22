<?php
// Dữ liệu giả – sau này thay bằng MySQL
$revenue_today = 12480000;
$order_count = 142;
$new_customers = 36;
$return_rate = 2.1;

$recent_orders = [
    ["DH001", "Nguyễn Văn A", "Đã giao"],
    ["DH002", "Trần Thị B", "Đang xử lý"],
    ["DH003", "Phạm C", "Hoàn thành"],
    ["DH004", "Lê D", "Đã huỷ"],
];

$products = [
    ["Áo phông nam", "4,500,000"],
    ["Áo thể thao", "650,000"],
    ["Áo thun nữ", "120,000"],
];
?>

<div class="admin-container">

    <!-- Ô tìm kiếm -->
    <div class="search-bar">
        <input type="text" placeholder="Tìm kiếm sản phẩm, đơn hàng">
    </div>

    <!-- 4 ô thống kê -->
    <div class="stats-box">
        <div class="stat-item">
            <p class="title">Doanh thu (Hôm nay)</p>
            <h2>₫ <?= number_format($revenue_today) ?></h2>
            <span class="increase">+8.4% so với hôm qua</span>
        </div>

        <div class="stat-item">
            <p class="title">Đơn hàng</p>
            <h2><?= $order_count ?></h2>
            <span class="sub">5 đơn mới</span>
        </div>

        <div class="stat-item">
            <p class="title">Khách hàng mới</p>
            <h2><?= $new_customers ?></h2>
            <span class="sub">Trong 7 ngày</span>
        </div>

        <div class="stat-item">
            <p class="title">Tỷ lệ hoàn trả</p>
            <h2><?= $return_rate ?>%</h2>
            <span class="sub">Ổn định</span>
        </div>
    </div>

    <!-- Báo cáo nhanh -->
    <div class="report-section">
        <div class="report-header">
            <h3>Báo cáo nhanh</h3>
            <button class="range-btn">7 ngày</button>
        </div>

        <div class="report-chart">
            (Biểu đồ - Thay bằng chart thực nếu cần)
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="recent-orders">
        <div class="recent-header">
            <h3>Đơn hàng gần đây</h3>
            <a href="#">Xem tất cả →</a>
        </div>

        <table>
            <tr>
                <th>MÃ</th>
                <th>KHÁCH HÀNG</th>
                <th>TRẠNG THÁI</th>
            </tr>

            <?php foreach ($recent_orders as $o): ?>
            <tr>
                <td><?= $o[0] ?></td>
                <td><?= $o[1] ?></td>
                <td><?= $o[2] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Quản lý sản phẩm -->
    <div class="products-section">
        <div class="products-header">
            <h3>Quản lý sản phẩm</h3>
            <button class="add-btn">+ Thêm sản phẩm</button>
        </div>

        <div class="product-list">
            <?php foreach ($products as $p): ?>
            <div class="product-item">
                <h4><?= $p[0] ?></h4>
                <p>₫ <?= $p[1] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

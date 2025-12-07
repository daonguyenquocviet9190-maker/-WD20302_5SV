<div class="admin-container">

    <!-- Ô tìm kiếm -->
    <div class="search-bar">
        <input type="text" placeholder="Tìm kiếm sản phẩm, đơn hàng">
    </div>

    <!-- 4 ô thống kê -->
    <div class="stats-box">
        <div class="stat-item">
            <p class="title">Doanh thu (Hôm nay)</p>
            <<h2>₫ <?= number_format($revenue_today ?? 0) ?></h2>
            <span class="increase">+8.4% so với hôm qua</span>
        </div>

        <div class="stat-item">
            <p class="title">Đơn hàng</p>
            <h2><?= $order_count ?? 0 ?></h2>
            <span class="sub">5 đơn mới</span>
        </div>

        <div class="stat-item">
            <p class="title">Khách hàng mới</p>
            <h2><?= $new_customers ?? 0 ?></h2>
            <span class="sub">Trong 7 ngày</span>
        </div>

        <div class="stat-item">
            <p class="title">Tỷ lệ hoàn trả</p>
            <h2><?= ($return_rate ?? 0) ?>%</h2>
            <span class="sub">Ổn định</span>
        </div>
    </div>
    

<style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
        }
        .report-section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        h3 {
            margin: 0;
            color: #007bff;
        }
        /* Cấu hình Flexbox để 3 biểu đồ nằm cạnh nhau */
        .charts-group {
            display: flex; 
            justify-content: space-around;
            flex-wrap: wrap; 
            gap: 10px; 
        }
        .chart-container {
            width: 30%; /* Chia đều không gian cho 3 biểu đồ */
            min-width: 250px; 
            margin: 10px 0;
            box-sizing: border-box;
        }
    </style>

    <div class="report-section">
        <div class="report-header">
            <h3>Báo cáo nhanh</h3>
            <button class="range-btn">7 ngày</button>
        </div>
        
        <div class="charts-group">
            
            <div class="chart-container">
                <canvas id="doanhThuChart"></canvas>
            </div>
            
            <div class="chart-container">
                <canvas id="donHangChart"></canvas>
            </div>
            
            <div class="chart-container">
                <canvas id="khachHangChart"></canvas>
            </div>

        </div>
        
    </div>

    <script>
        const labels = ['T.Hai', 'T.Ba', 'T.Tư', 'T.Năm', 'T.Sáu', 'T.Bảy', 'C.Nhật'];

        // --- 1. BIỂU ĐỒ DOANH THU (LINE CHART) ---
        const doanhThuData = {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [1200000, 1900000, 1500000, 2400000, 3000000, 2800000, 3500000],
                tension: 0.4
            }]
        };

        const doanhThuConfig = {
            type: 'line',
            data: doanhThuData,
            options: { plugins: { title: { display: true, text: 'Xu hướng Doanh thu' } } }
        };

        new Chart(document.getElementById('doanhThuChart'), doanhThuConfig);


        // --- 2. BIỂU ĐỒ ĐƠN HÀNG (BAR CHART) ---
        const donHangData = {
            labels: labels,
            datasets: [{
                label: 'Số lượng Đơn hàng',
                backgroundColor: 'rgb(54, 162, 235)',
                borderColor: 'rgb(54, 162, 235)',
                data: [15, 22, 18, 25, 30, 28, 35],
            }]
        };

        const donHangConfig = {
            type: 'bar',
            data: donHangData,
            options: { plugins: { title: { display: true, text: 'Đơn hàng mới trong ngày' } } }
        };

        new Chart(document.getElementById('donHangChart'), donHangConfig);


        // --- 3. BIỂU ĐỒ KHÁCH HÀNG MỚI (LINE CHART) ---
        const khachHangData = {
            labels: labels,
            datasets: [{
                label: 'Khách hàng mới',
                backgroundColor: 'rgb(75, 192, 192)',
                borderColor: 'rgb(75, 192, 192)',
                data: [3, 5, 4, 7, 8, 6, 9],
                tension: 0.4
            }]
        };

        const khachHangConfig = {
            type: 'line',
            data: khachHangData,
            options: { plugins: { title: { display: true, text: 'Xu hướng Khách hàng mới' } } }
        };

        new Chart(document.getElementById('khachHangChart'), khachHangConfig);
    </script>

    <!-- Đơn hàng gần đây -->
    <div class="recent-orders">
        <div class="recent-header">
            <h3>Đơn hàng gần đây</h3>
            <!-- <a href="App/view/admin/xemtatca.php">Xem tất cả →</a> -->
        </div>

        <table>
            <tr>
                <th>MÃ</th>
                <th>KHÁCH HÀNG</th>
                <th>TRẠNG THÁI</th>
            </tr>

            <?php foreach ($recent_orders ?? [] as $o): ?>
            
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
            <?php foreach ($dssp as $p): ?>
            <div class="product-item">
                <img src="App/public/img/<?= $p['img'] ?>" width="60%" alt="">
                <h4><?= $p['Name'] ?></h4>
                <p>₫ <?= $p["Price"] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

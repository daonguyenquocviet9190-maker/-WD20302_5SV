<?php
// =========================================================
// PHẦN KẾT NỐI VÀ TRUY VẤN DATABASE (CẦN ĐẶT Ở ĐẦU FILE)
// =========================================================

// ⚠️ QUAN TRỌNG: Bạn cần thay thế đường dẫn này nếu file Database của bạn ở chỗ khác.
// Giả định file database.php chứa class Database
require_once 'App/Model/database.php'; 

// --- 1. KHỞI TẠO KẾT NỐI DB ---
// Tên DB đã được bạn cung cấp là '5svcode'
$db_host = 'localhost'; 
$db_user = 'root';      
$db_pass = '';          
$db_name = '5svcode';   

// Tạo đối tượng Database
$db = new Database($db_host, $db_name, $db_user, $db_pass);
// Thiết lập kết nối
$db->connect(); 


// --- 2. LOGIC TÌM KIẾM VÀ TRUY VẤN SẢN PHẨM ---

$search_term = $_GET['search'] ?? null;
$dssp = []; // Danh sách sản phẩm cuối cùng (để hiển thị)
$params = []; // Mảng tham số cho Prepared Statement

// SQL cơ bản
$sql = "SELECT * FROM sanpham";
$where_clause = ""; 

if (!empty($search_term)) {
    
    // Chuẩn hóa và làm sạch từ khóa tìm kiếm
    $search_key = trim($search_term);
    
    // Thêm điều kiện tìm kiếm: 
    // LOWER(Name) LIKE :search_key
    // Điều kiện này giúp tìm kiếm không phân biệt chữ hoa/thường (LOWER) 
    // và tìm kiếm tương đối (LIKE) trên cột Name
    $where_clause = " WHERE LOWER(Name) LIKE :search_key";
    
    // Gán tham số cho Prepared Statement (Đảm bảo an toàn)
    $params[':search_key'] = '%' . strtolower($search_key) . '%';
    
    // Khi có tìm kiếm, reset phân trang
    $current_page = 1;
    $total_pages = 1;

} else {
    // Nếu không có tìm kiếm, áp dụng logic phân trang mặc định (NẾU CÓ)
    
    // ⚠️ BẠN CẦN THAY THẾ PHẦN NÀY BẰNG LOGIC PHÂN TRANG THỰC TẾ CỦA BẠN
    // Ví dụ về logic phân trang mẫu:
    $current_page = $_GET['p'] ?? 1;
    $items_per_page = 10;
    
    // (Bổ sung: Lấy tổng số sản phẩm để tính $total_pages nếu cần)
    $count_sql = "SELECT COUNT(*) FROM sanpham";
    $total_items = $db->get_one($count_sql)['COUNT(*)'] ?? 0;
    
    $total_pages = ceil($total_items / $items_per_page);
    
    $offset = ($current_page - 1) * $items_per_page;
    $sql .= " LIMIT :limit OFFSET :offset";
    
    $params[':limit'] = $items_per_page;
    $params[':offset'] = $offset;
    // ⚠️ HẾT PHẦN PHÂN TRANG MẪU
}

// Nối SQL và điều kiện tìm kiếm
$sql .= $where_clause;

// 3. THỰC HIỆN TRUY VẤN
$dssp = $db->get_all($sql, $params); 


// --- 4. CÁC BIẾN THỐNG KÊ (GIẢ ĐỊNH) ---
// (Các biến này cần được lấy từ DB thông qua các truy vấn khác, tôi giữ nguyên giá trị mẫu)
$revenue_today = 5000000;
$order_count = 55;
$new_orders = 7;
$new_customers = 15;
$return_rate = 1.2;
// (Không cần đóng kết nối vì $db là đối tượng, nó sẽ tự giải phóng khi script kết thúc)


// =========================================================
// KẾT THÚC PHẦN XỬ LÝ VÀ BẮT ĐẦU HTML
// =========================================================
?>

<div class="admin-container">

    <div class="search-bar">
        <form action="" method="GET" class="search-form">
            <input type="hidden" name="page" value="home">
            
            <input type="text" 
                   name="search" 
                   placeholder="Tìm kiếm sản phẩm theo tên..."
                   value="<?= htmlspecialchars($search_term ?? '') ?>" 
                   class="search-input">
            
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> 
            </button>
            <?php 
            // Nếu có từ khóa tìm kiếm, thêm nút reset (xóa tìm kiếm)
            if (!empty($search_term)): ?>
                <a href="?page=home" class="reset-search-btn">Xóa tìm kiếm</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="stats-box">
    
    <div class="stat-item revenue-stat">
        <div class="stat-header">
            <i class="fas fa-chart-line icon-bg-red"></i> <p class="title">Doanh thu (Hôm nay)</p>
        </div>
        
        <h2 class="main-value">₫ <?= number_format($revenue_today ?? 0) ?></h2>
        
        <?php 
            $change_rate = 8.4; // Ví dụ: Lấy từ biến $revenue_change_rate
            $change_class = ($change_rate >= 0) ? 'increase' : 'decrease';
            $sign = ($change_rate >= 0) ? '+' : '';
        ?>
        <span class="<?= $change_class ?>"><?= $sign . number_format($change_rate, 1) ?>% so với hôm qua</span>
    </div>

    <div class="stat-item order-stat">
        <div class="stat-header">
            <i class="fas fa-shopping-cart icon-bg-blue"></i> <p class="title">Đơn hàng</p>
        </div>
        
        <h2 class="main-value"><?= number_format($order_count ?? 0) ?></h2>
        
        <span class="sub highlight-sub"><?= $new_orders ?? 5 ?> đơn mới đang chờ</span>
    </div>

    <div class="stat-item customer-stat">
        <div class="stat-header">
            <i class="fas fa-user-plus icon-bg-green"></i> <p class="title">Khách hàng mới</p>
        </div>
        
        <h2 class="main-value"><?= number_format($new_customers ?? 0) ?></h2>
        
        <span class="sub">Trong $7$ ngày qua</span>
    </div>

    <div class="stat-item return-stat">
        <div class="stat-header">
            <i class="fas fa-redo-alt icon-bg-orange"></i> <p class="title">Tỷ lệ hoàn trả</p>
        </div>
        
        <h2 class="main-value return-value"><?= ($return_rate ?? 0) ?>%</h2>
        
        <span class="sub">Ổn định</span>
    </div>
</div>

    


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
    // ... (Phần JavaScript giữ nguyên) ...
    const labels = ['T.Hai', 'T.Ba', 'T.Tư', 'T.Năm', 'T.Sáu', 'T.Bảy', 'C.Nhật'];

    Chart.defaults.font = {
        family: 'Arial, sans-serif',
        style: 'normal',
        weight: 'normal',
        size: 11 
    };


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
        options: { 
            plugins: { 
                title: { display: true, text: 'Xu hướng Doanh thu' } 
            },
            scales: { // THÊM CẤU HÌNH CHO TRỤC X VÀ Y
                x: {
                    ticks: {
                        font: { style: 'normal', weight: 'normal' } // Buộc nhãn trục X là thường
                    }
                },
                y: {
                    ticks: {
                        font: { style: 'normal', weight: 'normal' } // Buộc nhãn trục Y là thường
                    }
                }
            }
        }
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
        options: { 
            plugins: { 
                title: { display: true, text: 'Đơn hàng mới trong ngày' } 
            },
            scales: { // THÊM CẤU HÌNH CHO TRỤC X
                x: {
                    ticks: {
                        font: { style: 'normal', weight: 'normal' } // Buộc nhãn trục X là thường
                    }
                }
            }
        }
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
        options: { 
            plugins: { 
                title: { display: true, text: 'Xu hướng Khách hàng mới' } 
            },
            scales: { // THÊM CẤU HÌNH CHO TRỤC X
                x: {
                    ticks: {
                        font: { style: 'normal', weight: 'normal' } // Buộc nhãn trục X là thường
                    }
                }
            }
        }
    };

    new Chart(document.getElementById('khachHangChart'), khachHangConfig);
</script>

    <div class="products-section">
        <div class="products-header">
            <h3>Quản lý sản phẩm</h3>
            </div>

        <div class="product-list">
            <?php foreach ($dssp as $p): ?>
            <div class="product-item">
                <img src="App/public/img/<?= $p['img'] ?>" width="60%" alt="">
                <h4><?= $p['Name'] ?></h4>
                <p>₫ <?= number_format($p["Price"] ?? 0) ?></p>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($dssp) && !empty($search_term)): ?>
                <p style="padding: 20px; text-align: center; width: 100%;">Không tìm thấy sản phẩm nào khớp với từ khóa "<?= htmlspecialchars($search_term) ?>".</p>
            <?php endif; ?>
            
        </div>
        
        <div class="pagination">
            <?php if ($total_pages > 1): ?>
                
                <?php if ($current_page > 1): ?>
                    <a href="?page=home&p=<?= $current_page - 1 ?>" class="page-link">← Trước</a>
                <?php endif; ?>

                <?php 
                    // Logic hiển thị tối đa 5 nút xung quanh trang hiện tại
                    $start_loop = max(1, $current_page - 2);
                    $end_loop = min($total_pages, $current_page + 2);

                    if ($current_page < 3) $end_loop = min($total_pages, 5);
                    if ($current_page > $total_pages - 2) $start_loop = max(1, $total_pages - 4);
                ?>

                <?php for ($i = $start_loop; $i <= $end_loop; $i++): ?>
                    <a href="?page=home&p=<?= $i ?>" 
                       class="page-link <?= ($i == $current_page ? 'active' : '') ?>">
                       <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=home&p=<?= $current_page + 1 ?>" class="page-link">Sau →</a>
                <?php endif; ?>

            <?php endif; ?>
        </div>
        </div>

        

</div>
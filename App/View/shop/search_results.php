<?php
// Kiểm tra và khởi tạo biến để tránh lỗi
// $search_term và $dssp_timkiem được truyền từ Controller:
$search_term = $search_term ?? '';
$dssp_timkiem = $dssp_timkiem ?? [];

// Lấy giá trị sắp xếp từ URL (nếu muốn hỗ trợ sắp xếp kết quả tìm kiếm)
$sort = $_GET['sort'] ?? 'default';

// Sắp xếp sản phẩm (Nếu bạn muốn sắp xếp kết quả tìm kiếm)
if (!empty($dssp_timkiem) && $sort !== 'default') {
    usort($dssp_timkiem, function($a, $b) use ($sort) {
        // Giá sử dụng sale_price nếu có, không thì Price. Ép kiểu an toàn.
        $priceA = (float)($a['sale_price'] ?? 0) > 0 ? (float)($a['sale_price'] ?? 0) : (float)($a['Price'] ?? 0);
        $priceB = (float)($b['sale_price'] ?? 0) > 0 ? (float)($b['sale_price'] ?? 0) : (float)($b['Price'] ?? 0);

        if ($sort === 'price_high') {
            return $priceB <=> $priceA; // Cao đến thấp
        } elseif ($sort === 'price_low') {
            return $priceA <=> $priceB; // Thấp đến cao
        } elseif ($sort === 'newest') {
            return ($b['id_SP'] ?? 0) <=> ($a['id_SP'] ?? 0); // id_SP lớn hơn là mới hơn
        }
        return 0;
    });
}
$product_count = count($dssp_timkiem);
?>

<div class="shop-wrapper">
<aside class="filter-sidebar-new">
        <h2>Lọc sản phẩm</h2>

      <!-- Giới tính – Click là chuyển trang ngay -->
<div class="filter-group-new">
    <div class="filter-header-new">Giới tính <i class="fas fa-chevron-down"></i></div>
    <div class="filter-content-new">
        <label style="display:block; margin:8px 0; cursor:pointer;">
            <input type="checkbox" onclick="if(this.checked){location.href='index.php?page=nu_product'}else{this.checked=true}">
            Nữ
        </label>
        <label style="display:block; margin:8px 0; cursor:pointer;">
            <input type="checkbox" onclick="if(this.checked){location.href='index.php?page=nam_product'}else{this.checked=true}">
            Nam
        </label>
    </div>
</div>

       <!-- SIZE FILTER - Đã thêm name="size" và value -->
<div class="filter-group-new">
    <div class="filter-header-new">Size <i class="fas fa-chevron-down"></i></div>
    <div class="filter-content-new">
        <div class="size-options">
            <div class="size-btn <?= (($_GET['size'] ?? '') == 'S') ? 'active' : '' ?>" 
                 onclick="filterBySize('S')">S</div>
            <div class="size-btn <?= (($_GET['size'] ?? '') == 'M') ? 'active' : '' ?>" 
                 onclick="filterBySize('M')">M</div>
            <div class="size-btn <?= (($_GET['size'] ?? '') == 'L') ? 'active' : '' ?>" 
                 onclick="filterSize('L')">L</div>
            <div class="size-btn <?= (($_GET['size'] ?? '') == 'XL') ? 'active' : '' ?>" 
                 onclick="filterSize('XL')">XL</div>
            <div class="size-btn <?= (($_GET['size'] ?? '') == 'XXL') ? 'active' : '' ?>" 
                 onclick="filterSize('XXL')">XXL</div>
            <div class="size-btn <?= (($_GET['size'] ?? '') == '3XL') ? 'active' : '' ?>" 
                 onclick="filterSize('3XL')">3XL</div>
            <!-- Thêm các size khác tương tự -->
            <div class="size-btn <?= ($_GET['size'] ?? '') == '37' ? 'active' : '' ?>" onclick="filterSize('37')">37</div>
            <div class="size-btn <?= ($_GET['size'] ?? '') == '40' ? 'active' : '' ?>" onclick="filterSize('40')">40</div>
            <div class="size-btn <?= ($_GET['size'] ?? '') == '45' ? 'active' : '' ?>" onclick="filterSize('45')">45</div>
        </div>
    </div>
</div>


        <!-- Màu sắc -->
        <div class="filter-group-new">
            <div class="filter-header-new">Màu sắc <i class="fas fa-chevron-down"></i></div>
            <div class="filter-content-new">
                <div class="color-options">
                    <div class="color-btn" style="background:#007bff;"></div>
                    <div class="color-btn" style="background:#9c27b0;"></div>
                    <div class="color-btn" style="background:#9e9e9e;"></div>
                    <div class="color-btn" style="background:#ffeb3b;"></div>
                    <div class="color-btn" style="background:#2196f3;"></div>
                    <div class="color-btn" style="background:#e91e63;"></div>
                    <div class="color-btn" style="background:#795548;"></div>
                    <div class="color-btn" style="background:#000000;"></div>
                    <div class="color-btn" style="background:#ff9800;"></div>
                    <div class="color-btn" style="background:#4caf50;"></div>
                    <div class="color-btn" style="background:#f44336;"></div>
                    <div class="color-btn" style="background:#ffffff; border:1px solid #ddd;"></div>
                </div>
            </div>
        </div>
      <!-- Hoạt động -->
<div class="filter-group-new">
    <div class="filter-header-new">Hoạt động <i class="fas fa-chevron-down"></i></div>
    <div class="filter-content-new">
        <label style="display:block; margin:8px 0; cursor:pointer;">
            <input type="checkbox" onclick="if(this.checked){location.href='index.php?page=hd_bongda'}else{this.checked=true}">
            Bóng Đá
        </label>
        <label style="display:block; margin:8px 0; cursor:pointer;">
            <input type="checkbox" onclick="if(this.checked){location.href='index.php?page=hd_caulong'}else{this.checked=true}">
            Cầu lông/Tennis
        </label>
        <label style="display:block; margin:8px 0; cursor:pointer;">
            <input type="checkbox" onclick="if(this.checked){location.href='index.php?page=hd_chaybo'}else{this.checked=true}">
            Chạy Bộ
        </label>
        <label style="display:block; margin:8px 0; cursor:pointer;">
            <input type="checkbox" onclick="if(this.checked){location.href='index.php?page=hd_boiloi'}else{this.checked=true}">
            Bơi lội
        </label>
        <label style="display:block; margin:8px 0; cursor:pointer;">
            <input type="checkbox" onclick="if(this.checked){location.href='index.php?page=hd_gym'}else{this.checked=true}">
            Gym/ Yoga/ Pilates
        </label>
        <label style="display:block; margin:8px 0; cursor:pointer;">
            <input type="checkbox" onclick="if(this.checked){location.href='index.php?page=hd_macngay'}else{this.checked=true}">
            Mặc thường ngày
        </label>
    </div>
</div>
    </aside>
    <main class="main-content full-width"> 
        <div class="top-bar">
            <div class="result-count">
                Kết quả tìm kiếm cho: 
                <span class="search-highlight">"<?= htmlspecialchars($search_term) ?>"</span>
                (Tìm thấy **<?= number_format($product_count) ?>** sản phẩm)
            </div>
            
            <select id="sort-select" onchange="updateSort(this.value)">
                <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Sắp xếp theo...</option>
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Sắp xếp theo mới nhất</option>
                <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Sắp xếp theo giá: thấp đến cao</option>
                <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Sắp xếp theo giá: cao đến thấp</option>
            </select>
        </div>

        <?php if (!empty($dssp_timkiem)): ?>
        
        <div class="product-grid">
            
            <?php foreach ($dssp_timkiem as $sp): 
                // === LOGIC TÍNH GIÁ ĐỒNG BỘ VỚI TRANG PRODUCT ===
                $original_price = (float)($sp['Price'] ?? 0); 
                $sale_price  = (float)($sp['sale_price'] ?? 0);
                
                // Tính % giảm giá
                $discount_percent = 0;
                if ($sale_price > 0 && $sale_price < $original_price) {
                    $discount_percent = round(100 - ($sale_price * 100 / $original_price));
                }

                // Giá hiển thị cuối cùng
                $display_price = ($discount_percent > 0) ? $sale_price : $original_price;
            ?>

            <a href="?page=product_detail&id=<?= $sp['id_SP'] ?? '' ?>" class="product-card"> 
                <div class="img-wrap">
                    <img src="App/public/img/<?= htmlspecialchars($sp['img'] ?? 'default.jpg') ?>" 
                         alt="<?= htmlspecialchars($sp['Name'] ?? 'Sản phẩm') ?>">
                         
                    <?php if ($discount_percent > 0): ?>
                        <div class="sale-badge">-<?= $discount_percent ?>%</div>
                    <?php endif; ?>
                </div>

                <div class="product-info">
                    <div class="product-name">
                        <?= htmlspecialchars($sp['Name'] ?? 'Sản phẩm không tên') ?>
                    </div>

                    <div class="price-wrap">
                        <?php if ($discount_percent > 0): ?>
                            <del class="price-old">
                                <?= number_format($original_price, 0, ',', '.') ?>đ
                            </del>
                        <?php endif; ?>

                        <span class="price-current" style="color:red; font-weight:600; font-size:16px;">
                            <?= number_format($display_price, 0, ',', '.') ?>đ
                        </span>
                    </div>
                </div>
            </a>

            <?php endforeach; ?>
            
        </div>

        <?php else: ?>
            <p class="no-results" style="padding-top: 50px; text-align: center; font-size: 1.1em; color: #777;">
                Xin lỗi, không tìm thấy sản phẩm nào khớp với từ khóa "<?= htmlspecialchars($search_term) ?>".
                Vui lòng thử lại với từ khóa khác.
            </p>
        <?php endif; ?>
    </main>
</div>

<script>
// Click đúng tiêu đề mới mở (giữ nguyên như cũ)
document.querySelectorAll('.filter-header-new').forEach(header => {
    header.addEventListener('click', function() {
        this.parentElement.classList.toggle('active');
    });
});

// Chọn size (chỉ 1 cái active) - giữ nguyên
document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.size-options').querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Chọn màu (chỉ 1 cái active) - giữ nguyên
document.querySelectorAll('.color-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.color-options').querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Hàm update sort khi chọn dropdown
function updateSort(value) {
    const url = new URL(window.location);
    if (value === 'default') {
        url.searchParams.delete('sort');
    } else {
        url.searchParams.set('sort', value);
    }
    window.location.href = url.toString();
}

</script>
<?php
// Lấy tất cả sản phẩm
$dssp = $this->sanpham->getall_sp();

// Lấy giá trị sắp xếp từ URL
$sort = $_GET['sort'] ?? 'default';

// Sắp xếp sản phẩm dựa trên giá trị sort
if ($sort !== 'default') {
    usort($dssp, function($a, $b) use ($sort) {
        // Giá sử dụng sale_price nếu có, không thì Price
        $priceA = $a['sale_price'] > 0 ? $a['sale_price'] : $a['Price'];
        $priceB = $b['sale_price'] > 0 ? $b['sale_price'] : $b['Price'];

        if ($sort === 'price_high') {
            return $priceB <=> $priceA; // Cao đến thấp
        } elseif ($sort === 'price_low') {
            return $priceA <=> $priceB; // Thấp đến cao
        } elseif ($sort === 'newest') {
            return $b['id_SP'] <=> $a['id_SP']; // Giả sử id_SP lớn hơn là mới hơn
        } elseif ($sort === 'popular') {
            // Giả sử có cột views hoặc sales, nếu không thì giữ nguyên
            return ($b['views'] ?? 0) <=> ($a['views'] ?? 0);
        } elseif ($sort === 'average') {
            // Giả sử có cột rating, nếu không thì giữ nguyên
            return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
        }
        return 0;
    });
}

?>

<div class="shop-wrapper">

    <!-- SIDEBAR LỌC MỚI - GIỮ NGUYÊN NHƯ CŨ -->
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

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="top-bar">
            <div class="result-count">

            </div>
            <!-- Dropdown sắp xếp -->
            <select id="sort-select" onchange="updateSort(this.value)">
                <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Sắp xếp theo...</option>
                <!-- <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Sắp xếp theo mức độ phổ biến</option>
                <option value="average" <?= $sort === 'average' ? 'selected' : '' ?>>Sắp xếp theo mức độ trung bình</option> -->
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Sắp xếp theo mới nhất</option>
                <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Sắp xếp theo giá: thấp đến cao</option>
                <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Sắp xếp theo giá: cao đến thấp</option>
            </select>
        </div>

       <div class="product-grid">
    <?php foreach ($dssp as $sp): 
        // === FIX LỖI NULL & ÉP KIỂU AN TOÀN ===
        $original_price = (float)($sp['Price'] ?? 0);                    // Giá gốc
        $sale_price     = (float)($sp['sale_price'] ?? 0);              // Giá giảm (NULL → 0)
        
        // Tính % giảm giá
        $discount_percent = 0;
        if ($sale_price > 0 && $sale_price < $original_price) {
            $discount_percent = round(100 - ($sale_price * 100 / $original_price));
        }

        // Giá hiển thị cuối cùng
        $display_price = ($discount_percent > 0) ? $sale_price : $original_price;
    ?>

        <a href="?page=product_detail&id=<?= $sp['id_SP'] ?>" class="product-card">
            <div class="img-wrap">
                <img src="App/public/img/<?= htmlspecialchars($sp['img'] ?? 'default.jpg') ?>" 
                     alt="<?= htmlspecialchars($sp['Name'] ?? 'Sản phẩm') ?>">
                     
                <!-- Chỉ hiện badge khi thực sự giảm giá -->
                <?php if ($discount_percent > 0): ?>
                    <div class="sale-badge">-<?= $discount_percent ?>%</div>
                <?php endif; ?>
            </div>

            <div class="product-info">
                <div class="product-name">
                    <?= htmlspecialchars($sp['Name'] ?? 'Sản phẩm không tên') ?>
                </div>

                <div class="price-wrap">
                    <!-- Giá cũ (gạch ngang) chỉ hiện khi có giảm -->
                    <?php if ($discount_percent > 0): ?>
                        <del class="price-old">
                            <?= number_format($original_price, 0, ',', '.') ?>đ
                        </del>
                    <?php endif; ?>

                    <!-- Giá hiện tại (đỏ đậm) -->
                    <span class="price-current" style="color:red; font-weight:600; font-size:16px;">
                        <?= number_format($display_price, 0, ',', '.') ?>đ
                    </span>
                </div>
            </div>
        </a>

    <?php endforeach; ?>
</div>
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
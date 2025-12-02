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

        <!-- Giới tính -->
        <div class="filter-group-new">
            <div class="filter-header-new">Giới tính <i class="fas fa-chevron-down"></i></div>
            <div class="filter-content-new">
                <label><input type="checkbox" name="gender" value="nu"> Nữ</label>
                <label><input type="checkbox" name="gender" value="nam"> Nam</label>
            </div>
        </div>

        <!-- Size -->
        <div class="filter-group-new">
            <div class="filter-header-new">Size <i class="fas fa-chevron-down"></i></div>
            <div class="filter-content-new">
                <div class="size-options">
                    <div class="size-btn">S</div>
                    <div class="size-btn">M</div>
                    <div class="size-btn">L</div>
                    <div class="size-btn">XL</div>
                    <div class="size-btn">XXL</div>
                    <div class="size-btn">3XL</div>
                    <div class="size-btn">2</div>
                    <div class="size-btn">6</div>
                    <div class="size-btn">10</div>
                    <div class="size-btn">32</div>
                    <div class="size-btn">37</div>
                    <div class="size-btn">40</div>
                    <div class="size-btn">45</div>
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
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="top-bar">
            <div class="result-count">

            </div>
            <!-- Dropdown sắp xếp -->
            <select id="sort-select" onchange="updateSort(this.value)">
                <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Sắp xếp theo...</option>
                <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Sắp xếp theo mức độ phổ biến</option>
                <option value="average" <?= $sort === 'average' ? 'selected' : '' ?>>Sắp xếp theo mức độ trung bình</option>
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Sắp xếp theo mới nhất</option>
                <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Sắp xếp theo giá: thấp đến cao</option>
                <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Sắp xếp theo giá: cao đến thấp</option>
            </select>
        </div>

        <div class="product-grid">
            <?php foreach ($dssp as $sp): 
                $discount = round(100 - ($sp['sale_price'] * 100 / $sp['Price']));
            ?>
                <a href="?page=product_detail&id=<?= $sp['id_SP'] ?>" class="product-card">
                    <div class="img-wrap">
                        <img src="App/public/img/<?= htmlspecialchars($sp['img']) ?>" alt="<?= htmlspecialchars($sp['Name']) ?>">
                        <div class="sale-badge">-<?= $discount ?>%</div>
                    </div>
                    <div class="product-info">
                        <div class="product-name"><?= htmlspecialchars($sp['Name']) ?></div>
                        <div class="price-wrap">
                            <del class="price-old"><?= number_format($sp['Price'],0,',','.') ?>đ</del>
                            <span class="price-current" style="color: red; font-style: italic;"><?= number_format($sp['sale_price'],0,',','.') ?>đ</span>
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
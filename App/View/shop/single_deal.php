<?php
$deal_products = $this->deal_products ?? [];
$page_title    = $this->page_title ?? 'Single Deal';

// Lấy giá trị sắp xếp từ URL
$sort = $_GET['sort'] ?? 'default';

// Sắp xếp sản phẩm dựa trên giá trị sort
if ($sort !== 'default') {
    usort($deal_products, function($a, $b) use ($sort) {
        // Giá sử dụng sale_price (vì đây là trang deal, sale_price luôn có)
        $priceA = (float)($a['sale_price'] ?? 0);
        $priceB = (float)($b['sale_price'] ?? 0);

        if ($sort === 'price_high') {
            return $priceB <=> $priceA; // Cao đến thấp
        } elseif ($sort === 'price_low') {
            return $priceA <=> $priceB; // Thấp đến cao
        } elseif ($sort === 'newest') {
            return $b['id_SP'] <=> $a['id_SP']; // Giả sử id_SP lớn hơn là mới hơn
        } 
        // Bỏ qua popular và average nếu không có cột views/rating
        // elseif ($sort === 'popular') {
        //     return ($b['views'] ?? 0) <=> ($a['views'] ?? 0);
        // } elseif ($sort === 'average') {
        //     return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
        // }
        return 0;
    });
}
?>

<div class="shop-wrapper">

    <aside class="filter-sidebar-new">
        <h2>Lọc sản phẩm</h2>

        <div class="filter-group-new">
            <div class="filter-header-new">Giới tính <i class="fas fa-chevron-down"></i></div>
            <div class="filter-content-new">
                <label><input type="checkbox" name="gender" value="nu"> Nữ</label>
                <label><input type="checkbox" name="gender" value="nam"> Nam</label>
            </div>
        </div>

        <div class="filter-group-new">
            <div class="filter-header-new">Size <i class="fas fa-chevron-down"></i></div>
            <div class="filter-content-new">
                <div class="size-options">
                    <div class="size-btn">S</div><div class="size-btn">M</div><div class="size-btn">L</div>
                    <div class="size-btn">XL</div><div class="size-btn">XXL</div><div class="size-btn">3XL</div>
                    <div class="size-btn">2</div><div class="size-btn">6</div><div class="size-btn">10</div>
                    <div class="size-btn">32</div><div class="size-btn">37</div><div class="size-btn">40</div><div class="size-btn">45</div>
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

    <main class="main-content">
        <div class="top-bar">
            <div class="result-count">
                <span style="color:#666; margin-left:10px;">
                    </span>
            </div>
            <select id="sort-select" onchange="updateSort(this.value)">
                <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Sắp xếp theo...</option>
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Sắp xếp theo mới nhất</option>
                <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Sắp xếp theo giá: thấp đến cao</option>
                <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Sắp xếp theo giá: cao đến thấp</option>
            </select>
        </div>

        <div class="product-grid">
            <?php if (empty($deal_products)): ?>
                <p style="grid-column:1/-1; text-align:center; padding:80px 20px; font-size:22px; color:#999;">
                    Hiện chưa có sản phẩm nào trong mục này!
                </p>
            <?php else: ?>
                <?php foreach($deal_products as $sp): 
                    $original_price = (float)($sp['Price'] ?? 0); 
                    $sale_price     = (float)($sp['sale_price'] ?? 0);
                    $discount_percent = ($sale_price > 0 && $sale_price < $original_price) 
                                        ? round(100 - ($sale_price * 100 / $original_price)) 
                                        : 0;
                ?>
                    <a href="index.php?page=product_detail&id=<?= $sp['id_SP'] ?>" class="product-card">
                        <div class="img-wrap">
                            <img src="App/public/img/<?= htmlspecialchars($sp['img']) ?>" alt="<?= htmlspecialchars($sp['Name']) ?>">
                            <?php if($discount_percent > 0): ?>
                                <div class="sale-badge">-<?= $discount_percent ?>%</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($sp['Name']) ?></div>
                            <div class="price-wrap">
                                <?php if($discount_percent > 0): ?>
                                    <del class="price-old"><?= number_format($original_price,0,',','.') ?>đ</del>
                                <?php endif; ?>
                                <span class="price-current" style="color:#e74c3c; font-weight:700; font-size:18px;">
                                    <?= number_format($sale_price,0,',','.') ?>đ
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
// Click đúng tiêu đề mới mở (giữ nguyên như cũ)
document.querySelectorAll('.filter-header-new').forEach(h => h.onclick = () => h.parentElement.classList.toggle('active'));

// Chọn size (chỉ 1 cái active) - giữ nguyên
document.querySelectorAll('.size-btn').forEach(b => b.onclick = function(){
    this.parentElement.querySelectorAll('.active').forEach(x => x.classList.remove('active'));
    this.classList.add('active');
});

// Chọn màu (chỉ 1 cái active) - giữ nguyên
document.querySelectorAll('.color-btn').forEach(b => b.onclick = function(){
    this.parentElement.querySelectorAll('.active').forEach(x => x.classList.remove('active'));
    this.classList.add('active');
});

// Hàm update sort khi chọn dropdown (Sao chép từ product.php)
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
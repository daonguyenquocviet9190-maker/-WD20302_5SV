<?php
$deal_products = $this->deal_products ?? [];
$page_title    = $this->page_title ?? 'Single Deal';
?>

<div class="shop-wrapper">

    <!-- SIDEBAR LỌC -->
    <aside class="filter-sidebar-new">
        <h2>Lọc sản phẩm</h2>
        <div class="filter-group-new">
            <div class="filter-header-new">Giới tính <i class="fas fa-chevron-down"></i></div>
            <div class="filter-content-new">
                <label><input type="checkbox"> Nữ</label>
                <label><input type="checkbox"> Nam</label>
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
        <div class="filter-group-new">
            <div class="filter-header-new">Màu sắc <i class="fas fa-chevron-down"></i></div>
            <div class="filter-content-new">
                <div class="color-options">
                    <div class="color-btn" style="background:#007bff;"></div>
                    <div class="color-btn" style="background:#9c27b0;"></div>
                    <div class="color-btn" style="background:#9e9e9e;"></div>
                    <div class="color-btn active" style="background:#ffeb3b;"></div>
                    <div class="color-btn" style="background:#2196f3;"></div>
                    <div class="color-btn" style="background:#e91e63;"></div>
                    <div class="color-btn" style="background:#795548;"></div>
                    <div class="color-btn" style="background:#000;"></div>
                    <div class="color-btn" style="background:#ff9800;"></div>
                    <div class="color-btn" style="background:#4caf50;"></div>
                    <div class="color-btn" style="background:#f44336;"></div>
                    <div class="color-btn" style="background:#fff;border:1px solid #ddd;"></div>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="top-bar">
            <div class="result-count">
                <span style="color:#666; margin-left:10px;">
                </span>
            </div>
            <select class="form-select w-auto">
                <option>Sắp xếp theo...</option>
                <option>Sắp xếp theo mức độ phổ biến</option>
                <option>Sắp xếp theo mức độ trung bình</option>
                <option>Sắp xếp theo mới nhất</option>
                <option>Sắp xếp theo giá: thấp đến cao</option>
                <option>Sắp xếp theo giá: cao đến thấp</option>
            </select>
        </div>

        <div class="product-grid">
            <?php if (empty($deal_products)): ?>
                <p style="grid-column:1/-1; text-align:center; padding:80px 20px; font-size:22px; color:#999;">
                    Hiện chưa có sản phẩm nào trong mục này!
                </p>
            <?php else: ?>
                <?php foreach($deal_products as $sp): 
                    $discount = $sp['Price'] > 0 ? round(100 - ($sp['sale_price'] * 100 / $sp['Price'])) : 0;
                ?>
                    <a href="index.php?page=product_detail&id=<?= $sp['id_SP'] ?>" class="product-card">
                        <div class="img-wrap">
                            <img src="App/public/img/<?= htmlspecialchars($sp['img']) ?>" alt="<?= htmlspecialchars($sp['Name']) ?>">
                            <?php if($discount > 0): ?>
                                <div class="sale-badge">-<?= $discount ?>%</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($sp['Name']) ?></div>
                            <div class="price-wrap">
                                <?php if($discount > 0): ?>
                                    <del class="price-old"><?= number_format($sp['Price'],0,',','.') ?>đ</del>
                                <?php endif; ?>
                                <span class="price-current" style="color:#e74c3c; font-weight:700; font-size:18px;">
                                    <?= number_format($sp['sale_price'],0,',','.') ?>đ
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
document.querySelectorAll('.filter-header-new').forEach(h => h.onclick = () => h.parentElement.classList.toggle('active'));
document.querySelectorAll('.size-btn, .color-btn').forEach(b => b.onclick = function(){
    this.parentElement.querySelectorAll('.active').forEach(x => x.classList.remove('active'));
    this.classList.add('active');
});
</script>
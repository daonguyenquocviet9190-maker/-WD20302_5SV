<?php
$dssp = $this->sanpham->getall_sp();
?>

<div class="shop-wrapper">

    <!-- SIDEBAR LỌC MỚI - ĐẸP NHƯ HÌNH BẠN GỬI -->
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
                    <div class="color-btn active" style="background:#ffeb3b;"></div>
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

        <!-- Hoạt động (nếu cần sau này) -->
        <div class="filter-group-new">
            <div class="filter-header-new">Hoạt động <i class="fas fa-chevron-down"></i></div>
            <div class="filter-content-new">
                <label><input type="checkbox"> Bóng Đá</label>
                <label><input type="checkbox"> Cầu lông/Tennis</label>
                <label><input type="checkbox"> Chạy Bộ</label>
                <label><input type="checkbox"> Bơi lội</label>
                <label><input type="checkbox"> Gym/ Yoga/ Pilates</label>
                <label><input type="checkbox"> Mặc thường ngày</label>
            </div>
        </div>
    </aside>

    <!-- NỘI DUNG CHÍNH -->
    <main class="main-content">
        <div class="top-bar">
            <div class="result-count"></div>
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
    <?php foreach($sp_nam as $sp): ?>
        
        <!-- ĐIỀU KIỆN DUY NHẤT: CHỈ HIỂN THỊ NẾU ĐANG GIẢM GIÁ -->
        <?php if (!empty($sp['sale_price']) && $sp['sale_price'] > 0 && $sp['sale_price'] < $sp['Price']): ?>
            
            <?php 
                // Tính % giảm để hiện badge
                $discount = round(100 - ($sp['sale_price'] * 100 / $sp['Price']));
            ?>

            <a href="index.php?page=product_detail&id=<?= $sp['id_SP'] ?>" class="product-card">
                <div class="img-wrap">
                    <img src="App/public/img/<?= htmlspecialchars($sp['img']) ?>" alt="<?= htmlspecialchars($sp['Name']) ?>">
                    <div class="sale-badge">-<?= $discount ?>%</div>
                </div>
                <div class="product-info">
                    <div class="product-name"><?= htmlspecialchars($sp['Name']) ?></div>
                    <div class="price-wrap">
                        <del class="price-old"><?= number_format($sp['Price'],0,',','.') ?>đ</del>
                        <span class="price-current" style="color: red;  font-style: italic;"><?= number_format($sp['sale_price'],0,',','.') ?>đ</span>
                    </div>
                </div>
            </a>
            
        <?php endif; ?>
    <?php endforeach; ?>
</div>
    </main>
</div>

<script>
// Click đúng tiêu đề mới mở (không dùng Bootstrap collapse)
document.querySelectorAll('.filter-header-new').forEach(header => {
    header.addEventListener('click', function() {
        this.parentElement.classList.toggle('active');
    });
});

// Chọn size (chỉ 1 cái active)
document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.size-options').querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Chọn màu (chỉ 1 cái active)
document.querySelectorAll('.color-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.color-options').querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>
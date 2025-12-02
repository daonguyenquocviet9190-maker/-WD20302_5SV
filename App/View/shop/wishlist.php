<?php
session_start();

// Khởi tạo model Product
if (!isset($this->sanpham)) {
    $this->sanpham = new Product();
}

// Lấy danh sách wishlist từ session
$ds_sp_wish = [];
if (isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist'])) {
    foreach ($_SESSION['wishlist'] as $item) {
        $sp = $this->sanpham->get_sp_byID($item['id']);
        if ($sp) $ds_sp_wish[] = $sp;
    }
}
?>

<!-- Wrapper chính với override mạnh -->
<div class="wishlist-page" style="padding: 0px 20px 40px; max-width: 1400px; margin: 0 auto; width: 100%; box-sizing: border-box;">
    <h2 class="product-title" style="text-align: center; margin-bottom: 10px; font-size: 24px; font-weight: bold; color: #222;">
        Sản Phẩm Yêu Thích 
        <?php if (!empty($ds_sp_wish)): ?>
            <span style="color:#e74c3c;">(<?= count($ds_sp_wish) ?> sản phẩm)</span>
        <?php endif; ?>
    </h2>

    <?php if (empty($ds_sp_wish)): ?>
        <div class="wishlist-empty" style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <i class="fas fa-heart-broken" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 style="color: #555; font-size: 24px;">Chưa có sản phẩm nào trong danh sách yêu thích</h3>
            <p style="color: #888; font-size: 16px; margin-bottom: 30px;">Hãy thêm những món đồ bạn thích nhé!</p>
            <a href="index.php?page=home" style="background: #e74c3c; color: white; padding: 12px 24px; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 10px rgba(231,76,60,0.3); transition: all 0.3s;">
                Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <!-- Grid pure CSS - Không phụ thuộc Bootstrap, center hoàn hảo -->
        <div class="wishlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 24px; justify-content: center; width: 100%; margin: 0 auto; padding: 0 10px; box-sizing: border-box;">
            <?php foreach ($ds_sp_wish as $sp): ?>
                <div class="product-card" style="position: relative; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.1); transition: all 0.3s ease; height: 100%;">
                    <a href="?page=product_detail&id=<?= $sp['id_SP'] ?>" style="display: block; text-decoration: none;">
                        <img src="App/public/img/<?= htmlspecialchars($sp['img']) ?>" 
                             style="width: 100%; height: 280px; object-fit: cover; transition: transform 0.4s;" 
                             alt="<?= htmlspecialchars($sp['Name']) ?>">

                        <!-- Icon xóa wishlist -->
                        <div style="position: absolute; top: 12px; right: 12px;">
                            <a href="?page=removefromwishlist&id=<?= $sp['id_SP'] ?>" 
                               style="background: rgba(255,255,255,0.95); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.15); transition: all 0.3s;"
                               onclick="return confirm('Xóa sản phẩm này khỏi danh sách yêu thích?')">
                                <i class="fas fa-heart" style="color: #e74c3c; font-size: 20px;"></i>
                            </a>
                        </div>

                        <!-- Badge SALE -->
                        <?php if (!empty($sp['sale_price']) && $sp['sale_price'] < $sp['Price']): ?>
                            <span style="position: absolute; top: 12px; left: 12px; background: #e74c3c; color: white; padding: 6px 12px; font-size: 13px; font-weight: bold; border-radius: 4px;">SALE</span>
                        <?php endif; ?>
                    </a>

                    <div style="padding: 16px;">
                        <h6 style="font-weight: 600; font-size: 16px; color: #333; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 48px;">
                            <?= htmlspecialchars($sp['Name']) ?>
                        </h6>
                        <div style="font-size: 18px; font-weight: bold; color: #222;">
                            <?php if (!empty($sp['sale_price']) && $sp['sale_price'] < $sp['Price']): ?>
                                <del style="color: #999; font-size: 14px; margin-right: 6px;"><?= number_format($sp['Price']) ?>₫</del>
                                <span style="color: #e74c3c;"><?= number_format($sp['sale_price']) ?>₫</span>
                            <?php else: ?>
                                <span><?= number_format($sp['Price']) ?>₫</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- CSS override mạnh mẽ hơn, đặt inline để ưu tiên cao -->
<style>
    .wishlist-page, .wishlist-page * { box-sizing: border-box !important; margin: 0 auto !important; }
    .wishlist-page .wishlist-grid { justify-items: center !important; display: grid !important; overflow: visible !important; flex-wrap: nowrap !important; } /* Override flex cũ nếu có */
    .wishlist-page .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important; }
    .wishlist-page .product-scroll, .product-scroll { display: none !important; } /* Ẩn hoàn toàn class cũ */
    
    /* Responsive */
    @media (max-width: 768px) {
        .wishlist-page .wishlist-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px; padding: 0 5px; }
        .wishlist-page img { height: 220px !important; }
    }
    @media (max-width: 480px) {
        .wishlist-page .wishlist-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
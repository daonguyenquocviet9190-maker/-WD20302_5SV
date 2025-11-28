<?php
// app/View/shop/product_detail.php
if (!isset($ct_sp) || empty($ct_sp)) {
    echo "<h3>Sản phẩm không tồn tại!</h3>";
    return;
}
$sp = $ct_sp[0]; // Thông tin sản phẩm chính
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($sp['TenSP']) ?> - 5GV</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-detail { padding: 20px 0; }
        .product-images { text-align: center; }
        .product-images img { max-width: 100%; border: 1px solid #ddd; }
        .product-info h1 { font-size: 24px; margin: 10px 0; color: #333; }
        .price del { color: #999; }
        .price ins { color: #e74c3c; font-size: 28px; text-decoration: none; font-weight: bold; }
        .size-btn { display: inline-block; padding: 8px 15px; border: 1px solid #ddd; margin: 5px; cursor: pointer; }
        .size-btn.active { border-color: #000; background: #000; color: #fff; }
        .quantity { width: 60px; text-align: center; padding: 8px; }
        .btn-addcart { background: #e74c3c; color: #fff; padding: 12px 30px; border: none; cursor: pointer; font-size: 16px; }
        .btn-buynow { background: #000; color: #fff; padding: 12px 30px; border: none; cursor: pointer; margin-left: 10px; }
        .related-products { margin-top: 50px; }
        .related-products h3 { font-size: 22px; margin-bottom: 20px; }
        .product-item { text-align: center; margin-bottom: 30px; }
        .product-item img { width: 100%; height: auto; }
        .rating { color: #f39c12; }
    </style>
</head>
<body>

<div class="container product-detail">
    <div class="row">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-5 product-images">
            <img src="public/images/products/<?= htmlspecialchars($sp['HinhAnh']) ?>" alt="<?= htmlspecialchars($sp['TenSP']) ?>">
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-7 product-info">
            <h1><?= htmlspecialchars($sp['TenSP']) ?></h1>
            
            <div class="rating">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                <span>(245 đánh giá)</span>
            </div>

            <div class="price">
                <del><?= number_format($sp['GiaGoc'], 0, ',', '.') ?>đ</del>
                <ins><?= number_format($sp['GiaKhuyenMai'], 0, ',', '.') ?>đ</ins>
            </div>

            <p><strong>Mã sản phẩm:</strong> <?= htmlspecialchars($sp['MaSP']) ?></p>
            <p><strong>Thương hiệu:</strong> 5GV</p>

            <!-- Chọn size -->
            <div class="size-selection">
                <p><strong>Size:</strong></p>
                <div class="size-btn active">M</div>
                <div class="size-btn">L</div>
                <div class="size-btn">XL</div>
            </div>

            <!-- Số lượng -->
            <div style="margin: 20px 0;">
                <label><strong>Số lượng:</strong></label>
                <input type="number" class="quantity" value="1" min="1">
            </div>

            <!-- Nút thêm giỏ hàng & mua ngay -->
            <div>
                <button class="btn-addcart"><i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng</button>
                <button class="btn-buynow">Mua ngay</button>
            </div>

            <!-- Khuyến mãi -->
            <div style="margin-top: 30px; padding: 15px; background: #fff8f8; border: 1px solid #fab1b1; border-radius: 5px;">
                <p style="margin:5px 0; color:#e74c3c;"><strong>Hotline CSKH:</strong> 1900 9201 (8h - 21h | T2 - T7)</p>
                <p style="margin:5px 0; color:#e74c3c;">Miễn phí vận chuyển đơn hàng từ 500k</p>
                <p style="margin:5px 0; color:#e74c3c;">30 ngày đổi trả dễ dàng</p>
                <p style="margin:5px 0; color:#e74c3c;">Mã giảm thêm</p>
            </div>
        </div>
    </div>

    <!-- Mô tả chi tiết -->
    <div class="row" style="margin-top: 50px;">
        <div class="col-12">
            <h3>Chi tiết sản phẩm</h3>
            <div style="line-height: 1.8;">
                <?= nl2br(htmlspecialchars($sp['MoTa'] ?? 'Áo tanktop unisex phiên bản giới hạn với chất liệu cotton mềm mại, thoáng mát, phù hợp cho mọi hoạt động hàng ngày.')) ?>
            </div>
        </div>
    </div>

    <!-- Đánh giá & bình luận -->
    <div class="row" style="margin-top: 50px;">
        <div class="col-12">
            <h3>Đánh giá sản phẩm</h3>
            <p>Chưa có đánh giá nào.</p>
            <p>Hãy là người đầu tiên <a href="#">đánh giá</a> sản phẩm này</p>

            <!-- Form bình luận -->
            <form action="" method="post" style="margin-top: 30px;">
                <div class="form-group">
                    <label>Tên *</label>
                    <input type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Đánh giá của bạn</label>
                    <textarea class="form-control" rows="5" placeholder="Viết đánh giá của bạn tại đây..."></textarea>
                </div>
                <button type="submit" style="background:#e74c3c; color:#fff; padding:10px 20px; border:none;">Gửi đi</button>
            </form>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    <div class="related-products">
        <h3>Sản phẩm liên quan</h3>
        <div class="row">
            <?php if(isset($sp_lq) && count($sp_lq) > 0): ?>
                <?php foreach($sp_lq as $item): ?>
                    <div class="col-md-3 col-6">
                        <div class="product-item">
                            <a href="index.php?page=product_detail&id=<?= $item['id_SP'] ?>">
                                <img src="public/images/products/<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['Name']) ?>">
                                <h4><?= htmlspecialchars($item['TenSP']) ?></h4>
                                <p class="price">
                                    <del><?= number_format($item['GiaGoc'], 0, ',', '.') ?>đ</del>
                                    <ins><?= number_format($item['GiaKhuyenMai'], 0, ',', '.') ?>đ</ins>
                                </p>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Chưa có sản phẩm liên quan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Chọn size
document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

</body>
</html>
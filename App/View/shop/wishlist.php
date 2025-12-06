<?php
// session_start();

// Khởi tạo model Product
if (!isset($this->sanpham)) {
    // Lưu ý: Trong môi trường MVC, $this->sanpham thường đã được khởi tạo
    // ở __construct của Controller. Đoạn kiểm tra này có thể không cần thiết
    // nếu bạn chắc chắn đã khởi tạo Product.
    $this->sanpham = new Product();
}

// Lấy danh sách wishlist từ session
$ds_sp_wish = [];
if (isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist'])) {
    foreach ($_SESSION['wishlist'] as $item) {
        $sp = $this->sanpham->get_sp_byID($item['id']);
        // Kiểm tra xem sản phẩm có tồn tại và đang được hiển thị không
        if ($sp) $ds_sp_wish[] = $sp;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản Phẩm Yêu Thích</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        /* RESET và Cấu trúc chung */
        :root {
            --primary-color: #e74c3c; /* Màu đỏ chủ đạo */
            --secondary-color: #3498db;
            --text-color: #2c3e50;
            --light-bg: #f8f9fa;
        }

        .wishlist-page {
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
            min-height: 70vh; /* Đảm bảo chiều cao tối thiểu */
        }
        
        /* Tiêu đề */
        .page-title {
            text-align: center;
            margin-bottom: 35px;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-color);
        }
        .page-title span {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: 500;
        }

        /* Grid */
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 30px;
            padding: 0 10px;
        }

        /* Thẻ sản phẩm */
        .product-card {
            position: relative;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        /* Ảnh */
        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.4s;
        }
        .product-card:hover img {
            transform: scale(1.05);
        }
        
        /* Khu vực nội dung */
        .product-details {
            padding: 15px;
            text-align: center;
        }
        .product-details h6 {
            font-weight: 600;
            font-size: 17px;
            color: var(--text-color);
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 40px; /* Chiều cao cố định cho tiêu đề */
        }
        .price-group {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }
        .sale-price {
            color: var(--primary-color);
            margin-left: 5px;
        }
        .original-price {
            color: #95a5a6;
            font-size: 14px;
            margin-right: 5px;
            text-decoration: line-through;
        }

        /* Icon xóa (Heart) */
        .btn-remove-wish {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            z-index: 10;
        }
        .btn-remove-wish:hover {
            background: var(--primary-color);
            transform: scale(1.1);
        }
        .btn-remove-wish i {
            color: var(--primary-color);
            font-size: 18px;
            transition: color 0.3s;
        }
        .btn-remove-wish:hover i {
            color: white;
        }

        /* Badge SALE */
        .sale-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--primary-color);
            color: white;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
            z-index: 10;
        }
        
        /* Giỏ trống */
        .wishlist-empty {
            text-align: center;
            padding: 150px 20px;
            background: var(--light-bg);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-top: 30px;
        }

        .wishlist-empty h3 {
            font-size: 24px;
            color: var(--text-color);
            margin-bottom: 20px;
        }

        .btn-shopping {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 14px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(231,76,60,0.4);
            transition: all 0.3s;
        }
        .btn-shopping:hover {
            background: #c0392b;
            box-shadow: 0 8px 20px rgba(231,76,60,0.5);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .wishlist-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
            .product-card img { height: 250px; }
            .page-title { font-size: 28px; }
        }
        @media (max-width: 600px) {
            .wishlist-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; }
            .product-card img { height: 200px; }
            .page-title { font-size: 24px; margin-bottom: 20px;}
            .page-title span { font-size: 18px; }
        }
        /* Đảm bảo nút này không bao giờ bị gạch chân */
.btn-remove-wish {
    /* ... các thuộc tính khác ... */
    text-decoration: none !important; /* Quan trọng: loại bỏ gạch chân trên thẻ a */
}

/* Quan trọng: Loại bỏ gạch chân ngay cả khi di chuột */
.btn-remove-wish:hover {
    /* ... các thuộc tính khác ... */
    text-decoration: none; 
}
    </style>
</head>
<body>

<div class="wishlist-page">
    <h2 class="page-title">
        <i class="" style="color: var(--primary-color); margin-right: 10px;"></i>
        Sản Phẩm Yêu Thích 
        <?php if (!empty($ds_sp_wish)): ?>
            <span style="font-size: 24px; color: #555;">(<?= count($ds_sp_wish) ?> sản phẩm)</span>
        <?php endif; ?>
    </h2>

    <?php if (empty($ds_sp_wish)): ?>
        <div class="wishlist-empty">
            <i class="far fa-heart" style="font-size: 60px; color: #95a5a6; margin-bottom: 30px; display: block;"></i>
            <h3>Danh sách yêu thích của bạn đang trống!</h3>
            <a href="index.php?page=product" class="btn-shopping">
                Khám phá và thêm sản phẩm ngay
            </a>
        </div>
    <?php else: ?>
        <div class="wishlist-grid">
            <?php foreach ($ds_sp_wish as $sp): ?>
                <div class="product-card">
                    
                    <a href="?page=removefromwishlist&id=<?= $sp['id_SP'] ?>" 
                        class="btn-remove-wish"
                        title="Xóa khỏi danh sách yêu thích"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?')">
                        <i class="fas fa-times"></i> </a>

                    <a href="?page=product_detail&id=<?= $sp['id_SP'] ?>" style="text-decoration: none; color: inherit;">
                        <img src="App/public/img/<?= htmlspecialchars($sp['img']) ?>" 
                             alt="<?= htmlspecialchars($sp['Name']) ?>">

                        <?php if (!empty($sp['sale_price']) && $sp['sale_price'] < $sp['Price']): ?>
                            <span class="sale-badge">SALE</span>
                        <?php endif; ?>
                        
                        <div class="product-details">
                            <h6><?= htmlspecialchars($sp['Name']) ?></h6>
                            <div class="price-group">
                                <?php if (!empty($sp['sale_price']) && $sp['sale_price'] < $sp['Price']): ?>
                                    <del class="original-price"><?= number_format($sp['Price']) ?>₫</del>
                                    <span class="sale-price"><?= number_format($sp['sale_price']) ?>₫</span>
                                <?php else: ?>
                                    <span><?= number_format($sp['Price']) ?>₫</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
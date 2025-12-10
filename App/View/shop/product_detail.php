

   <?php if(!isset($ct_sp)) { echo "Lỗi: Không tìm thấy sản phẩm"; exit; } ?>
<div class="detail-container">
    <div class="detail-wrapper">

        <!-- LEFT IMAGE -->
        <div class="detail-left">
            <div class="main-image">
                <img src="App/public/img/<?= $ct_sp['img'] ?>" alt="<?= htmlspecialchars($ct_sp['Name']) ?>">
            </div>
            <div class="image-icons">
                <i class="fas fa-phone"></i>
                <i class="fas fa-comment"></i>
                <i class="fas fa-share"></i>
            </div>
        </div>

        <!-- RIGHT INFO -->
        <div class="detail-right">
            <h1 class="sp-title"><?= $ct_sp['Name'] ?></h1>

            <!-- GIÁ CHÍNH - KHÔNG CÓ GẠCH CHÂN -->
            <div class="sp-price">
                <?php if($ct_sp['sale_price'] > 0 && $ct_sp['sale_price'] < $ct_sp['Price']): ?>
                    <del><?= number_format($ct_sp['Price']) ?>đ</del>
                    <span class="price-sale"><?= number_format($ct_sp['sale_price']) ?>đ</span>
                <?php else: ?>
                    <span class="price-regular"><?= number_format($ct_sp['Price']) ?>đ</span>
                <?php endif; ?>
            </div>

            <form action="index.php?page=add_to_cart" method="post">
                <input type="hidden" name="id_SP" value="<?= $ct_sp['id_SP'] ?>">
                
                <div class="size-container">
                    <p>Chọn size:</p>
                    <div class="sizes">
                        <span class="size-item active">M</span>
                        <span class="size-item">L</span>
                        <span class="size-item">XL</span>
                    </div>
                </div>

                <div class="qty-container">
                    <p>Số lượng:</p>
                    <div class="qty-box">
                        <button type="button">-</button>
                        <input type="text" value="1" readonly>
                        <button type="button">+</button>
                    </div>
                </div>

                <input type="hidden" name="size" id="size-input" value="M">
                <input type="hidden" name="qty" id="qty-input" value="1">

                <div class="btn-group">
                    <button type="submit" class="btn-add">Thêm vào giỏ hàng</button>
                    <button type="submit" class="btn-buy">Mua ngay</button>
                </div>
            </form>

            <div class="support-box">
                <p><strong>Hotline CSKH 1900 9201 từ 8h - 21h | T2 - T7</strong></p>
                <p>Miễn phí vận chuyển đơn hàng từ 500K</p>
                <p>30 ngày đổi trả dễ dàng</p>
            </div>

            <div class="accordion">
                <div class="acc-item"><span>Mô tả sản phẩm</span> <i class="fas fa-plus"></i></div>
                <div class="acc-item"><span>Chất liệu</span> <i class="fas fa-plus"></i></div>
                <div class="acc-item"><span>Thông số sản phẩm</span> <i class="fas fa-plus"></i></div>
                <div class="acc-item"><span>Hướng dẫn bảo quản</span> <i class="fas fa-plus"></i></div>
            </div>
        </div>
    </div>

    <!-- ==================== SẢN PHẨM LIÊN QUAN (ĐÃ TẮT AUTOPLAY) ==================== -->
    <div class="related-section" style="margin: 10px 0 50px;">
        <h2 style="text-align:center; color:#ee2d2d; font-size:26px; margin-bottom:30px;">
            Sản phẩm khác
        </h2>

        <?php
        $sp_lq_full = $this->sanpham->get_sp_lq_full($ct_sp['id_SP'], $ct_sp['id_DM'] ?? 0);
        ?>

        <?php if (!empty($sp_lq_full)): ?>
        <div class="related-carousel">
            <div class="swiper relatedSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($sp_lq_full as $item): ?>
                    <div class="swiper-slide">
                        <div class="product-card">
                            <a href="index.php?page=product_detail&id=<?= $item['id_SP'] ?>" class="product-link">
                                <div class="product-img">
                                    <img src="App/public/img/<?= $item['img'] ?>" alt="<?= htmlspecialchars($item['Name']) ?>">
                                    <?php if ($item['sale_price'] > 0 && $item['sale_price'] < $item['Price']): ?>
                                        <span class="sale-badge">
                                            -<?= round((($item['Price'] - $item['sale_price']) / $item['Price']) * 100) ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3><?= htmlspecialchars($item['Name']) ?></h3>
                                    
                                    <!-- GIÁ LIÊN QUAN - KHÔNG GẠCH CHÂN -->
                                    <div class="price">
                                        <?php if ($item['sale_price'] > 0 && $item['sale_price'] < $item['Price']): ?>
                                            <del><?= number_format($item['Price']) ?>đ</del>
                                            <span class="price-sale"><?= number_format($item['sale_price']) ?>đ</span>
                                        <?php else: ?>
                                            <span class="price-regular"><?= number_format($item['Price']) ?>đ</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <!-- Swiper CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <style>
        /* BỎ HOÀN TOÀN GẠCH CHÂN CHO TÊN + GIÁ */
        a, a:hover, a:focus, .product-link, .sp-title, h3, .price, .price span {
            text-decoration: none !important;
        }
        .product-info h3:hover { color: #ee2d2d; }

        /* Giá chính */
        .sp-price del { color: #999; font-size: 18px; margin-right: 12px; }
        .price-sale, .price-regular { color: #ee2d2d; font-size: 26px; font-weight: bold; }

        /* Giá liên quan */
        .related-section .price del { color: #999; font-size: 13px; margin-right: 8px; }
        .related-section .price-sale, .related-section .price-regular {
            color: #ee2d2d; font-size: 18px; font-weight: bold;
        }
        .swiper-wrapper{gap:15px;}
        /* Carousel style */
        .related-carousel { max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        .product-card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 5px 20px rgba(0,0,0,0.08); transition:.4s; }
        .product-card:hover { transform:translateY(-10px); box-shadow:0 15px 35px rgba(0,0,0,0.15); }
        .product-card:hover img { transform:scale(1.08); }
        .product-img { position:relative; overflow:hidden; height:280px; }
        .product-img img { width:100%; height:100%; object-fit:cover; transition:.5s; }
        .sale-badge { position:absolute; top:12px; left:12px; background:#ee2d2d; color:#fff; padding:6px 12px; border-radius:8px; font-weight:bold; font-size:14px; }
        .product-info { padding:16px; text-align:center; }
        .product-info h3 { font-size:15px; margin:8px 0 12px; color:#333; height:48px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }

        .swiper-button-next, .swiper-button-prev {
            color:#ee2d2d; background:white; width:44px; height:44px; border-radius:50%; box-shadow:0 4px 15px rgba(0,0,0,0.1);
        }
        .swiper-button-next:after, .swiper-button-prev:after { font-size:18px; }
        </style>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper(".relatedSwiper", {
                slidesPerView: 2,
                spaceBetween: 20,
                loop: true,
                // ĐÃ TẮT HOÀN TOÀN AUTOPLAY
                // autoplay: { delay: 4000, disableOnInteraction: false },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    640: { slidesPerView: 3 },
                    768: { slidesPerView: 4 },
                    1024: { slidesPerView: 5 },
                }
            });
        });

        // Chọn size
        document.querySelectorAll('.size-item').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.size-item').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.getElementById("size-input").value = this.innerText;
            });
        });

        // Tăng giảm số lượng
        const qtyInput = document.querySelector('.qty-box input');
        const [minusBtn, plusBtn] = document.querySelectorAll('.qty-box button');
        minusBtn.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > 1) { qtyInput.value = val - 1; document.getElementById("qty-input").value = qtyInput.value; }
        });
        plusBtn.addEventListener('click', () => {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            document.getElementById("qty-input").value = qtyInput.value;
        });
        </script>

        <?php else: ?>
        <p style="text-align:center; color:#999; font-size:16px; padding:60px 0;">
            Hiện chưa có sản phẩm liên quan.
        </p>
        <?php endif; ?>
    </div>
</div>

<script>
// Đảm bảo không bị gạch chân dù có CSS bên ngoài can thiệp
document.querySelectorAll('a, .price, .sp-title, h3').forEach(el => {
    el.style.textDecoration = 'none';   
});
</script>
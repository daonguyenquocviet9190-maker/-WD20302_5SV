<div class="detail-container">

    <div class="detail-wrapper">
        <!-- LEFT: HÌNH -->
        <div class="detail-left">
            <div class="main-image">
                <img src="App/public/img/<?= $ct_sp['img'] ?>" alt="">
            </div>

            <div class="image-icons">
                <i class="fas fa-phone"></i>
                <i class="fas fa-comment"></i>
                <i class="fas fa-share"></i>
            </div>
        </div>

        <!-- RIGHT: THÔNG TIN -->
        <div class="detail-right">

            <h1 class="sp-title"><?= $ct_sp['Name'] ?></h1>

            <div class="sp-price">
                <del><?= number_format($ct_sp['Price']) ?>đ</del>
                <ins><?= number_format($ct_sp['sale_price']) ?>đ</ins>
            </div>

            <p class="label">Size</p>
            <div class="size-list">
                <div class="size-item active">M</div>
                <div class="size-item">L</div>
                <div class="size-item">XL</div>
            </div>

            <p class="label">Số lượng</p>
            <div class="qty-box">
                <button>-</button>
                <input type="text" value="1" min="1">
                <button>+</button>
            </div>

            <div class="btn-group">
                <button class="btn-add">Thêm vào giỏ hàng</button>
                <button class="btn-buy">Mua ngay</button>
            </div>

            <div class="support-box">
                <p><strong>Hotline CSKH 1900 9201 từ 8h - 21h | T2 - T7</strong></p>
                <p>Miễn phí vận chuyển đơn hàng từ 500K</p>
                <p>30 ngày đổi trả dễ dàng</p>
            </div>

            <!-- ACCORDION -->
            <div class="accordion">
                <div class="acc-item">
                    <span>Mô tả sản phẩm</span> <i class="fas fa-plus"></i>
                </div>
                <div class="acc-item">
                    <span>Chất liệu</span> <i class="fas fa-plus"></i>
                </div>
                <div class="acc-item">
                    <span>Thông số sản phẩm</span> <i class="fas fa-plus"></i>
                </div>
                <div class="acc-item">
                    <span>Hướng dẫn bảo quản</span> <i class="fas fa-plus"></i>
                </div>
            </div>

        </div>
    </div>

    <!-- REVIEW -->
    <div class="review-section">

        <h2>Đánh giá</h2>
        <p>Chưa có đánh giá nào.</p>

        <div class="stars">
            <i class="far fa-star"></i><i class="far fa-star"></i>
            <i class="far fa-star"></i><i class="far fa-star"></i>
            <i class="far fa-star"></i>
        </div>

        <textarea placeholder="Viết đánh giá của bạn..."></textarea>
       <form action="" method="post">
        <div class="review-inputs">
            <div>
                <label>Tên*</label>
                <input type="text" required>
            </div>
            <div>
                <label>Email*</label>
                <input type="email" required>
            </div>
        </div>

        <button type="submit" class="review-btn">gửi đi</button>
        </form>
    </div>
   
</div>
<script>
// ====== CHỌN SIZE ======
document.querySelectorAll('.size-item').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.size-item').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// ====== SỐ LƯỢNG ======
document.querySelectorAll('.qty-box').forEach(box => {
    const input = box.querySelector('input');
    const btns = box.querySelectorAll('button');

    btns[0].addEventListener('click', () => {
        let val = parseInt(input.value);
        if (val > 1) input.value = val - 1;
    });
    btns[1].addEventListener('click', () => {
        let val = parseInt(input.value);
        input.value = val + 1;
    });
});
// ====== CHỌN STAR RATING ======
const stars = document.querySelectorAll('.stars i');
let selectedRating = 0;

stars.forEach((star, idx) => {
    // hover để highlight
    star.addEventListener('mouseover', () => {
        stars.forEach((s, i) => {
            s.classList.toggle('hovered', i <= idx);
        });
    });
    star.addEventListener('mouseout', () => {
        stars.forEach((s, i) => {
            s.classList.toggle('hovered', i < selectedRating);
        });
    });

    // click để chọn rating
    star.addEventListener('click', () => {
        selectedRating = idx + 1;
        stars.forEach((s, i) => {
            s.classList.toggle('active', i < selectedRating);
        });
    });
});

</script>
<?php if(!isset($ct_sp)) { echo "Lỗi: Không tìm thấy sản phẩm"; exit; } ?>
<div class="detail-container">

    <div class="detail-wrapper">

        <!-- LEFT IMAGE -->
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

        <!-- RIGHT INFO -->
        <div class="detail-right">

            <h1 class="sp-title"><?= $ct_sp['Name'] ?></h1>

            <div class="sp-price">
                <del><?= number_format($ct_sp['Price']) ?>đ</del>
                <ins><?= number_format($ct_sp['sale_price']) ?>đ</ins>
            </div>

            
<form action="index.php?page=add_to_cart" method="post">

    <!-- GIỮ ID SẢN PHẨM -->
    <input type="hidden" name="id_SP" value="<?= $ct_sp['id_SP'] ?>">

    <!-- SIZE -->
    <div class="size-container">
        <p>Chọn size:</p>
        <div class="sizes">
            <span class="size-item active">M</span>
            <span class="size-item">L</span>
            <span class="size-item">XL</span>
        </div>
    </div>

    <!-- QTY -->
    <div class="qty-container">
        <p>Số lượng:</p>
        <div class="qty-box">
            <button type="button">-</button>
            <input type="text" value="1">
            <button type="button">+</button>
        </div>
    </div>

    <!-- HIDDEN INPUT -->
    <input type="hidden" name="size" id="size-input" value="M">
    <input type="hidden" name="qty" id="qty-input" value="1">

    <div class="btn-group">
        <button type="submit" class="btn-add">Thêm vào giỏ hàng</button>
        <button type="button" class="btn-buy">Mua ngay</button>
    </div>

</form>


            <div class="support-box">
                <p><strong>Hotline CSKH 1900 9201 từ 8h - 21h | T2 - T7</strong></p>
                <p>Miễn phí vận chuyển đơn hàng từ 500K</p>
                <p>30 ngày đổi trả dễ dàng</p>
            </div>

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

        <textarea placeholder="Viết đánh giá của bạn..." cols="5" rows="8"></textarea>

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
            <button type="submit" class="review-btn">Gửi đi</button>
        </form>
    </div>

</div>

<script>
// ====== SIZE ======
document.querySelectorAll('.size-item').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.size-item').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById("size-input").value = this.innerText;
    });
});

// ====== QTY ======
const qtyInput = document.querySelector('.qty-box input');
const [minusBtn, plusBtn] = document.querySelectorAll('.qty-box button');

minusBtn.addEventListener('click', () => {
    let val = parseInt(qtyInput.value);
    if (val > 1) qtyInput.value = val - 1;
    document.getElementById("qty-input").value = qtyInput.value;
});

plusBtn.addEventListener('click', () => {
    let val = parseInt(qtyInput.value);
    qtyInput.value = val + 1;
    document.getElementById("qty-input").value = qtyInput.value;
});

// ====== STAR RATING ======
const stars = document.querySelectorAll('.stars i');
let selectedRating = 0;

stars.forEach((star, idx) => {
    star.addEventListener('mouseover', () => {
        stars.forEach((s, i) => s.classList.toggle('hovered', i <= idx));
    });
    star.addEventListener('mouseout', () => {
        stars.forEach((s, i) => s.classList.toggle('hovered', i < selectedRating));
    });
    star.addEventListener('click', () => {
        selectedRating = idx + 1;
        stars.forEach((s, i) => s.classList.toggle('active', i < selectedRating));
    });
});
</script>

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
    <?php if($ct_sp['sale_price'] < $ct_sp['Price'] && $ct_sp['sale_price'] > 0): ?>
        <del><?= number_format($ct_sp['Price']) ?>đ</del>
        <ins><?= number_format($ct_sp['sale_price']) ?>đ</ins>
    <?php else: ?>
        <ins><?= number_format($ct_sp['Price']) ?>đ</ins>
    <?php endif; ?>
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

        <!-- ĐÁNH GIÁ - ĐẸP Y HỆT HÌNH 1 CỦA BẠN -->
    <div class="review-section">
        <h2 style="color:#ee2d2d;margin:40px 0 20px;font-size:24px;">Đánh giá</h2>

        <?php 
        $danhgia_list = $this->sanpham->get_danhgia($ct_sp['id_SP']);
        $da_dangnhap = isset($_SESSION['username']);
        $username = $da_dangnhap ? $_SESSION['username'] : '';

        // Kiểm tra đã gửi chưa
        $da_gui = false;
        $danhgia_cua_user = null;
        foreach($danhgia_list as $dg){
            if($dg['ten_nguoidg'] === $username){
                $da_gui = true;
                $danhgia_cua_user = $dg;
                break;
            }
        }
        ?>

        <!-- ĐÃ GỬI → HIỆN ĐÚNG Y HỆT ẢNH 1 -->
        <?php if($da_gui && $danhgia_cua_user): ?>
        <div style="background:#f8f9fa;padding:20px;border-radius:8px;margin:30px 0;display:flex;align-items:flex-start;gap:15px;position:relative;">
            <i class="fas fa-user-circle" style="font-size:45px;color:#ccc;flex-shrink:0;"></i>
            <div style="flex:1;">
                <p style="margin:0 0 10px;color:#666;font-size:15px;line-height:1.4;">
                    Đánh giá của bạn đang chờ phê duyệt
                </p>
                <?php if(!empty($danhgia_cua_user['noidung'])): ?>
                <p style="margin:15px 0 0 0;color:#333;line-height:1.6;">
                    <?= nl2br(htmlspecialchars($danhgia_cua_user['noidung'])) ?>
                </p>
                <?php endif; ?>
            </div>
            <div style="position:absolute;top:20px;right:20px;display:flex;gap:4px;">
                <?php for($i=1;$i<=5;$i++): ?>
                    <i class="fas fa-star" style="color:#ee2d2d;font-size:18px;"></i>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- CHƯA GỬI → FORM ĐÁNH GIÁ -->
        <?php if(!$da_gui): ?>
        <div style="margin:40px 0;">
            <p style="color:#ee2d2d;font-weight:600;font-size:17px;margin-bottom:30px;">
                Hãy là người đầu tiên nhận xét “<?= htmlspecialchars($ct_sp['Name']) ?>”
            </p>

            <form action="" method="post">
                <div style="margin-bottom:30px;">
                    <label style="display:block;margin-bottom:15px;font-weight:600;">
                        Đánh giá của bạn <span style="color:red">*</span>
                    </label>
                    <div class="stars" style="display:flex;justify-content:center;gap:35px;font-size:46px;">
                        <i class="far fa-star" data-value="1"></i>
                        <i class="far fa-star" data-value="2"></i>
                        <i class="far fa-star" data-value="3"></i>
                        <i class="far fa-star" data-value="4"></i>
                        <i class="far fa-star" data-value="5"></i>
                    </div>
                    <input type="hidden" name="diem" id="selected-rating" required>
                </div>

                <textarea name="noidung" rows="6" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." required
                          style="width:100%;padding:16px;border:1px solid #ddd;border-radius:12px;font-size:15px;margin-bottom:25px;"></textarea>

                <?php if(!$da_dangnhap): ?>
                <div style="display:flex;gap:20px;margin-bottom:20px;">
                    <input type="text" name="ten" placeholder="Tên của bạn *" required 
                           style="flex:1;padding:14px;border:1px solid #ddd;border-radius:12px;">
                    <input type="email" name="email" placeholder="Email của bạn *" required 
                           style="flex:1;padding:14px;border:1px solid #ddd;border-radius:12px;">
                </div>
                <label style="display:flex;align-items:center;gap:10px;margin-bottom:25px;">
                    <input type="checkbox" name="luu_info" style="width:20px;height:20px;">
                    <span style="color:#555;">Lưu tên, email cho lần nhận xét sau</span>
                </label>
                <?php else: ?>
                <p style="margin:20px 0;padding:14px;background:#f8f8f8;border-radius:12px;">
                    Đang nhận xét với tên: <strong><?= htmlspecialchars($username) ?></strong>
                </p>
                <?php endif; ?>

                <input type="hidden" name="action" value="gui_danhgia">
                <button type="submit" style="background:#ee2d2d;color:white;border:none;padding:16px 60px;border-radius:50px;font-size:18px;font-weight:bold;">
                    Gửi đi
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Danh sách đánh giá cũ -->
        <?php if($danhgia_list): foreach($danhgia_list as $dg): ?>
            <?php if(!$da_gui || $dg['ten_nguoidg'] !== $username): ?>
            <div style="padding:30px 0;border-top:1px solid #eee;">
                <strong><?= htmlspecialchars($dg['ten_nguoidg']) ?></strong>
                <div style="margin:10px 0;display:flex;gap:6px;">
                    <?php for($i=1;$i<=5;$i++): ?>
                        <i class="<?= $i<=$dg['diem']?'fas':'far' ?> fa-star" style="color:#ee2d2d;font-size:18px;"></i>
                    <?php endfor; ?>
                </div>
                <small style="color:#999;display:block;margin-bottom:10px;">
                    <?= date('d/m/Y lúc H:i', strtotime($dg['ngay_danhgia'])) ?>
                </small>
                <p style="line-height:1.7;margin:10px 0;"><?= nl2br(htmlspecialchars($dg['noidung'])) ?></p>
            </div>
            <?php endif; ?>
        <?php endforeach; endif; ?>
    </div>

    <script>
    // SCRIPT SAO HOÀN HẢO - CHỈ 1 BỘ SAO, ĐẸP, MƯỢT, KHÔNG LỖI
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('.review-section form');
        if (!form) return;

        const starsContainer = form.querySelector('.stars');
        const stars = starsContainer.querySelectorAll('i');
        const hiddenInput = document.getElementById('selected-rating');

        stars.forEach((star, index) => {
            const value = index + 1;

            star.addEventListener('mouseenter', () => {
                stars.forEach((s, i) => {
                    s.className = i <= index ? 'fas fa-star' : 'far fa-star';
                    s.style.color = i <= index ? '#ee2d2d' : '#ddd';
                });
            });

            star.addEventListener('click', () => {
                hiddenInput.value = value;
                stars.forEach((s, i) => {
                    s.className = i < value ? 'fas fa-star' : 'far fa-star';
                    s.style.color = i < value ? '#ee2d2d' : '#ddd';
                });
            });
        });

        starsContainer.addEventListener('mouseleave', () => {
            const selected = hiddenInput.value || 0;
            stars.forEach((s, i) => {
                s.className = i < selected ? 'fas fa-star' : 'far fa-star';
                s.style.color = i < selected ? '#ee2d2d' : '#ddd';
            });
        });
    });
    </script>
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
</script>
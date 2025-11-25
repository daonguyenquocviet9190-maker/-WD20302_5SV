<!-- BANNER SLIDER -->
<div class="slider">
    <img src="App/public/img/banner0.jpg" class="slide active">
    <img src="App/public/img/banner1.png" class="slide">
    <img src="App/public/img/banner2.png" class="slide">
</div>

<h2><i>DANH MỤC SẢN PHẨM</i></h2>
   <div class="tab-container">
     <a href="index.php?page=home&gender=nu" 
       class="tab <?= ($gender == 'nu') ? 'active' : '' ?>">Nữ</a>

    <a href="index.php?page=home&gender=nam" 
       class="tab <?= ($gender == 'nam') ? 'active' : '' ?>">Nam</a>
</div>

<!-- SCROLL WRAPPER -->
<div class="wrap">

    <div class="scroll-btn left-btn" onclick="scrollToLeft()">&#10094;</div>
    <div class="scroll-btn right-btn" onclick="scrollToRight()">&#10095;</div>

    <div class="category-list" id="cateList">
        <?php foreach($dsdm as $dm): ?>
            <div class="category-card">
                <img src="App/public/img/<?= $dm['img'] ?>">
                <p><?= $dm['Name'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="dots" id="dotsContainer"></div>
</div>
  <script src="App/public/js/function.js"></script>
<section class="promo-banner">
    <div class="promo-top">
        <div class="promo-text">
            <h2>“TRỢ THỦ” ĐẮC LỰC <br> CỦA THẾ HỆ YÊU VẬN ĐỘNG</h2>
        </div>
        
        <div class="promo-img">
            <img src="App/public/img/banner3.png" alt="promo">
        </div>

        <div class="promo-info">
            <h3>Kiến tạo trải nghiệm <br> vận động toàn diện</h3>
              <p>Với triết lý đề cao sự thoải mái, chất lượng và tính ứng dụng cao, từng sản phẩm của 5SV được thiết kế để:</p>
            <ul>
                <li><b>Truyền cảm hứng</b> cho bạn mỗi ngày để bước chuyển nhỏ tích cực.</li>
                <li><b>Đồng hành linh hoạt</b> trong mọi hoạt động – từ luyện tập đến cuộc sống thường nhật.</li>
                <li><b>Khơi dậy tinh thần thể thao</b> không đơn thuần là rèn luyện cơ thể mà là lối sống lành mạnh...</li>
            </ul>
        </div>
    </div>

    <div class="promo-bottom">
        <img src="App/public/img/banner4.png" alt="">
    </div>
</section>

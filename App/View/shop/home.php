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

<h2 class="product-title">Deal 111K</h2>
<div class="product-scroll">
    <?php foreach($deal111k as $sp): ?>
        <div class="product-card">
            <a class="product-img-wrapper" href="?page=product_detail&id=<?= $sp['id_SP'] ?>">
                <img src="App/public/img/<?= $sp['img'] ?>" class="product-img">
                <div class="product-icons">
                      <a href="#" class="icon"><i class="fa fa-link"></i></a>
                    <a href="#" class="icon"><i class="fa fa-heart"></i></a>
                    <a href="#" class="icon"><i class="fa fa-arrows"></i></a>
                </div>

                <?php if($sp['sale_price'] < 200000): ?>
                    <span class="sale-badge">SALE</span>
                <?php endif; ?>
                </a>

            <div class="product-name"><?= $sp['Name'] ?></div>
            <div class="product-price"><del style="color:#8E8E8E; font-size: 15px;"><?= number_format($sp['Price']) ?>₫</del>
             <?= number_format($sp['sale_price']) ?>₫</div>
        </div>
    <?php endforeach; ?>
</div>


<h2 class="product-title">Sản phẩm mới</h2>
<div class="product-scroll">
    <?php foreach($sp_moi as $sp): ?>
        <div class="product-card">
            <a class="product-img-wrapper" href="?page=product_detail&id=<?= $sp['id_SP'] ?>">
                <img src="App/public/img/<?= $sp['img'] ?>" class="product-img">
                <div class="product-icons">
                    <a href="#" class="icon"><i class="fa fa-link"></i></a>
                    <a href="#" class="icon"><i class="fa fa-heart"></i></a>
                    <a href="#" class="icon"><i class="fa fa-arrows"></i></a>
    </div>

                <span class="new-badge">MỚI</span>
    </a>

            <div class="product-name"><?= $sp['Name'] ?></div>
            <div class="product-price" style="color: black; font-weight: 550;"><?= number_format($sp['Price']) ?>₫</div>
        </div>
    <?php endforeach; ?>
</div>

<div class="eco-banner">
    
    <!-- CỘT TRÁI: ẢNH FULL + TEXT OVERLAY -->
    <div class="eco-col-left">
        <img src="App/public/img/macxanh.png" alt="Leaf" class="eco-img-left">

        <!-- <div class="eco-overlay-text">
            <h2>MẶC XANH<br>SỐNG LÀNH</h2>
            <p>Thay đổi thói quen nhỏ mỗi ngày bằng sản phẩm xanh – tạo ra xu hướng thời trang tích cực.</p>
        </div> -->
    </div>

    <!-- CỘT GIỮA: ẢNH SẢN PHẨM -->
    <div class="eco-col-center">
        <img src="App/public/img/macxanh1.png" alt="Product" class="eco-img-center">
    </div>

    <!-- CỘT PHẢI: TEXT -->
    <div class="eco-col-right">
        <h3>ECO MOVE<br><span>MẶC XANH SỐNG LÀNH</span></h3>

        <p>
            Eco Move là dòng trang phục thể thao thân thiện môi trường, được sản xuất từ chất liệu tái chế cao cấp,
            mang đến khả năng co giãn tối ưu, thoáng khí và bền bỉ trong từng chuyển động.
        </p>

        <p>
            Không chỉ chú trọng hiệu suất vận động, sản phẩm còn là cam kết của DELTA trong việc giảm thiểu tác động
            đến môi trường.
        </p>

        <p>
            Mỗi sản phẩm Eco Move không chỉ là một món đồ thể thao – mà còn là lựa chọn xanh cho tương lai.
        </p>
    </div>

</div>


<!-- ===== BANNER 2 CỘT ===== -->
<div class="big-banner">
    <div class="banner-item">
        <img src="App/public/img/sss.png" alt="">
        <!-- <div class="banner-overlay">
            <span class="badge">NEW DROP</span>
            <h2>SWIMWEAR<br><small>Collection</small></h2>
            <div class="banner-arrow">→</div>
        </div> -->
    </div>

    <div class="banner-item">
        <img src="App/public/img/ssss.png" alt="">
        <!-- <div class="banner-overlay">
            <span class="badge">NEW DROP</span>
            <h2>FOOTBALL<br><small>Collection</small></h2>
            <div class="banner-arrow">→</div>
        </div> -->
    </div>
</div>

<div class="small-categories">
    <div class="cate-box">
        <img src="App/public/img/ca.png">
    </div>

    <div class="cate-box">
        <img src="App/public/img/ca1.png">
    </div>

    <div class="cate-box">
        <img src="App/public/img/ca2.png">
    </div>

    <div class="cate-box">
        <img src="App/public/img/ca3.png">
    </div>
</div>



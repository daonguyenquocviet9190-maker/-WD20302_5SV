<?php
session_start();

// Lấy username từ session nếu đã login
$user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>5SV Sport Fashion</title>
    <link rel="stylesheet" href="App/public/shop/css/style.css">
    <link rel="stylesheet" href="App/public/shop/css/category.css">
    <link rel="stylesheet" href="App/public/shop/css/product.css">
    <link rel="stylesheet" href="App/public/shop/css/product_detail.css">
    <link rel="stylesheet" href="App/public/shop/css/cart.css">
    <link rel="stylesheet" href="App/public/shop/css/order.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
 /* USER DROPDOWN */
.user-dropdown {
    position: relative;
    display: inline-block;
    z-index: 9999;
}

.user-trigger {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    background: white;
    transition: all 0.3s ease;
    font-size: 14px;
    color: #0f0e0eff;
}

.user-trigger:hover {
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.user-trigger .arrow {
    display: inline-block;
    transition: transform 0.3s ease;
}

.user-dropdown.active .arrow {
    transform: rotate(180deg);
}

/* MENU */
.user-menu {
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    pointer-events: none;
    transition: all 0.3s ease;
}

.user-dropdown.active .user-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}


.user-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 18px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.user-menu a:hover {
    background: #e31e24;
    color: #fff;
    transform: translateX(5px);
}

.user-menu i {
    width: 18px;
    text-align: center;
}

    </style>
</head>
<body>

    <header class="header">

        <div class="header-container">

            <!-- Logo -->
            <div class="logo">
                <a href="index.php?page=home">
                    <img src="App/public/img/logo5sv.png" alt="Logo">
                </a>
            </div>

            <!-- NAVIGATION -->
            <nav class="nav">
                <ul>
                    <li class="dropdown">
                        <a href="?page=product">Tất cả</a>
                    </li>
                    <li class="dropdown">
                        <a href="?page=nu_product">Nữ <i class="fa-solid fa-caret-down"></i></a>
                        <div class="dropdown-menu large">
                            <div class="col">
                                <h4>Áo</h4>
                                <a href="#">Tất cả áo nữ</a>
                                <a href="#">Áo ngắn tay</a>
                                <a href="#">Áo dài tay</a>
                                <a href="#">Áo polo</a>
                                <a href="#">Áo tanktop</a>
                                <a href="#">Áo hoodie</a>
                                <a href="#">Áo bra</a>
                                <a href="#">Áo khoác</a>
                            </div>
                            <div class="col">
                                <h4>Quần</h4>
                                <a href="#">Tất cả quần nữ</a>
                                <a href="#">Quần dài</a>
                                <a href="#">Quần shorts</a>
                                <a href="#">Quần legging</a>
                                <a href="#">Váy / Đầm</a>
                            </div>
                            <div class="col">
                                <h4>Hoạt động</h4>
                                <a href="#">Mặc thường ngày</a>
                                <a href="#">Chạy bộ</a>
                                <a href="#">Tennis/ Cầu lông/ Pickleball</a>
                                <a href="#">Bóng đá</a>
                                <a href="#">Gym/ Yoga/ Pilates</a>
                                <a href="#">Bơi lội</a>
                            </div>
                            <div class="col">
                                <h4>Nổi bật</h4>
                                <a href="#">Dri Air</a>
                                <a href="#">Anti UV</a>
                                <a href="#">Eco Move</a>
                                <a href="#">U.S. Cotton</a>
                            </div>
                            <div class="col">
                                <h4>Giày</h4>
                                <a href="#">Tất cả giày nữ</a>
                                <a href="#">Casual</a>
                                <a href="#">Chạy bộ</a>
                                <a href="#">Tập luyện</a>
                                <a href="#">Đá bóng</a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="?page=nam_product">Nam <i class="fa-solid fa-caret-down"></i></a>
                        <div class="dropdown-menu large">
                            <div>
                                <h4>Áo</h4>
                                <a href="#">Áo thun</a>
                                <a href="#">Áo tanktop</a>
                                <a href="#">Áo hoodie</a>
                            </div>
                            <div>
                                <h4>Quần</h4>
                                <a href="#">Quần shorts</a>
                                <a href="#">Quần dài</a>
                            </div>
                            <div>
                                <h4>Hoạt động</h4>
                                <a href="#">Gym</a>
                                <a href="#">Bơi</a>
                                <a href="#">Chạy bộ</a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="?page=giay_product">Giày Thể Thao <i class="fa-solid fa-caret-down"></i></a>
                        <div class="dropdown-menu large">
                            <div class="image">
                                <a href="#"><img src="App/public/img/giay.png" alt=""></a>
                                <a href="#"><img src="App/public/img/giay1.png" alt=""></a>
                                <a href="#"><img src="App/public/img/giay2.png" alt=""></a>
                                <a href="#"><img src="App/public/img/giay3.png" alt=""></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="?page=phukien">Phụ Kiện & Dụng Cụ <i class="fa-solid fa-caret-down"></i></a>
                        <div class="dropdown-menu large">
                            <div>
                                <h4>Phụ kiện</h4>
                                <a href="#">Balo</a>
                                <a href="#">Nón</a>
                                <a href="#">Tất</a>
                            </div>
                            <div>
                                <h4>Dụng cụ thể thao</h4>
                                <a href="#">Bóng chuyền</a>
                                <a href="#">Bóng đá</a>
                                <a href="#">Bóng mini</a>
                            </div>
                            <div class="image">
                                <a href="#"><img src="App/public/img/b1.png" alt=""></a>
                                <a href="#"><img src="App/public/img/b2.png" alt=""></a>
                                <a href="#"><img src="App/public/img/b2.jpg" alt=""></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="?page=single_deal">Single Deal <i class="fa-solid fa-caret-down"></i></a>
                        <div class="dropdown-menu large">
                            <div>
                                <a href="?page=single_deal&price=11">Đồng giá 11k</a>
                                <a href="?page=single_deal&price=111">Đồng giá 111k</a>
                                <a href="?page=single_deal&price=211">Đồng giá 211k</a>
                            </div>
                        </div>
                    </li>
                    <li><a href="?page=bosuutap">Bộ Sưu Tập</a></li>
                </ul>
            </nav>

            <!-- Icons -->
            <div class="icons">

                <!-- User -->
                <div class="user-dropdown">
    <div class="user-trigger">
        <i class="fa-regular fa-user"></i>
        <span class="user-name"><?= $user ? 'Xin chào bé,' . " ". htmlspecialchars($user) : '' ?></span>
        <i class="fa-solid fa-chevron-down arrow"></i>
    </div>

    <div class="user-menu">
        <?php if($user): ?>
            <a href="index.php?page=profile"><i class="fa-solid fa-id-card"></i> Hồ sơ</a>
            <a href="index.php?page=order_history"><i class="fa-solid fa-clock-rotate-left"></i> Lịch sử mua hàng</a>
            <a href="index.php?page=logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
        <?php else: ?>
            <a href="index.php?page=register"><i class="fa-solid fa-right-to-bracket"></i> Đăng nhập / Đăng ký</a>
        <?php endif; ?>
    </div>
</div>
                <a href="?page=wishlist"><i class="fas fa-heart"></i></a>
                <a href="javascript:void(0)" id="openSearchPopup"><i class="fas fa-search"></i></a>
                <a href="?page=giohang"><i class="fas fa-shopping-cart"></i></a>
            </div>

        </div>

        <div class="free-ship">MIỄN PHÍ VẬN CHUYỂN HOÁ ĐƠN TỪ 500K</div>

    </header>

    <!-- Search popup -->
    <div class="search-overlay-bg" id="searchOverlayBg"></div>
    <div class="search-popup" id="searchPopup">
        <div class="search-popup-header">
            <h3>Tìm kiếm sản phẩm</h3>
            <span class="close-search-popup" id="closeSearchPopup">×</span>
        </div>
        <div class="search-popup-input">
            <input type="text" placeholder="Nhập tên sản phẩm, mã SP, từ khóa..." autofocus>
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
    </div>

 <script>
// CLICK TOGGLE MENU
const userDropdown = document.querySelector('.user-dropdown');
const userTrigger = userDropdown.querySelector('.user-trigger');

userTrigger.addEventListener('click', function(e){
    e.stopPropagation();
    userDropdown.classList.toggle('active'); // click mở/đóng
});

// Để menu **giữ nguyên trạng thái**, không tự ẩn khi click ra ngoài
// Nếu muốn click ngoài đóng, thêm đoạn này:
// document.addEventListener('click', (e) => {
//     if(!userDropdown.contains(e.target)) {
//         userDropdown.classList.remove('active');
//     }
// });

</script>

    <script>
        // Mở popup tìm kiếm
        document.getElementById('openSearchPopup').addEventListener('click', function () {
            document.getElementById('searchOverlayBg').classList.add('active');
            document.getElementById('searchPopup').classList.add('active');
            document.querySelector('#searchPopup input').focus();
        });

        // Đóng popup
        function closeSearchPopup() {
            document.getElementById('searchOverlayBg').classList.remove('active');
            document.getElementById('searchPopup').classList.remove('active');
        }
        document.getElementById('closeSearchPopup').addEventListener('click', closeSearchPopup);
        document.getElementById('searchOverlayBg').addEventListener('click', closeSearchPopup);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSearchPopup();
        });
    </script>
</body>

</html>
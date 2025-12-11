<?php
session_start();

// Lấy username từ session nếu đã login
$user = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// 🔥 THAY ĐỔI: Lấy từ khóa tìm kiếm từ tham số 'search' trên URL.
// Nếu không tồn tại (chưa tìm kiếm), gán giá trị rỗng.
$search_term = $_GET['search'] ?? ''; 
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
/* --- 1. Container chính của Tìm kiếm Gần đây --- */
.search-popup-recent {
    padding: 15px 20px;
    /* Đường phân cách giữa thanh input và phần lịch sử */
    border-top: 1px solid #e0e0e0;
}

/* --- 2. Tiêu đề (VD: "Tìm kiếm gần đây:") --- */
.search-popup-recent h4 {
    margin: 0 0 10px 0;
    font-size: 14px;
    color: #555;
    font-weight: 600; /* Hơi đậm hơn */
}

/* --- 3. Danh sách các từ khóa (`ul.recent-list`) --- */
.recent-list {
    list-style: none; /* Loại bỏ dấu chấm mặc định */
    padding: 0;
    margin: 0;
    display: flex; /* Sắp xếp các mục theo hàng ngang */
    flex-wrap: wrap; /* Cho phép từ khóa xuống hàng nếu quá dài */
    gap: 8px; /* Khoảng cách giữa các "thẻ" từ khóa */
}

/* --- 4. Định dạng từng từ khóa (`li a`) --- */
.recent-list li a {
    display: inline-block;
    padding: 6px 12px;
    background-color: #f7f7f7; /* Nền xám nhạt */
    border: 1px solid #e0e0e0; /* Đường viền nhẹ */
    border-radius: 18px; /* Bo tròn góc để trông như thẻ (tag) */
    font-size: 13px;
    color: #333;
    text-decoration: none;
    transition: all 0.2s ease; /* Hiệu ứng mượt mà khi di chuột */
    line-height: 1; /* Căn chỉnh văn bản tốt hơn */
}

.recent-list li a:hover {
    background-color: #e6e6e6; /* Đổi màu nền khi di chuột */
    border-color: #cccccc;
    color: #000;
}

/* --- 5. Nút "Xóa lịch sử" --- */
.search-popup-recent .clear-recent {
    float: right; /* Đẩy nút sang bên phải */
    margin-top: -30px; /* Di chuyển lên gần tiêu đề */
    padding: 0;
    background: none;
    border: none;
    color: #a0a0a0;
    font-size: 12px;
    cursor: pointer;
    text-decoration: none; /* Không gạch chân mặc định */
    transition: color 0.2s;
}

.search-popup-recent .clear-recent:hover {
    color: #000;
    text-decoration: underline; /* Gạch chân khi di chuột */
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
                                <h4 style="white-space: nowrap;">Phụ kiện</h4>
                                <a href="#">Balo</a>
                                <a href="#">Nón</a>
                                <a href="#">Tất</a>
                            </div>
                            <div>
                                <h4 style="white-space: nowrap;">Dụng cụ thể thao</h4>
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
                        <div class="dropdown-menu large" style="min-width: 170px; left: 0%;">
                            <div>
                                <a href="?page=single_deal&price=11">Đồng giá 11k</a>
                                <a href="?page=single_deal&price=111">Đồng giá 111k</a>
                                <a href="?page=single_deal&price=211">Đồng giá 211k</a>
                            </div>
                        </div>
                    </li>
                    <li><a href="?page=bosuutap">Bộ Sưu Tập</a></li>
                    <li><a href="?page=contact">Liên hệ</a></li>
                </ul>
            </nav>

            <!-- Icons -->
            <div class="icons">

                <!-- User -->
                <div class="user-dropdown">
    <div class="user-trigger">
        <i class="fa-regular fa-user"></i>
        <span class="user-name"><?= $user ? 'Xin chào,' . " ". htmlspecialchars($user) : '' ?></span>
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
<form action="index.php" method="GET" class="search-popup-form" id="searchForm">
    <input type="hidden" name="page" value="search">
    <div class="search-popup-input">
        <input type="text" 
               name="search" 
               placeholder="Nhập tên sản phẩm, mã SP, từ khóa..." 
               value="<?= htmlspecialchars($search_term) ?>"
               autofocus 
               required 
               id="searchInput">
        <button type="submit"><i class="fas fa-search"></i></button>
    </div>
</form>
</form>
    <div class="search-popup-recent">
        <h4>Gợi ý tìm kiếm :</h4>
        <ul class="recent-list">
            <li><a href="index.php?page=nu_product">Áo nữ</a></li>
            <li><a href="index.php?page=giay_product">Giày thể thao</a></li>
            <li><a href="index.php?page=nam_product">Đồ nam</a></li>
            <li><a href="index.php?page=product_detail&id=118">Bóng rổ</a></li>
            <li><a href="index.php?page=single_deal&price=11">Sản phẩm sale 11k</a></li>
            <li><a href="index.php?page=product_detail&id=132">Nước rửa vợt</a></li>
            <li><a href="index.php?page=hd_bongda">Đồ bóng đá</a></li>
            <li><a href="index.php?page=hd_boiloi">Đồ bơi</a></li>

        </ul>

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
<script>
    // Lấy form và input
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');

    searchForm.addEventListener('submit', function (e) {
        // Lấy từ khóa và loại bỏ khoảng trắng dư thừa, chuyển về chữ thường để so sánh
        const keyword = searchInput.value.trim().toLowerCase();
        let redirectUrl = null;

        // --- Bắt đầu kiểm tra các từ khóa điều hướng nhanh ---
        if (keyword === 'áo nam') {
            redirectUrl = 'index.php?page=nam_product';
        } else if (keyword === 'áo nữ') {
            redirectUrl = 'index.php?page=nu_product';
        } else if (keyword === 'giày' || keyword === 'giày thể thao') { // Thêm 1 từ khóa phụ cho Giày
            redirectUrl = 'index.php?page=giay_product';
        } else if (keyword === 'phụ kiện' || keyword === 'dụng cụ' || keyword === 'phụ kiện & dụng cụ') { // Thêm các từ khóa liên quan
            redirectUrl = 'index.php?page=phukien';
        }
        // --- Kết thúc kiểm tra ---

        if (redirectUrl) {
            e.preventDefault(); // CHỈ CHẶN GỬI FORM NẾU CÓ ĐIỀU HƯỚNG
            window.location.href = redirectUrl; // Điều hướng
            closeSearchPopup(); // Đóng popup
            // Ngăn không cho code tiếp tục chạy và gửi form (mặc dù đã preventDefault)
            return; 
        }

        // Nếu không khớp với bất kỳ từ khóa nào ở trên, form sẽ gửi bình thường 
        // đến index.php?page=search&keyword=...
    });
</script>
</script>
</body>

</html>
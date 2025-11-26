<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>5SV Sport Fashion</title>
    <link rel="stylesheet" href="App/public/shop/css/style.css">
    <link rel="stylesheet" href="App/public/shop/css/category.css">
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <?php 
// Banner tiêu đề theo page
// if(isset($_GET['page'])) {
//     echo "<div class='page-banner'>".$_GET['page']."</div>";
// }
?>

<!-- HEADER -->
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
    <a href="?page=nu">Nữ <i class="fa-solid fa-caret-down"></i></a>

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
                    <a href="?page=nam">Nam <i class="fa-solid fa-caret-down"></i></a>
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

                <li><a href="?page=giay">Giày Thể Thao <i class="fa-solid fa-caret-down"></i></a></li>
                <li><a href="?page=phu-kien">Phụ Kiện & Dụng Cụ <i class="fa-solid fa-caret-down"></i></a></li>
                <li><a href="?page=deal">Single Deal <i class="fa-solid fa-caret-down"></i></a></li>
                <li><a href="?page=bo-suu-tap">Bộ Sưu Tập <i class="fa-solid fa-caret-down"></i></a></li>
            </ul>
        </nav>

        <!-- Icons -->
        <div class="icons">
            <a href="#"><i class="fas fa-heart"></i></a>
            <a href="#"><i class="fas fa-user"></i></a>
            <a href="#"><i class="fas fa-search"></i></a>
            <a href="#"><i class="fas fa-shopping-cart"></i></a>
        </div>

    </div>

    <div class="free-ship">MIỄN PHÍ VẬN CHUYỂN HOÁ ĐƠN TỪ 500K</div>

</header>



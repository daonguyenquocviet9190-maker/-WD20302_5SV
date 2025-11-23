<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delta Sport</title>
    <link rel="stylesheet" href="App/public/shop/css/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
                <img src="App/img/logo5sv.png" alt="Logo">
            </a>
        </div>

        <!-- NAVIGATION -->
        <nav class="nav">
            <ul>
                <li class="dropdown">
                    <a href="?page=nu">Nữ <i class="fa-solid fa-caret-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="#">Áo nữ</a>
                        <a href="#">Quần nữ</a>
                        <a href="#">Đồ tập nữ</a>
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



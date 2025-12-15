<?php
$adminName = "Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<!-- Thêm thư viện Chart.js từ CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="App/public/admin/css/admin.css">
</head>
<body>
<div class="header">
    <div>
        <img src="App/public/img/logo5sv.png" alt="Logo">
    </div>

    <div class="admin-text">
        Xin chào <?php echo $adminName; ?>
    </div>
</div>
<nav>
    <ul>
        <li><a href="?page=home">Dashboard</a></li>
        <li><a href="?page=product">Sản phẩm</a></li>
        <li><a href="?page=category">Loại sản phẩm</a></li>
        <li><a href="?page=user">Khách hàng</a></li>
        <li><a href="?page=order">Đơn hàng</a></li>
        <!-- <li><a href="admin.php?page=voucher_form">Danh sách Voucher</a></li> -->
    </ul>
</nav>


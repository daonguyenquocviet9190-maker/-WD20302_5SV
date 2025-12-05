<?php
session_start();

// Xóa tất cả dữ liệu trong session
$_SESSION = [];

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập hoặc trang chủ
header("Location: index.php?page=home");
exit;

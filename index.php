<?php
ob_start();

include "App/Controllers/HomeController.php";
$controller = new HomeController();

include 'App/View/shop/header.php';

// Nếu không có page => mặc định home
if (!isset($_GET['page'])) {
    header('location:index.php?page=home');
    exit;
}

$page = $_GET['page'];
$action = $_GET['action'] ?? '';

// ===== ROUTER GIỎ HÀNG =====
if ($page == "giohang" && $action == "remove") {
    $controller->giohang_remove();
    exit;
}

if ($page == "giohang" && $action == "update") {
    $controller->giohang_update();
    exit;
}

// ===== ROUTER BÌNH THƯỜNG =====
if (method_exists($controller, $page)) {
    $controller->$page();
} else {
    echo "404 - Page not found!";
}

include 'App/View/shop/footer.php';
?>

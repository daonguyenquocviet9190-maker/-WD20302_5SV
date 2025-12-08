<?php
session_start();

header('Content-Type: application/json');

// Chỉ chấp nhận phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Lấy ID sản phẩm
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
    exit;
}

// Xóa sản phẩm trong giỏ
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id_SP'] == $id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reset index
            echo json_encode(['status' => 'success', 'message' => 'Removed']);
            exit;
        }
    }
}

// Nếu không tìm thấy sản phẩm
echo json_encode(['status' => 'error', 'message' => 'Product not found']);

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if(isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $key => $product) {
            if($product['id_SP'] == $id) {
                unset($_SESSION['cart'][$key]);
                // Sắp xếp lại mảng
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
}

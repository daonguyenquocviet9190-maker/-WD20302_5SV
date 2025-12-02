<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

    if(isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as &$product) {
            if($product['id_SP'] == $id) {
                $product['quantity'] = $qty;
                break;
            }
        }
        unset($product); // tránh tham chiếu
    }
}

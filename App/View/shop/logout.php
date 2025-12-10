<?php
session_start();

require_once 'App/Model/database.php'; 
require_once 'App/Model/user.php';

$db = new Database("localhost", "5svcode", "root", "");
$pdo = $db->connect();

// Nếu user đang đăng nhập → cập nhật trạng thái OFFLINE
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE user SET status = 'offline' WHERE id_User = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Xóa session
$_SESSION = [];
session_destroy();

// Chuyển hướng
header("Location: index.php?page=home");
exit;

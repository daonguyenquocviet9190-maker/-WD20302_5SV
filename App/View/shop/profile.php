<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ✅ KẾT NỐI DATABASE (BẮT BUỘC PHẢI CÓ)
require_once __DIR__ . "/../../Model/database.php";
$db  = new Database("localhost", "5svcode", "root", "");
$pdo = $db->connect(); // ✅ DÒNG QUAN TRỌNG BỊ THIẾU CỦA BẠN

// ✅ CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem thông tin'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ XỬ LÝ CẬP NHẬT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $phone    = $_POST['phone'] ?? '';

    $stmt = $pdo->prepare("
        UPDATE user 
        SET Username = :username, Email = :email, Phone = :phone 
        WHERE id_User = :id
    ");

    $stmt->execute([
        'username' => $username,
        'email'    => $email,
        'phone'    => $phone,
        'id'       => $user_id
    ]);

    echo "<script>alert('Cập nhật thông tin thành công'); window.location='index.php?page=profile';</script>";
    exit;
}

// ✅ LẤY THÔNG TIN USER
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_User = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>alert('Không tìm thấy thông tin người dùng'); window.location='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Trang Profile</title>
<style>
* {
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}

body {
    background: linear-gradient(120deg,#f5f7fa,#c3cfe2);
    margin: 0;
    padding: 0;
}

.profile-wrapper {
    max-width: 1400px;
    margin: 60px auto;
    background: white;
    border-radius: 18px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    overflow: hidden;
    display: grid;
    grid-template-columns: 300px 1fr;
}

/* ===== LEFT SIDEBAR ===== */
.profile-sidebar {
    background: linear-gradient(135deg,#6a11cb,#2575fc);
    padding: 40px 25px;
    color: white;
    text-align: center;
}

.avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 4px solid white;
    margin: auto;
    object-fit: cover;
}

.profile-name {
    font-size: 20px;
    font-weight: bold;
    margin-top: 15px;
}

.profile-email {
    font-size: 14px;
    opacity: 0.9;
    margin-top: 5px;
}

.profile-menu {
    margin-top: 40px;
    text-align: left;
}

.profile-menu a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 10px;
    transition: 0.3s;
}

.profile-menu a:hover {
    background: rgba(255,255,255,0.2);
}

/* ===== RIGHT CONTENT ===== */
.profile-content {
    padding: 40px;
}

.profile-title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 25px;
    color: #333;
}

.profile-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px 25px;
}

.profile-form label {
    font-weight: 600;
    margin-bottom: 4px;
    display: block;
    font-size: 14px;
}

.profile-form input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 15px;
}

.profile-form input:focus {
    border-color: #2575fc;
    outline: none;
}

.profile-form .full {
    grid-column: 1 / 3;
}

.profile-actions {
    margin-top: 30px;
    display: flex;
    justify-content: flex-end;
}

.save-button {
    padding: 12px 28px;
    background: linear-gradient(135deg,#2575fc,#6a11cb);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.25s;
}

.save-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

/* ===== MOBILE ===== */
@media(max-width:768px){
    .profile-wrapper{
        grid-template-columns: 1fr;
    }
    .profile-sidebar{
        text-align:center;
    }
}
</style>

</head>
<body>

<div class="profile-wrapper">

    <!-- SIDEBAR -->
    <div class="profile-sidebar">
        <img src="https://i.imgur.com/1X7QpJ6.png" class="avatar">
        <div class="profile-name"><?= htmlspecialchars($user['Username']) ?></div>
        <div class="profile-email"><?= htmlspecialchars($user['Email']) ?></div>

        <div class="profile-menu">
            <a href="#">👤 Thông tin cá nhân</a>
            <a href="index.php?page=order_history">📦 Đơn hàng của tôi</a>
            <a href="#">🔒 Đổi mật khẩu</a>
            <a href="logout.php">🚪 Đăng xuất</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="profile-content">
        <div class="profile-title">Thông tin tài khoản</div>

        <form method="POST" class="profile-form">

            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['Username']) ?>" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" required>
            </div>

            <div class="full">
                <label>Số điện thoại</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['Phone']) ?>">
            </div>

            <div class="profile-actions full">
                <button type="submit" class="save-button">💾 Lưu thông tin</button>
            </div>

        </form>
    </div>

</div>


</body>
</html>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'App/Model/user.php';
$userObj = new User();

// Nếu đã login → chuyển về home
if (isset($_SESSION['user_id'])) {
    header("Location: index.php?page=home");
    exit;
}

// Mảng lỗi đăng ký / login
$register_errors = [];
$login_error = '';
$register_success = '';

/* ===========================
    XỬ LÝ ĐĂNG KÝ
=========================== */
if (isset($_POST['register'])) {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $repassword = $_POST['repassword'];

    if (empty($username) || empty($email) || empty($password) || empty($repassword)) {
        $register_errors[] = "Vui lòng điền đầy đủ thông tin.";
    }
    if ($password !== $repassword) {
        $register_errors[] = "Mật khẩu nhập lại không khớp.";
    }
    if (strlen($password) < 6) {
        $register_errors[] = "Mật khẩu phải ít nhất 6 ký tự.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_errors[] = "Email không hợp lệ.";
    }

    // Kiểm tra username/email tồn tại
    if (empty($register_errors)) {
        $check = $userObj->db->getConnection()->prepare(
            "SELECT id_User FROM user WHERE Username = ? OR Email = ?"
        );
        $check->execute([$username, $email]);
        if ($check->rowCount() > 0) {
            $register_errors[] = "Tên đăng nhập hoặc email đã được sử dụng.";
        }
    }

    // Thêm tài khoản
    if (empty($register_errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $userObj->db->getConnection()->prepare(
            "INSERT INTO user (Username, Password, Email, Role, status) 
             VALUES (?, ?, ?, 'customer', 'offline')"
        );
        if ($stmt->execute([$username, $hashed, $email])) {
            $register_success = "Đăng ký thành công! Hãy đăng nhập ngay.";
        } else {
            $register_errors[] = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    }
}

if (isset($_POST['login'])) {
    $user_or_email = trim($_POST['user_or_email']);
    $password = $_POST['password'];

    if (empty($user_or_email) || empty($password)) {
        $login_error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $stmt = $userObj->db->getConnection()->prepare(
            "SELECT * FROM user WHERE Username = ? OR Email = ?"
        );
        $stmt->execute([$user_or_email, $user_or_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {

            /* 🔥 Cập nhật trạng thái ONLINE ngay khi đăng nhập */
            $update = $userObj->db->getConnection()->prepare(
                "UPDATE user SET status = 'online' WHERE id_User = ?"
            );
            $update->execute([$user['id_User']]);

            // Lưu session
            $_SESSION['user_id'] = $user['id_User'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];

            header("Location: index.php?page=home");
            exit();
        } else {
            $login_error = "Tên đăng nhập/email hoặc mật khẩu không đúng.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng ký / Đăng nhập - 5SV Sport Fashion</title>
<link rel="stylesheet" href="App/public/shop/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .error-msg { background:#fdf2f2; color:#e74c3c; padding:12px; border-radius:8px; text-align:center; margin:10px 0; font-size:14px; }
    .success-msg { background:#e6ffed; color:#2a7a2a; padding:12px; border-radius:8px; text-align:center; margin:10px 0; font-size:14px; }
    .auth-tabs .tab-btn.active { color: #e31e24 !important; border-bottom: 4px solid #e31e24 !important; }
</style>
</head>
<body>

<div class="auth-page">
    <div class="auth-container">
        <!-- Ảnh bên trái -->
        <div class="auth-image">
            <img src="App/public/img/anhtrangdangky.png" alt="5SV Sport Couple">
        </div>

        <!-- Form bên phải -->
        <div class="auth-form">
            <div class="auth-tabs">
                <div class="tab-btn <?= !empty($register_success) ? '' : 'active' ?>" onclick="showTab('register')">Đăng ký</div>
                <div class="tab-btn <?= !empty($register_success) ? 'active' : '' ?>" onclick="showTab('login')">Đăng nhập</div>
            </div>

            <!-- Thông báo đăng ký thành công -->
            <?php if(!empty($register_success)): ?>
                <div class="success-msg"><?= $register_success ?></div>
            <?php endif; ?>

            <!-- Form Đăng ký -->
            <div id="register-form" class="form-wrapper" style="display:<?= empty($register_success) ? 'block' : 'none' ?>;">
                <?php if(!empty($register_errors)): ?>
                    <div class="error-msg"><?= implode('<br>', $register_errors) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ E-mail</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" name="repassword" required>
                    </div>
                    <button type="submit" name="register" class="btn-submit">Đăng ký</button>
                </form>
            </div>

            <!-- Form Đăng nhập -->
            <div id="login-form" class="form-wrapper" style="display:<?= !empty($register_success) ? 'block' : 'none' ?>;">
                <?php if(!empty($login_error)): ?>
                    <div class="error-msg"><?= $login_error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập hoặc E-mail</label>
                        <input type="text" name="user_or_email" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn-submit">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    const tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(t => t.classList.remove('active'));
    
    if (tab === 'register') {
        tabs[0].classList.add('active');
        document.getElementById('register-form').style.display = 'block';
        document.getElementById('login-form').style.display = 'none';
    } else {
        tabs[1].classList.add('active');
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('login-form').style.display = 'block';
    }
}

// Tự động chuyển tab login nếu đăng ký thành công
<?php if (!empty($register_success)) echo "showTab('login');"; ?>
</script>
</body>
</html>

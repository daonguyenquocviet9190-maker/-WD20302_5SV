<?php
session_start();
include_once 'App/Model/user.php';        // Đường dẫn đúng từ View/shop đến Model

$userObj = new User();

// ------------------- XỬ LÝ ĐĂNG KÝ -------------------
if (isset($_POST['register'])) {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $repassword = $_POST['repassword'];

    $errors = [];

    // Validate
    if (empty($username) || empty($email) || empty($password) || empty($repassword)) {
        $errors[] = "Vui lòng điền đầy đủ thông tin.";
    }
    if ($password !== $repassword) {
        $errors[] = "Mật khẩu nhập lại không khớp.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Mật khẩu phải ít nhất 6 ký tự.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    }

    // Kiểm tra trùng username hoặc email
    if (empty($errors)) {
        $sql  = "SELECT id_User FROM user WHERE Username = ? OR Email = ?";
        $stmt = $userObj->db->getConnection()->prepare($sql);   // Sửa lỗi private $conn
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Tên đăng nhập hoặc email đã được sử dụng.";
        }
    }

    // Đăng ký nếu không có lỗi
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql  = "INSERT INTO user (Username, Password, Email, Role) VALUES (?, ?, ?, 'customer')";
        $stmt = $userObj->db->getConnection()->prepare($sql);   // Sửa lỗi private $conn
        $result = $stmt->execute([$username, $hashed_password, $email]);

        if ($result) {
            echo "<script>alert('Đăng ký thành công! Hãy đăng nhập ngay.'); showTab('login');</script>";
        } else {
            $errors[] = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    }

    // Hiển thị lỗi đăng ký
    if (!empty($errors)) {
        echo '<div style="color:#e74c3c; background:#fdf2f2; padding:12px; border-radius:8px; margin:15px 0; text-align:center;">'
             . implode('<br>', $errors) . '</div>';
    }
}

// ------------------- XỬ LÝ ĐĂNG NHẬP -------------------
if (isset($_POST['login'])) {
    $user_or_email = trim($_POST['user_or_email']);
    $password      = $_POST['password'];

    if (empty($user_or_email) || empty($password)) {
        $login_error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $sql  = "SELECT * FROM user WHERE Username = ? OR Email = ?";
        $stmt = $userObj->db->getConnection()->prepare($sql);   // Sửa lỗi private $conn
        $stmt->execute([$user_or_email, $user_or_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id']  = $user['id_User'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role']     = $user['Role'];

            header("Location: index.php?page=home");
            exit();
        } else {
            $login_error = "Tên đăng nhập/email hoặc mật khẩu không đúng.";
        }
    }

    if (!empty($login_error)) {
        echo '<div style="color:#e74c3c; background:#fdf2f2; padding:12px; border-radius:8px; margin:15px 0; text-align:center;">'
             . $login_error . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký / Đăng nhập - 5SV Sport Fashion</title>
    <link rel="stylesheet" href="App/public/shop/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-image">
            <img src="App/public/img/anhtrangdangky.png" alt="5SV Sport Couple">
        </div>
        <div class="auth-form">
            <div class="auth-tabs">
                <div class="tab-btn active" onclick="showTab('register')">Đăng ký</div>
                <div class="tab-btn" onclick="showTab('login')">Đăng nhập</div>
            </div>

            <!-- FORM ĐĂNG KÝ -->
            <div id="register-form" class="form-wrapper">
                <form method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text" name="username" placeholder="Viết liền không dấu" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ E-mail</label>
                        <input type="email" name="email" placeholder="example@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" placeholder="Ít nhất 6 ký tự" required>
                    </div>
                    <div class="form-group">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" name="repassword" placeholder="Nhập lại mật khẩu" required>
                    </div>

                    <button type="button" class="btn-google">
                        <img src="App/public/img/google.png" width="20"> Continue with Google
                    </button>

                    <button type="submit" name="register" class="btn-submit">Đăng ký</button>
                </form>
            </div>

            <!-- FORM ĐĂNG NHẬP -->
            <div id="login-form" class="form-wrapper" style="display:none;">
                <form method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập hoặc Email</label>
                        <input type="text" name="user_or_email" placeholder="Nhập tên đăng nhập hoặc email" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>

                    <button type="button" class="btn-google">
                        <img src="App/public/img/google.png" width="20"> Continue with Google
                    </button>

                    <button type="submit" name="login" class="btn-submit">Đăng nhập</button>
                </form>

                <div class="auth-footer">
                    <a href="#">Quên mật khẩu?</a>
                </div>
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
</script>

</body>
</html>
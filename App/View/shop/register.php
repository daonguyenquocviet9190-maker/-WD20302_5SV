<?php
session_start();
include_once 'App/Model/user.php';
$userObj = new User();

// XỬ LÝ ĐĂNG KÝ
if (isset($_POST['register'])) {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $repassword = $_POST['repassword'];

    $errors = [];

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

    if (empty($errors)) {
        $check = $userObj->db->getConnection()->prepare("SELECT id_User FROM user WHERE Username = ? OR Email = ?");
        $check->execute([$username, $email]);
        if ($check->rowCount() > 0) {
            $errors[] = "Tên đăng nhập hoặc email đã được sử dụng.";
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (Username, Password, Email, Role) VALUES (?, ?, ?, 'customer')";
        $stmt = $userObj->db->getConnection()->prepare($sql);
        if ($stmt->execute([$username, $hashed, $email])) {
            echo "<script>alert('Đăng ký thành công! Hãy đăng nhập ngay.'); showTab('login');</script>";
        } else {
            $errors[] = "Có lỗi xảy ra, vui lòng thử lại.";
        }
    }

    if (!empty($errors)) {
        echo '<div class="error-msg">' . implode('<br>', $errors) . '</div>';
    }
}

// XỬ LÝ ĐĂNG NHẬP
if (isset($_POST['login'])) {
    $user_or_email = trim($_POST['user_or_email']);
    $password = $_POST['password'];

    if (empty($user_or_email) || empty($password)) {
        $login_error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $stmt = $userObj->db->getConnection()->prepare("SELECT * FROM user WHERE Username = ? OR Email = ?");
        $stmt->execute([$user_or_email, $user_or_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['id_User'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];
            header("Location: index.php?page=home");
            exit();
        } else {
            $login_error = "Tên đăng nhập/email hoặc mật khẩu không đúng.";
        }
    }
    if (!empty($login_error)) {
        echo '<div class="error-msg">' . $login_error . '</div>';
    }
}
?>

    <style>
        /* CSS bổ sung để giống 100% ảnh bạn gửi */
        .error-msg {
            background: #fdf2f2;
            color: #e74c3c;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin: 15px 0;
            font-size: 14px;
        }
        .auth-tabs .tab-btn.active {
            color: #e31e24 !important;
            border-bottom: 4px solid #e31e24 !important;
        }
        .btn-google img {
            width: 20px;
            height: 20px;
        }
    </style>

<div class="auth-page">
    <div class="auth-container">
        <!-- Ảnh bên trái -->
        <div class="auth-image">
            <img src="App/public/img/anhtrangdangky.png" alt="5SV Sport Couple">
        </div>

        <!-- Form bên phải -->
        <div class="auth-form">
            <div class="auth-tabs">
                <div class="tab-btn active" onclick="showTab('register')">Đăng ký</div>
                <div class="tab-btn" onclick="showTab('login')">Đăng nhập</div>
            </div>

            <!-- Form Đăng ký -->
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
                        <img src="https://www.google.com/favicon.ico" alt="Google"> Continue with Google
                    </button>

                    <button type="submit" name="register" class="btn-submit">Đăng ký</button>
                </form>
            </div>

            <!-- Form Đăng nhập -->
            <div id="login-form" class="form-wrapper" style="display:none;">
                <form method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập hoặc E-mail</label>
                        <input type="text" name="user_or_email" placeholder="Nhập tên đăng nhập hoặc email" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>

                    <button type="button" class="btn-google">
                        <img src="https://www.google.com/favicon.ico" alt="Google"> Continue with Google
                    </button>

                    <div style="display:flex; align-items:center; justify-content:space-between; margin-top:15px;">
                        <a href="#" style="color:#e31e24; font-size:14px;">Forgot your password?</a>
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

// Tự động chuyển tab khi đăng ký thành công
<?php if (isset($_POST['register']) && empty($errors)) echo "showTab('login');"; ?>
</script>
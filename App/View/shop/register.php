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
            <!-- dang ky -->
            <div id="register-form" class="form-wrapper">
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text" name="username" placeholder="Viết liền không dấu" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ E-mail</label>
                        <input type="email" name="email" placeholder="example@gmail.com" required>
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
                        <img src="App/public/img/google.png" width="20" alt="Google">
                        Continue with Google
                    </button>

                    <button type="submit" name="register" class="btn-submit">Đăng ký</button>
                </form>
            </div>

            <!-- dang nhap-->
            <div id="login-form" class="form-wrapper" style="display:none;">
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Tên đăng nhập hoặc Email</label>
                        <input type="text" name="user_or_email" placeholder="Nhập tên đăng nhập hoặc email" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>

                    <button type="button" class="btn-google">
                        <img src="App/public/img/google.png" width="20" alt="Google">
                        Continue with Google
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
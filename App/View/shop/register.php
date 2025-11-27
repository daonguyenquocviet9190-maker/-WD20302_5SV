<?php
// App/View/shop/register.php
// ĐÃ SỬA HOÀN HẢO – giống hệt web 5SV chính thức
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký / Đăng nhập - 5SV Sport Fashion</title>
    <link rel="stylesheet" href="App/public/shop/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #f9f9f9; }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(to bottom, #ffecec, #ffffff);
        }

        .auth-container {
            width: 100%;
            max-width: 1100px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0,0,0,0.12);
            display: flex;
            height: 680px;
        }

        /* Ảnh bên trái */
        .auth-image {
            flex: 1;
            overflow: hidden;
        }
        .auth-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.4s ease;
        }
        .auth-image:hover img {
            transform: scale(1.05);
        }

        /* Form bên phải */
        .auth-form {
            flex: 1;
            padding: 60px 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fff;
        }

        .auth-tabs {
            display: flex;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
        }
        .tab-btn {
            flex: 1;
            padding: 16px 0;
            text-align: center;
            font-size: 21px;
            font-weight: 600;
            color: #999;
            cursor: pointer;
            transition: all 0.3s;
        }
        .tab-btn.active {
            color: #e31e24;
            border-bottom: 4px solid #e31e24;
        }

        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 16px 18px;
            border: 1.5px solid #ddd;
            border-radius: 12px;
            font-size: 16px;
            transition: border 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #e31e24;
        }
        .form-group input::placeholder {
            color: #aaa;
        }

        .btn-google {
            background: #fff;
            border: 1.5px solid #ddd;
            padding: 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 16px;
            font-weight: 500;
            margin: 30px 0 20px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .btn-google:hover {
            border-color: #ccc;
            background: #fdfdfd;
        }

        .btn-submit {
            background: #e31e24;
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }
        .btn-submit:hover {
            background: #c81b20;
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
        }
        .auth-footer a {
            color: #e31e24;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
        }

        /* Đảm bảo 2 form căn giữa giống hệt nhau */
        #register-form, #login-form > div {
            min-height: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @media (max-width: 992px) {
            .auth-container { 
                flex-direction: column; 
                height: auto; 
            }
            .auth-image { height: 320px; }
            .auth-form { padding: 50px 40px; }
        }
        @media (max-width: 576px) {
            .auth-form { padding: 40px 25px; }
            .tab-btn { font-size: 18px; }
        }
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
                <div class="tab-btn active" onclick="showTab('register')">Đăng ký</div>
                <div class="tab-btn" onclick="showTab('login')">Đăng nhập</div>
            </div>

            <!-- FORM ĐĂNG KÝ -->
            <div id="register-form">
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

            <!-- FORM ĐĂNG NHẬP – ĐÃ CĂN GIỮA HOÀN HẢO -->
            <div id="login-form" style="display:none;">
                <div> <!-- Wrapper để căn giữa giống form đăng ký -->
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
<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . "/../../Model/database.php";

// Kết nối DB với PDO và bật lỗi
try {
    $db = new Database("localhost", "5svcode", "root", "");
    $pdo = $db->connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối DB: " . $e->getMessage());
}

// Xử lý gửi form
$send_status = "";
if (isset($_POST['send_contact'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($fullname && $email && $subject && $message) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts (fullname, email, phone, subject, message) VALUES (:fullname, :email, :phone, :subject, :message)");
            $stmt->execute([
                ':fullname' => $fullname,
                ':email' => $email,
                ':phone' => $phone,
                ':subject' => $subject,
                ':message' => $message
            ]);
            $send_status = "success";

            // Giả lập gửi mail
            // Không thực sự gửi, chỉ hiện thông báo
        } catch (PDOException $e) {
            $send_status = "error";
        }
    } else {
        $send_status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            color: #333;
        }

        .contact-page {
            padding: 30px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .contact-header h2 {
            font-size: 38px;
            color: #007bff;
            margin-bottom: 12px;
        }

        .contact-header p {
            color: #555;
            font-size: 16px;
            max-width: 650px;
            margin: 0 auto;
        }

        .contact-container {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .contact-form {
            flex: 1 1 55%;
            background: #fff;
            padding: 45px;
            border-radius: 15px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        .contact-form h3 {
            margin-bottom: 28px;
            color: #007bff;
            font-size: 26px;
        }

        .contact-form .form-group {
            margin-bottom: 20px;
        }

        .contact-form label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #007bff;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            background: #f9faff;
        }

        .contact-form button {
            background: linear-gradient(45deg, #007bff, #00bfff);
            color: #fff;
            border: none;
            padding: 14px 35px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .contact-form button:hover {
            background: linear-gradient(45deg, #0056b3, #0099cc);
        }

        .alert-success {
            padding: 14px 20px;
            background: #d4edda;
            color: #155724;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
            opacity: 1;
            transition: opacity 0.5s;
        }

        .alert-success .close-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-weight: 700;
            font-size: 18px;
        }

        .contact-info {
            flex: 1 1 40%;
            background: linear-gradient(135deg, #e0f2ff, #cfe9ff);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
        }

        .contact-info h3 {
            color: #007bff;
            margin-bottom: 25px;
            font-size: 26px;
        }

        .contact-info ul {
            list-style: none;
            padding: 0;
            margin: 0 0 25px 0;
        }

        .contact-info ul li {
            margin-bottom: 18px;
            font-size: 15px;
            color: #333;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            word-break: break-word;
            white-space: nowrap;
            /* Cho phép xuống dòng nếu quá dài */
        }

        .contact-info ul li span {
            display: inline-block;
            white-space: normal;
            /* Giúp text dài trong span xuống dòng tự nhiên */
        }

        .contact-info ul li i {
            color: #007bff;
            font-size: 18px;
            margin-top: 3px;
        }

        .contact-info .map iframe {
            border-radius: 12px;
            width: 100%;
            height: 300px;
            border: 0;
        }

        @media screen and (max-width:992px) {
            .contact-container {
                flex-direction: column;
            }

            .contact-form,
            .contact-info {
                flex: 1 1 100%;
            }
        }
    </style>
    <script>
        function closeAlert() {
            const alert = document.getElementById('alertBox');
            alert.style.opacity = '0';
            setTimeout(() => { alert.style.display = 'none'; }, 500);
        }

        // Validate cơ bản
        function validateForm() {
            const email = document.forms["contactForm"]["email"].value;
            const phone = document.forms["contactForm"]["phone"].value;
            if (email && !/\S+@\S+\.\S+/.test(email)) { alert("Email không hợp lệ!"); return false; }
            if (phone && !/^[0-9\+\-\s]+$/.test(phone)) { alert("Số điện thoại không hợp lệ!"); return false; }
            return true;
        }

        // Reload sau 2s nếu gửi thành công
        <?php if ($send_status == "success"): ?>
            setTimeout(function () {
                window.location.href = window.location.href;
            }, 2000);
        <?php endif; ?>
    </script>
</head>

<body>

    <div class="contact-page">
        <div class="contact-header">
            <h2>Liên Hệ Với Chúng Tôi</h2>
            <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn. Hãy gửi thông tin bên dưới và chúng tôi sẽ phản hồi sớm nhất!</p>
        </div>

        <div class="contact-container">
            <div class="contact-form">
                <h3>Gửi tin nhắn</h3>

                <?php if ($send_status == "success"): ?>
                    <div class="alert-success" id="alertBox">
                        Gửi liên hệ thành công!
                        <span class="close-btn" onclick="closeAlert()">&times;</span>
                    </div>
                <?php endif; ?>

                <?php if ($send_status != "success"): ?>
                    <form id="contactForm" name="contactForm" action="" method="post" onsubmit="return validateForm();">
                        <div class="form-group">
                            <label>Họ và tên <span>*</span></label>
                            <input type="text" name="fullname" required placeholder="Nhập họ và tên">
                        </div>
                        <div class="form-group">
                            <label>Email <span>*</span></label>
                            <input type="email" name="email" required placeholder="Nhập email">
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="form-group">
                            <label>Chủ đề <span>*</span></label>
                            <input type="text" name="subject" required placeholder="Nhập chủ đề">
                        </div>
                        <div class="form-group">
                            <label>Nội dung <span>*</span></label>
                            <textarea name="message" rows="6" required placeholder="Nhập nội dung"></textarea>
                        </div>
                        <button type="submit" name="send_contact">Gửi tin nhắn</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="contact-info">
                <h3>Thông tin liên hệ</h3>
                <ul>
                    <li><i class="bi bi-geo-alt-fill"></i><strong>Địa chỉ:</strong> <span>Số 1, Đường B, Khu ADC, Phường
                            Phú Thạnh, Quận Tân Phú, TP. HCM, Việt Nam</span></li>
                    <li><i class="bi bi-envelope-fill"></i><strong>Email:</strong> support@example.com</li>
                    <li><i class="bi bi-telephone-fill"></i><strong>Hotline:</strong> 0123 456 789</li>
                </ul>
                <div class="map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.123456789!2d106.619123!3d10.789123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752fa123456789%3A0xabcdef123456789!2sS%E1%BB%91%201%2C%20%C4%90%C6%B0%E1%BB%9Dng%20B%2C%20Khu%20ADC%2C%20Ph%C6%B0%E1%BB%9Dng%20Ph%C3%BA%20Th%E1%BA%A7nh%2C%20Qu%E1%BA%ADn%20T%C3%A2n%20Ph%C3%BA%2C%20TP.%20HCM!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s"
                        allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
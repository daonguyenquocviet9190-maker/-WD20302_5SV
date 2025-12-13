    <?php
    if(session_status()===PHP_SESSION_NONE) session_start();

    require_once __DIR__ . "/../../../App/Model/user.php";
    require_once __DIR__ . "/../../../vendor/autoload.php";
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $userObj = new User();
    $pdo = $userObj->db->getConnection();

    // --- Cấu hình mail ---
    define('SMTP_HOST','smtp.gmail.com');
    define('SMTP_PORT',587);
    define('SMTP_USER','tronghoainguyen5@gmail.com');
    define('SMTP_PASS','mhawycgdgupsfuhk');
    define('SMTP_FROM','tronghoainguyen5@gmail.com');
    define('SMTP_FROM_NAME','5SV Sport');

    function sendOtpEmail($toEmail,$otp){
        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom(SMTP_FROM,SMTP_FROM_NAME);
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'OTP xác thực - 5SV Sport';
            $mail->Body = "<h3>Mã OTP của bạn: <b>$otp</b></h3><p>Có hiệu lực 5 phút</p>";
            $mail->send();
            return true;
        }catch(Exception $e){
            error_log($e->getMessage());
            return false;
        }
    }

    function normalizePhonePlus($phone){
        $phone = preg_replace('/\D+/','',$phone);
        if(substr($phone,0,1)=='0') return '+84'.substr($phone,1);
        elseif(substr($phone,0,2)=='84') return '+'.$phone;
        elseif(substr($phone,0,3)=='+84') return $phone;
        else return '+84'.$phone;
    }

    // --- Kiểm tra login ---
    if(!isset($_SESSION['user_id'])){
        echo "<script>alert('Bạn cần đăng nhập'); window.location='index.php?page=login';</script>";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id_User=?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user) exit("User không tồn tại");

    // --- Đường dẫn upload và URL ---
    $uploadDir = __DIR__ . '/../../../uploads/avatars/';
    $baseURL = '/-WD20302_5SV/uploads/avatars/';

    // avatar mặc định
    $avatarSrc = !empty($user['Avatar']) ? $baseURL.$user['Avatar'].'?t='.time() : '/-WD20302_5SV/App/public/img/default-avatar.avif';

    $errors=[]; $success='';
    $showEdit=false; $showOtp=false; $showConfirm=false; $showSuccess=false;

    // --- Mở modal edit ---
    if(isset($_POST['open_edit'])) $showEdit=true;

    // --- Gửi OTP ---
    if(isset($_POST['send_profile_otp'])){
        $pending=[
            'username'=>trim($_POST['username']),
            'email'=>trim($_POST['email']),
            'phone'=>normalizePhonePlus(trim($_POST['phone'])),
            'avatar_temp'=>null,
            'otp'=>rand(100000,999999),
            'otp_time'=>time()
        ];

        // Upload avatar tạm
        if(isset($_FILES['avatar']) && $_FILES['avatar']['error']==0){
            $ext=strtolower(pathinfo($_FILES['avatar']['name'],PATHINFO_EXTENSION));
            if(in_array($ext,['jpg','jpeg','png','gif','webp'])){
                if(!is_dir($uploadDir.'temp/')) mkdir($uploadDir.'temp/',0755,true);
                $tempName='tmp_user_'.$user_id.'_'.time().'.'.$ext;
                $tempPath=$uploadDir.'temp/'.$tempName;
                if(move_uploaded_file($_FILES['avatar']['tmp_name'],$tempPath)){
                    $pending['avatar_temp']=$tempName;
                } else $errors[]="Upload avatar thất bại!";
            } else $errors[]="File avatar không hợp lệ!";
        }

        $_SESSION['profile_pending']=$pending;

        // Lưu OTP
        $stmt=$pdo->prepare("INSERT INTO otp_codes(email,otp,created_at,expires_at) VALUES(?,?,NOW(),DATE_ADD(NOW(),INTERVAL 5 MINUTE))");
        $stmt->execute([$pending['email'],$pending['otp']]);

        if(sendOtpEmail($pending['email'],$pending['otp'])){
            $success="OTP đã gửi tới ".$pending['email'];
            $showOtp=true;
        } else $errors[]="Gửi OTP thất bại";
    }

    // --- Gửi lại OTP ---
    if(isset($_POST['resend_profile_otp']) && isset($_SESSION['profile_pending'])){
        $pending=$_SESSION['profile_pending'];
        if(time()-$pending['otp_time']<20) $errors[]="Vừa gửi OTP, vui lòng chờ vài giây";
        else{
            $pending['otp']=rand(100000,999999);
            $pending['otp_time']=time();
            $_SESSION['profile_pending']=$pending;

            $stmt=$pdo->prepare("INSERT INTO otp_codes(email,otp,created_at,expires_at) VALUES(?,?,NOW(),DATE_ADD(NOW(),INTERVAL 5 MINUTE))");
            $stmt->execute([$pending['email'],$pending['otp']]);

            if(sendOtpEmail($pending['email'],$pending['otp'])){
                $success="OTP đã gửi lại tới ".$pending['email'];
                $showOtp=true;
            } else $errors[]="Gửi lại OTP thất bại";
        }
    }

    // --- Verify OTP ---
    // --- Verify OTP ---
    if(isset($_POST['verify_profile_otp']) && isset($_SESSION['profile_pending'])){
        $input_otp=trim($_POST['otp']);
        $pending=$_SESSION['profile_pending'];

        $stmt=$pdo->prepare("SELECT * FROM otp_codes WHERE email=? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$pending['email']]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) {
            $errors[]="OTP không tìm thấy";
            $showOtp = true; // hiển thị lại modal OTP
        } elseif($row['otp']!=$input_otp) {
            $errors[]="OTP không đúng";
            $showOtp = true; // hiển thị lại modal OTP
        } elseif(strtotime($row['expires_at'])<time()) {
            $errors[]="OTP hết hạn";
            $showEdit = true; // mở lại modal edit để gửi OTP mới
        } else {
            $showConfirm=true;
        }
    }


    // --- Final save ---
    if(isset($_POST['final_save']) && isset($_SESSION['profile_pending'])){
        $pending=$_SESSION['profile_pending'];
        $avatarFilename = $user['Avatar'];

        if(!empty($pending['avatar_temp'])){
            $ext = pathinfo($pending['avatar_temp'],PATHINFO_EXTENSION);
            $finalName='user_'.$user_id.'_'.time().'.'.$ext;
            if(rename($uploadDir.'temp/'.$pending['avatar_temp'],$uploadDir.$finalName)){
                $avatarFilename = $finalName;
            }
        }

        $stmt=$pdo->prepare("UPDATE user SET Username=?, Email=?, Phone=?, Avatar=? WHERE id_User=?");
        $stmt->execute([$pending['username'],$pending['email'],$pending['phone'],$avatarFilename,$user_id]);
        unset($_SESSION['profile_pending']);
        $showSuccess=true;

        $avatarSrc = $baseURL.$avatarFilename.'?t='.time();
        $user['Username']=$pending['username'];
        $user['Email']=$pending['email'];
        $user['Phone']=$pending['phone'];
        $user['Avatar']=$avatarFilename;
    }
    ?>

    <!DOCTYPE html>
    <html lang="vi">
    <head>
    <meta charset="UTF-8">
    <title>Profile - 5SV Sport</title>
    <style>
    /* CSS giữ nguyên như trước */
    body{background:#eef1f6;margin:0;padding:0;font-family:Arial,sans-serif;}
    .profile-wrapper{max-width:1150px;margin:50px auto;background:#fff;border-radius:16px;overflow:hidden;display:grid;grid-template-columns:300px 1fr;box-shadow:0 20px 50px rgba(0,0,0,.1);}
    .profile-sidebar{background:linear-gradient(135deg,#6a11cb,#2575fc);padding:50px 25px;color:#fff;text-align:center}
    .avatar{width:120px;height:120px;border-radius:50%;border:4px solid rgba(255,255,255,0.2);object-fit:cover}
    .profile-name{font-size:22px;font-weight:700;margin-top:12px}
    .profile-email{opacity:.9;margin-top:4px}
    .profile-menu{margin-top:35px;text-align:left}
    .profile-menu form, .profile-menu a{display:block;margin:8px 0}
    .profile-menu button{width:100%;padding:12px;background:#fff;color:#2575fc;border:none;border-radius:10px;cursor:pointer;font-weight:600}
    .profile-menu button:hover{background:#e6e6e6}
    .profile-content{padding:40px}
    .profile-title{font-size:28px;font-weight:700;margin-bottom:18px}
    .info-card{background:#fbfdff;padding:22px;border:1px solid #eef2f7;border-radius:14px;margin-bottom:20px}
    .info-row{display:flex;gap:15px;border-bottom:1px dashed #eee;padding:12px 0}
    .info-row:last-child{border-bottom:none}
    .info-icon{width:46px;height:46px;border-radius:10px;background:#eef5ff;display:flex;align-items:center;justify-content:center;font-size:20px;color:#0b63d0}
    .value{margin-top:4px;font-size:17px;font-weight:600;color:#1b2b45}
    /* Modal */
    .modal-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.55);display:flex;justify-content:center;align-items:center;z-index:9999;opacity:0;pointer-events:none;transition:all 0.3s;}
    .modal-overlay.show{opacity:1;pointer-events:auto;}
    .modal-box{width:400px;background:#fff;border-radius:14px;padding:22px;transform:scale(0.85);transition:all 0.25s ease-in-out;opacity:0;position:relative;}
    .modal-overlay.show .modal-box{transform:scale(1);opacity:1;}
    .modal-title{font-size:20px;font-weight:600;text-align:center;margin-bottom:18px;}
    .modal-box input{width:100%;padding:12px;margin:8px 0;border-radius:10px;border:1px solid #d4d7dd;font-size:15px;}
    .btn-modal{width:100%;padding:12px;background:#2575fc;color:#fff;border:none;border-radius:8px;cursor:pointer;margin-top:10px;transition:all 0.2s;}
    .btn-modal:hover{background:#1f5bcc;}
    .modal-close{position:absolute;top:10px;right:14px;font-size:22px;background:none;border:none;color:#888;cursor:pointer;}
    .modal-close:hover{color:#333;}
    .otp-timer{text-align:center;margin-top:8px;font-size:14px;color:#555;}
    .profile-menu a {display:block;padding:12px 15px;margin:8px 0;background: rgba(255,255,255,0.15);color: #fff;border-radius: 10px;text-decoration:none;font-weight:600;transition: all 0.2s ease;}
    .profile-menu a:hover{background: rgba(255,255,255,0.3);transform: translateX(4px);}
    </style>
    <script>
    let countdown=300;
    function startCountdown(){
        const el=document.querySelector('.otp-timer');
        if(!el) return;
        const interval=setInterval(()=>{
            let m=Math.floor(countdown/60).toString().padStart(2,'0');
            let s=(countdown%60).toString().padStart(2,'0');
            el.textContent=`${m}:${s}`;
            countdown--;
            if(countdown<0) clearInterval(interval);
        },1000);
    }
    window.onload=startCountdown;

    function previewAvatar(input){
        if(input.files && input.files[0]){
            const reader = new FileReader();
            reader.onload = function(e){
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function closeModal(id){document.getElementById(id).classList.remove('show');}
    </script>
    </head>
    <body>
    <div class="profile-wrapper">
    <aside class="profile-sidebar">
        <img src="<?= $avatarSrc ?>" class="avatar" alt="avatar">
        <div class="profile-name"><?= htmlspecialchars($user['Username']) ?></div>
        <div class="profile-email"><?= htmlspecialchars($user['Email']) ?></div>
        <div class="profile-menu">
            <form method="post"><button type="submit" name="open_edit">✏️ Chỉnh sửa thông tin</button></form>
            <a href="index.php?page=order_history">📦 Đơn hàng</a>
            <a href="#">🔒 Đổi mật khẩu</a>
            <a href="index.php?page=logout">🚪 Đăng xuất</a>
        </div>
    </aside>
    <section class="profile-content">
        <div class="profile-title">Thông tin tài khoản</div>
        <div class="info-card">
            <div class="info-row"><div class="info-icon">👤</div><div><div class="label">Username</div><div class="value"><?= htmlspecialchars($user['Username']) ?></div></div></div>
            <div class="info-row"><div class="info-icon">📧</div><div><div class="label">Email</div><div class="value"><?= htmlspecialchars($user['Email']) ?></div></div></div>
            <div class="info-row"><div class="info-icon">📱</div><div><div class="label">Số điện thoại</div><div class="value"><?= htmlspecialchars($user['Phone']) ?></div></div></div>
        </div>
        <?php if($errors){foreach($errors as $e) echo '<div style="color:red;margin-bottom:8px;">'.$e.'</div>';} ?>
        <?php if($success) echo '<div style="color:green;margin-bottom:8px;">'.$success.'</div>'; ?>
    </section>
    </div>

    <!-- Modal Edit -->
    <?php if($showEdit): ?>
    <div class="modal-overlay show" id="editModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        <div class="modal-title">Chỉnh sửa thông tin</div>
        <form method="post" enctype="multipart/form-data">
            <img id="avatarPreview" src="<?= $avatarSrc ?>" class="avatar" style="margin-bottom:10px;">
            <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(this)">
            <input type="text" name="username" value="<?= htmlspecialchars($user['Username']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" required>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['Phone']) ?>" required>
            <button class="btn-modal" type="submit" name="send_profile_otp">Gửi OTP & Tiếp tục</button>
        </form>
    </div>
    </div>
    <?php endif; ?>

    <!-- Modal OTP -->
    <?php if($showOtp): $pending=$_SESSION['profile_pending']; ?>
    <div class="modal-overlay show" id="otpModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('otpModal')">&times;</button>
        <div class="modal-title">Xác thực OTP</div>
        <form method="post">
            <p>Mã OTP đã gửi tới: <b><?= htmlspecialchars($pending['email']) ?></b></p>
            <input type="text" name="otp" placeholder="Nhập OTP" required>
            <div class="otp-timer">05:00</div>
            <button class="btn-modal" type="submit" name="verify_profile_otp">Xác nhận OTP</button>
            <button class="btn-modal" type="submit" name="resend_profile_otp">Gửi lại OTP</button>
        </form>
    </div>
    </div>
    <?php endif; ?>

    <!-- Modal Confirm -->
    <?php if($showConfirm): ?>
    <div class="modal-overlay show" id="confirmModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('confirmModal')">&times;</button>
        <div class="modal-title">Xác nhận lưu</div>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="final_save" value="1">
            <button class="btn-modal" type="submit">Lưu thông tin</button>
        </form>
    </div>
    </div>
    <?php endif; ?>

    <!-- Modal Success -->
    <?php if($showSuccess): ?>
    <div class="modal-overlay show" id="successModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('successModal')">&times;</button>
        <div class="modal-title">✔️ Thành công</div>
        <p>Thông tin đã được cập nhật.</p>
    </div>
    </div>
    <script>
    setTimeout(()=>closeModal('successModal'),2000);
    </script>
    <?php endif; ?>
    </body>
    </html>

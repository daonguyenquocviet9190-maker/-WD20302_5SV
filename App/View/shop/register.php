<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include_once __DIR__ . '/../../../App/Model/user.php';

// --- Kiểm tra autoload composer ---
$autoload_path = __DIR__ . '/../../../vendor/autoload.php';
if(!file_exists($autoload_path)){
    die("Không tìm thấy composer autoload.php. Kiểm tra đường dẫn: $autoload_path");
}
require_once $autoload_path;

use Twilio\Rest\Client;

$userObj = new User();

// --- Twilio config ---
$twilio_sid    = "ACa8f46a2839673948af6144605d60b5ed"; // Account SID
$twilio_token  = "425efb7c5ea8700112db9c200ad4eb4c";   // Auth Token
$twilio_number = "+84937781823";                      // Số Twilio đã active

$twilio_client = null;

// --- Khởi tạo Twilio client ---
try {
    if(!class_exists('\Twilio\Rest\Client')){
        die("Twilio SDK chưa được load. Chạy composer require twilio/sdk");
    }
    $twilio_client = new Client($twilio_sid, $twilio_token);
} catch(Exception $e){
    error_log("[TWILIO INIT ERROR] ".$e->getMessage());
    $twilio_client = null;
}

// --- Biến hiển thị ---
$register_errors = [];
$register_success = "";
$login_error = "";
$forgot_success = "";
$forgot_error = "";

// --- Chuẩn hóa SĐT VN ---
function normalizePhone($phone){
    $phone = preg_replace('/\D+/', '', $phone);
    if(substr($phone,0,1)=='0') return '+84'.substr($phone,1);
    elseif(substr($phone,0,2)=='84') return '+'.$phone;
    elseif(substr($phone,0,3)=='+84') return $phone;
    else return '+84'.$phone;
}

// --- Gửi OTP qua Twilio ---
function sendOTP($phone, $content){
    global $twilio_client, $twilio_number;

    if(!$twilio_client){
        error_log("[SMS ERROR] Twilio client chưa khởi tạo");
        return ["success"=>false, "error"=>"Twilio client chưa khởi tạo"];
    }

    try{
        $msg = $twilio_client->messages->create(
            $phone,
            [
                'from' => $twilio_number,
                'body' => $content
            ]
        );
        return ["success"=>true, "sid"=>$msg->sid];
    } catch(Exception $e){
        error_log("[SMS ERROR] ".$e->getMessage());
        return ["success"=>false, "error"=>$e->getMessage()];
    }
}

// --- Nếu đã login ---
if(isset($_SESSION['user_id'])){
    header("Location: index.php?page=home");
    exit;
}

/* ===================================================
   ĐĂNG KÝ – GỬI OTP
=================================================== */
if(isset($_POST['send_otp_register'])){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    if($password !== $repassword) $register_errors[] = "Mật khẩu nhập lại không khớp.";

    $normalized_phone = normalizePhone($phone);
    $stmt = $userObj->db->getConnection()->prepare("SELECT * FROM user WHERE Username=? OR Email=? OR Phone=?");
    $stmt->execute([$username,$email,$normalized_phone]);
    if($stmt->rowCount()>0) $register_errors[] = "Tài khoản, email hoặc số điện thoại đã tồn tại.";

    if(empty($register_errors)){
        $otp = rand(100000,999999);
        $_SESSION['pending_register'] = [
            'username'=>$username,
            'email'=>$email,
            'phone'=>$normalized_phone,
            'password'=>password_hash($password,PASSWORD_DEFAULT),
            'otp'=>$otp
        ];
        $result = sendOTP($normalized_phone,"OTP đăng ký 5SV Sport: $otp");

        if($result['success']){
            $register_success = "OTP đã gửi tới số $normalized_phone. Nhập OTP để hoàn tất đăng ký.<br>SMS SID: ".$result['sid'];
        } else {
            $register_errors[] = "Gửi OTP thất bại: ".$result['error'];
            unset($_SESSION['pending_register']);
        }
    }
}

/* ===================================================
   XÁC NHẬN OTP ĐĂNG KÝ
=================================================== */
if(isset($_POST['verify_otp_register']) && isset($_SESSION['pending_register'])){
    if($_POST['otp_register'] == $_SESSION['pending_register']['otp']){
        $data = $_SESSION['pending_register'];
        $stmt = $userObj->db->getConnection()->prepare("INSERT INTO user (Username,Email,Phone,Password,Role) VALUES (?,?,?,?,0)");
        $stmt->execute([$data['username'],$data['email'],$data['phone'],$data['password']]);
        unset($_SESSION['pending_register']);
        $register_success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
    } else $register_errors[] = "OTP không chính xác.";
}

/* ===================================================
   ĐĂNG NHẬP
=================================================== */
if(isset($_POST['login'])){
    $user_or_email = trim($_POST['user_or_email']);
    $normalized_input = normalizePhone($user_or_email);
    $stmt = $userObj->db->getConnection()->prepare("SELECT * FROM user WHERE Username=? OR Email=? OR Phone=?");
    $stmt->execute([$user_or_email,$user_or_email,$normalized_input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($_POST['password'],$user['Password'])){
        $_SESSION['user_id']=$user['id_User'];
        $_SESSION['username']=$user['Username'];
        $_SESSION['role']=$user['Role'];
        header("Location: index.php?page=home");
        exit;
    } else $login_error = "Sai tài khoản hoặc mật khẩu.";
}

/* ===================================================
   QUÊN MẬT KHẨU – GỬI OTP
=================================================== */
if(isset($_POST['forgot_send_otp'])){
    $phone = trim($_POST['forgot_phone']);
    $normalized_phone = normalizePhone($phone);
    $stmt = $userObj->db->getConnection()->prepare("SELECT * FROM user WHERE Phone=?");
    $stmt->execute([$normalized_phone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user) $forgot_error = "Không tìm thấy tài khoản với số này.";
    else {
        $otp = rand(100000,999999);
        $_SESSION['forgot_phone'] = $normalized_phone;
        $_SESSION['forgot_otp'] = $otp;
        $result = sendOTP($normalized_phone,"OTP reset mật khẩu 5SV Sport: $otp");

        if($result['success']){
            $forgot_success = "Mã OTP đã gửi đến số $normalized_phone. Nhập OTP để đổi mật khẩu.<br>SMS SID: ".$result['sid'];
        } else {
            $forgot_success = "Mã OTP đã được tạo (SMS chưa gửi). Lỗi: ".$result['error'];
            error_log("[SMS ERROR] ".$result['error']." cho $normalized_phone");
        }
    }
}

/* ===================================================
   XÁC NHẬN OTP QUÊN MẬT KHẨU
=================================================== */
if(isset($_POST['verify_forgot_otp']) && isset($_SESSION['forgot_otp'])){
    if($_POST['otp_forgot']==$_SESSION['forgot_otp']){
        $_SESSION['allow_reset'] = true;
        $forgot_success = "Xác minh thành công! Nhập mật khẩu mới.";
    } else $forgot_error = "OTP không chính xác.";
}

/* ===================================================
   ĐẶT LẠI MẬT KHẨU
=================================================== */
if(isset($_POST['save_new_password']) && isset($_SESSION['allow_reset'])){
    $newpass = $_POST['new_password'];
    $repass = $_POST['re_new_password'];
    if($newpass!==$repass) $forgot_error="Mật khẩu nhập lại không khớp.";
    else{
        $hash = password_hash($newpass,PASSWORD_DEFAULT);
        $phone = $_SESSION['forgot_phone'];
        $stmt = $userObj->db->getConnection()->prepare("UPDATE user SET Password=? WHERE Phone=?");
        $stmt->execute([$hash,$phone]);
        unset($_SESSION['allow_reset'],$_SESSION['forgot_otp'],$_SESSION['forgot_phone']);
        $forgot_success="Mật khẩu đã được đặt lại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đăng ký / Đăng nhập - 5SV Sport</title>
<link rel="stylesheet" href="App/public/shop/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:Arial,sans-serif;background:#f5f5f5;}
.auth-page{display:flex;justify-content:center;align-items:center;min-height:100vh;}
.auth-container{display:flex;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.1);}
.auth-image img{width:400px;display:block;}
.auth-form{padding:30px;width:360px;}
.auth-tabs{display:flex;margin-bottom:20px;}
.tab-btn{flex:1;text-align:center;padding:10px 0;cursor:pointer;border-bottom:2px solid #ccc;}
.tab-btn.active{color:#e31e24;border-bottom:4px solid #e31e24;}
.form-group{margin-bottom:15px;}
.form-group input{width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;}
.btn-submit{width:100%;padding:12px;background:#e31e24;color:#fff;border:none;border-radius:8px;cursor:pointer;}
.error-msg{background:#fdf2f2;color:#e74c3c;padding:10px;border-radius:6px;text-align:center;margin-bottom:10px;}
.success-msg{background:#e6ffed;color:#2a7a2a;padding:10px;border-radius:6px;text-align:center;margin-bottom:10px;}
.link-like{color:#e31e24;text-decoration:underline;cursor:pointer;}
.otp-input{width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;}
.modal-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.55);display:none;justify-content:center;align-items:center;z-index:9999;}
.modal-box{width:380px;background:#fff;border-radius:14px;padding:22px;animation:fadeIn .25s ease-in-out;}
@keyframes fadeIn{from{opacity:0;transform:translateY(-10px);}to{opacity:1;transform:translateY(0);}}
.modal-title{font-size:20px;font-weight:600;text-align:center;margin-bottom:18px;}
.btn-modal{width:100%;padding:12px;background:#e31e24;color:#fff;border:none;border-radius:8px;cursor:pointer;margin-top:10px;}
.modal-close{color:#666;text-align:center;margin-top:12px;cursor:pointer;}
</style>
</head>
<body>

<div class="auth-page">
<div class="auth-container">
<div class="auth-image"><img src="App/public/img/anhtrangdangky.png" alt="5SV Sport"></div>
<div class="auth-form">

<div class="auth-tabs">
<div class="tab-btn <?= !isset($_SESSION['pending_register']) ? 'active' : '' ?>" onclick="showTab('register')">Đăng ký</div>
<div class="tab-btn <?= isset($_SESSION['pending_register']) ? 'active' : '' ?>" onclick="showTab('login')">Đăng nhập</div>
</div>

<?php if(!empty($register_success)): ?><div class="success-msg"><?= $register_success ?></div><?php endif;?>
<?php if(!empty($register_errors)): ?><div class="error-msg"><?= implode('<br>',$register_errors) ?></div><?php endif;?>
<?php if(!empty($login_error)): ?><div class="error-msg"><?= $login_error ?></div><?php endif;?>
<?php if(!empty($forgot_error)): ?><div class="error-msg"><?= $forgot_error ?></div><?php endif;?>
<?php if(!empty($forgot_success)): ?><div class="success-msg"><?= $forgot_success ?></div><?php endif;?>

<!-- FORM ĐĂNG KÝ -->
<div id="register-form" style="display:<?= isset($_SESSION['pending_register'])?'none':'block' ?>">
<form method="POST">
<input type="text" name="username" placeholder="Tên đăng nhập" value="<?=htmlspecialchars($_POST['username']??'')?>" required>
<input type="email" name="email" placeholder="Email" value="<?=htmlspecialchars($_POST['email']??'')?>" required>
<input type="text" name="phone" placeholder="SĐT" value="<?=htmlspecialchars($_POST['phone']??'')?>" required>
<input type="password" name="password" placeholder="Mật khẩu" required>
<input type="password" name="repassword" placeholder="Nhập lại mật khẩu" required>
<button type="submit" name="send_otp_register" class="btn-submit">Gửi OTP & Đăng ký</button>
</form>
</div>

<!-- OTP ĐĂNG KÝ -->
<?php if(isset($_SESSION['pending_register'])): ?>
<div id="verify-register">
<form method="POST">
<input class="otp-input" type="text" name="otp_register" placeholder="Nhập OTP" required>
<button type="submit" name="verify_otp_register" class="btn-submit">Xác nhận OTP</button>
</form>
</div>
<?php endif;?>

<!-- FORM ĐĂNG NHẬP -->
<div id="login-form" style="display:<?= isset($_SESSION['pending_register'])?'block':'none' ?>">
<form method="POST">
<input type="text" name="user_or_email" placeholder="Tên đăng nhập / Email / SĐT" required>
<input type="password" name="password" placeholder="Mật khẩu" required>
<div style="margin:10px 0;"><span class="link-like" onclick="showForgotPopup()">Quên mật khẩu?</span></div>
<button type="submit" name="login" class="btn-submit">Đăng nhập</button>
</form>
</div>

</div></div></div>

<!-- POPUP QUÊN MẬT KHẨU -->
<div id="forgotModal" class="modal-overlay">
<div class="modal-box">
<div id="modalStep1">
<div class="modal-title">Quên mật khẩu</div>
<form method="POST">
<input type="text" name="forgot_phone" placeholder="SĐT" required>
<button type="submit" name="forgot_send_otp" class="btn-modal">Gửi OTP</button>
</form>
<div class="modal-close" onclick="closeForgotPopup()">Đóng</div>
</div>
<div id="modalStep2" style="display:none">
<div class="modal-title">Nhập OTP</div>
<form method="POST">
<input type="text" name="otp_forgot" placeholder="OTP" required>
<button type="submit" name="verify_forgot_otp" class="btn-modal">Xác nhận OTP</button>
</form>
<div class="modal-close" onclick="closeForgotPopup()">Đóng</div>
</div>
<div id="modalStep3" style="display:none">
<div class="modal-title">Mật khẩu mới</div>
<form method="POST">
<input type="password" name="new_password" placeholder="Mật khẩu mới" required>
<input type="password" name="re_new_password" placeholder="Nhập lại mật khẩu" required>
<button type="submit" name="save_new_password" class="btn-modal">Lưu mật khẩu</button>
</form>
<div class="modal-close" onclick="closeForgotPopup()">Đóng</div>
</div>
</div>
</div>

<script>
function showTab(tab){
document.querySelectorAll('.tab-btn').forEach(t=>t.classList.remove('active'));
if(tab==='register'){
document.querySelectorAll('.tab-btn')[0].classList.add('active');
document.getElementById('register-form').style.display='block';
document.getElementById('login-form').style.display='none';
}else{
document.querySelectorAll('.tab-btn')[1].classList.add('active');
document.getElementById('register-form').style.display='none';
document.getElementById('login-form').style.display='block';
}
}
function showForgotPopup(){
document.getElementById('forgotModal').style.display='flex';
document.getElementById('modalStep1').style.display='block';
document.getElementById('modalStep2').style.display='none';
document.getElementById('modalStep3').style.display='none';
}
function closeForgotPopup(){document.getElementById('forgotModal').style.display='none';}

// Khi reload trang, mở đúng bước OTP
<?php if(isset($_SESSION['forgot_phone'])): ?>
document.addEventListener('DOMContentLoaded',function(){
document.getElementById('forgotModal').style.display='flex';
document.getElementById('modalStep1').style.display='none';
document.getElementById('modalStep2').style.display='block';
});
<?php endif; ?>
<?php if(isset($_SESSION['allow_reset']) && $_SESSION['allow_reset']): ?>
document.addEventListener('DOMContentLoaded',function(){
document.getElementById('forgotModal').style.display='flex';
document.getElementById('modalStep1').style.display='none';
document.getElementById('modalStep2').style.display='none';
document.getElementById('modalStep3').style.display='block';
});
<?php endif; ?>
</script>
</body>
</html>

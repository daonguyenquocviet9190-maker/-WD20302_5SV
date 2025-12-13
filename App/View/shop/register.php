<?php
if(session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__.'/../../../App/Model/user.php';
require_once __DIR__.'/../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$userObj = new User();
$pdo = $userObj->db->getConnection();

// SMTP config
define('SMTP_HOST','smtp.gmail.com');
define('SMTP_PORT',587);
define('SMTP_USER','tronghoainguyen5@gmail.com');
define('SMTP_PASS','mhawycgdgupsfuhk'); // app password
define('SMTP_FROM','tronghoainguyen5@gmail.com');
define('SMTP_FROM_NAME','5SV Sport');

// Helpers
function sendOtpEmail($to,$otp){
    $mail=new PHPMailer(true);
    try{
        $mail->isSMTP();
        $mail->Host=SMTP_HOST;
        $mail->SMTPAuth=true;
        $mail->Username=SMTP_USER;
        $mail->Password=SMTP_PASS;
        $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port=SMTP_PORT;
        $mail->setFrom(SMTP_FROM,SMTP_FROM_NAME);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject='Mã OTP xác thực - 5SV Sport';
        $mail->Body='<h2>Mã OTP của bạn: '.$otp.'</h2><p>Có hiệu lực 5 phút.</p>';
        $mail->send();
        return true;
    }catch(Exception $e){
        error_log($e->getMessage());
        return false;
    }
}

function normalizePhone($phone){
    $phone=preg_replace('/\D+/','',$phone);
    if(substr($phone,0,1)=='0') return '+84'.substr($phone,1);
    elseif(substr($phone,0,2)=='84') return '+'.$phone;
    elseif(substr($phone,0,3)=='+84') return $phone;
    else return '+84'.$phone;
}

// ---------------- HANDLE ----------------
$errors=[];
$success='';
$show_register_modal=false;
$show_forgot_modal=false;
$forgot_step=1;

// REGISTER STEP 1
if(isset($_POST['register'])){
    $username=trim($_POST['username']);
    $email=trim($_POST['email']);
    $phone_input=trim($_POST['phone']);
    $password=$_POST['password'];
    $repassword=$_POST['repassword'];

    if($password!==$repassword) $errors[]="Mật khẩu không khớp";
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]="Email không hợp lệ";

    $normalized_phone=normalizePhone($phone_input);
    $stmt=$pdo->prepare("SELECT * FROM user WHERE Username=? OR Email=? OR Phone=?");
    $stmt->execute([$username,$email,$normalized_phone]);
    if($stmt->rowCount()>0) $errors[]="Tài khoản/Email/SĐT đã tồn tại";

    if(!$errors){
        $otp=rand(100000,999999);
        $_SESSION['pending_register']=[
            'username'=>$username,
            'email'=>$email,
            'phone'=>$normalized_phone,
            'password'=>password_hash($password,PASSWORD_DEFAULT),
            'otp'=>$otp,
            'otp_time'=>time()
        ];
        $stmt=$pdo->prepare("INSERT INTO otp_codes(email,otp,created_at,expires_at) VALUES (?,?,NOW(),DATE_ADD(NOW(),INTERVAL 5 MINUTE))");
        $stmt->execute([$email,$otp]);

        if(sendOtpEmail($email,$otp)){
            $success="OTP đã gửi tới $email";
            $show_register_modal=true;
        }else $errors[]="Gửi OTP thất bại";
    }
}

// REGISTER STEP 2 OTP
if(isset($_POST['verify_register_otp'])){
    $input_otp=trim($_POST['otp']);
    if(isset($_SESSION['pending_register'])){
        $pending=$_SESSION['pending_register'];
        $q=$pdo->prepare("SELECT * FROM otp_codes WHERE email=? ORDER BY id DESC LIMIT 1");
        $q->execute([$pending['email']]);
        $row=$q->fetch(PDO::FETCH_ASSOC);
        if(!$row) $errors[]="OTP không tìm thấy";
        elseif($row['otp']!=$input_otp) $errors[]="OTP không chính xác";
        elseif(strtotime($row['expires_at'])<time()) $errors[]="OTP đã hết hạn";
        else{
            $stmt=$pdo->prepare("INSERT INTO user (Username,Email,Phone,Password,Role) VALUES (?,?,?,?,0)");
            $stmt->execute([$pending['username'],$pending['email'],$pending['phone'],$pending['password']]);
            $stmt=$pdo->prepare("DELETE FROM otp_codes WHERE id=?");
            $stmt->execute([$row['id']]);
            unset($_SESSION['pending_register']);
            $success="Đăng ký thành công! Bạn có thể đăng nhập ngay.";
        }
    }else $errors[]="Phiên đăng ký không tồn tại";
}

// LOGIN
if(isset($_POST['login'])){
    $user_or_email=trim($_POST['user_or_email']);
    $password=$_POST['password'];
    $stmt=$pdo->prepare("SELECT * FROM user WHERE Username=? OR Email=? OR Phone=?");
    $stmt->execute([$user_or_email,$user_or_email,$user_or_email]);
    $user=$stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($password,$user['Password'])){
        $_SESSION['user_id']=$user['id_User'];
        header("Location: index.php?page=home"); exit;
    }else $errors[]="Tài khoản hoặc mật khẩu không đúng";
}

// FORGOT PASSWORD STEP 1
if(isset($_POST['forgot_send'])){
    $email=trim($_POST['forgot_email']);
    $stmt=$pdo->prepare("SELECT * FROM user WHERE Email=?");
    $stmt->execute([$email]);
    $user=$stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user) $errors[]="Email không tồn tại";
    else{
        $otp=rand(100000,999999);
        $_SESSION['pending_forgot']=['email'=>$email,'otp'=>$otp,'time'=>time()];
        $stmt=$pdo->prepare("INSERT INTO otp_codes(email,otp,created_at,expires_at) VALUES (?,?,NOW(),DATE_ADD(NOW(),INTERVAL 5 MINUTE))");
        $stmt->execute([$email,$otp]);
        if(sendOtpEmail($email,$otp)){
            $success="OTP đã gửi tới email $email";
            $show_forgot_modal=true;
            $forgot_step=2;
        }else $errors[]="Gửi OTP thất bại";
    }
}

// FORGOT PASSWORD STEP 2 OTP
if(isset($_POST['verify_forgot_otp'])){
    $input_otp=trim($_POST['otp_forgot']);
    if(isset($_SESSION['pending_forgot'])){
        $pending=$_SESSION['pending_forgot'];
        $q=$pdo->prepare("SELECT * FROM otp_codes WHERE email=? ORDER BY id DESC LIMIT 1");
        $q->execute([$pending['email']]);
        $row=$q->fetch(PDO::FETCH_ASSOC);
        if(!$row) $errors[]="OTP không tìm thấy";
        elseif($row['otp']!=$input_otp) $errors[]="OTP không chính xác";
        elseif(strtotime($row['expires_at'])<time()) $errors[]="OTP đã hết hạn";
        else{$forgot_step=3; $show_forgot_modal=true;}
    }else $errors[]="Phiên OTP không tồn tại";
}

// FORGOT PASSWORD STEP 3: save new password
if(isset($_POST['save_new_password'])){
    if(isset($_SESSION['pending_forgot'])){
        $pass=$_POST['new_password'];
        $repass=$_POST['re_new_password'];
        if($pass!==$repass) $errors[]="Mật khẩu không khớp";
        else{
            $hash=password_hash($pass,PASSWORD_DEFAULT);
            $stmt=$pdo->prepare("UPDATE user SET Password=? WHERE Email=?");
            $stmt->execute([$hash,$_SESSION['pending_forgot']['email']]);
            unset($_SESSION['pending_forgot']);
            $success="Đổi mật khẩu thành công! Bạn có thể đăng nhập ngay.";
        }
    }else $errors[]="Phiên OTP không tồn tại";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Đăng ký / Đăng nhập - 5SV Sport</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* BODY & CONTAINER */
body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;}
.auth-page{display:flex;justify-content:center;align-items:center;min-height:100vh;}
.auth-container{display:flex;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 15px 40px rgba(0,0,0,0.15);}
.auth-image img{width:400px;display:block;}

/* FORM */
.auth-form{padding:30px;width:360px;}
.auth-tabs{display:flex;margin-bottom:20px;}
.tab-btn{flex:1;text-align:center;padding:12px 0;cursor:pointer;border-bottom:2px solid #ccc;transition:all 0.3s;}
.tab-btn.active{color:#e31e24;border-bottom:4px solid #e31e24;font-weight:600;}
input{width:100%;padding:12px 14px;margin-bottom:12px;border:1px solid #ccc;border-radius:8px;font-size:14px;}
.btn-submit{width:100%;padding:12px;background:#e31e24;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:15px;}
.btn-submit:hover{background:#c41b20;}

/* MESSAGES */
.error-msg{background:#fdf2f2;color:#e74c3c;padding:10px;text-align:center;margin-bottom:10px;border-radius:6px;}
.success-msg{background:#e6ffed;color:#2a7a2a;padding:10px;text-align:center;margin-bottom:10px;border-radius:6px;}

/* MODAL */
.modal-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.55);display:flex;justify-content:center;align-items:center;opacity:0;visibility:hidden;transition:all 0.3s;}
.modal-overlay.show{opacity:1;visibility:visible;}
.modal-box{background:#fff;border-radius:14px;padding:25px;width:400px;transform:scale(0.9);transition:all 0.3s;}
.modal-overlay.show .modal-box{transform:scale(1);}
.modal-title{text-align:center;font-size:20px;font-weight:600;margin-bottom:18px;}
.btn-modal{width:100%;padding:12px;background:#e31e24;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:15px; margin-bottom: 8px;}
.btn-modal:hover{background:#c41b20;}
.modal-close{text-align:center;color:#666;margin-top:12px;cursor:pointer;}
.otp-timer{font-size:14px;color:#555;text-align:center;margin-top:8px;}

/* PASSWORD TOGGLE */
.show-password{position:relative;margin-bottom:12px;}
.show-password input[type="password"],.show-password input[type="text"]{padding-right:40px;}
.show-password .eye-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#e31e24;font-size:16px;}

/* FORGOT PASSWORD BTN */
.forgot-btn{background:none;border:none;color:#e31e24;cursor:pointer;margin-top:5px;text-decoration:underline;font-size:14px;}
.forgot-btn:hover{color:#c41b20;}
</style>
</head>
<body>

<div class="auth-page">
  <div class="auth-container">
    <div class="auth-image"><img src="App/public/img/anhtrangdangky.png" alt="5SV Sport"></div>
    <div class="auth-form">
      <div class="auth-tabs">
        <div class="tab-btn <?=(!isset($_POST['login']) && !isset($_POST['verify_register_otp']))?'active':''?>" onclick="showTab('register')">Đăng ký</div>
        <div class="tab-btn <?=isset($_POST['login'])?'active':''?>" onclick="showTab('login')">Đăng nhập</div>
      </div>

      <?php if($errors) foreach($errors as $e) echo '<div class="error-msg">'.$e.'</div>'; ?>
      <?php if($success) echo '<div class="success-msg">'.$success.'</div>'; ?>

      <!-- REGISTER FORM -->
      <div id="register-form" style="<?=(!isset($_POST['login']))?'display:block':'display:none'?>">
        <form method="post">
          <input type="text" name="username" placeholder="Tên đăng nhập" required>
          <input type="email" name="email" placeholder="Email" required>
          <input type="text" name="phone" placeholder="SĐT" required>
          <div class="show-password">
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <i class="fa-regular fa-eye eye-icon" onclick="togglePassword(this)"></i>
          </div>
          <div class="show-password">
            <input type="password" name="repassword" placeholder="Nhập lại mật khẩu" required>
            <i class="fa-regular fa-eye eye-icon" onclick="togglePassword(this)"></i>
          </div>
          <button type="submit" name="register" class="btn-submit">Gửi OTP & Đăng ký</button>
        </form>
      </div>

      <!-- LOGIN FORM -->
      <div id="login-form" style="<?=isset($_POST['login'])?'display:block':'display:none'?>">
        <form method="post">
          <input type="text" name="user_or_email" placeholder="Tên đăng nhập / Email / SĐT" required>
          <div class="show-password">
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <i class="fa-regular fa-eye eye-icon" onclick="togglePassword(this)"></i>
          </div>
          <button type="submit" name="login" class="btn-submit">Đăng nhập</button>
          <button type="button" class="forgot-btn" onclick="showModal('forgotModal')">Quên mật khẩu?</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- REGISTER OTP MODAL -->
<div id="registerModal" class="modal-overlay <?=($show_register_modal)?'show':''?>">
  <div class="modal-box">
    <div class="modal-title">Xác thực Email</div>
    <form method="post">
      <input type="text" name="otp" placeholder="Nhập OTP" required>
      <div class="otp-timer" id="registerOtpTimer"></div>
      <button type="submit" name="verify_register_otp" class="btn-modal">Xác nhận OTP</button>
    </form>
    <div class="modal-close" onclick="closeModal('registerModal')">Đóng</div>
  </div>
</div>

<!-- FORGOT PASSWORD MODAL -->
<div id="forgotModal" class="modal-overlay <?=($show_forgot_modal)?'show':''?>">
  <div class="modal-box">
    <?php if(!isset($forgot_step) || $forgot_step==1): ?>
    <div class="modal-title">Quên mật khẩu</div>
    <form method="post">
      <input type="email" name="forgot_email" placeholder="Nhập email" required>
      <button type="submit" name="forgot_send" class="btn-modal">Gửi OTP</button>
    </form>
    <?php elseif($forgot_step==2): ?>
    <div class="modal-title">Nhập OTP</div>
    <form method="post">
      <input type="text" name="otp_forgot" placeholder="Nhập OTP" required>
      <div class="otp-timer" id="forgotOtpTimer"></div>
      <button type="submit" name="verify_forgot_otp" class="btn-modal">Xác nhận OTP</button>
      <button type="button" class="btn-modal" onclick="resendForgotOtp()">Gửi lại OTP</button>
    </form>
    <?php elseif($forgot_step==3): ?>
    <div class="modal-title">Mật khẩu mới</div>
    <form method="post">
      <div class="show-password">
        <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
        <i class="fa-regular fa-eye eye-icon" onclick="togglePassword(this)"></i>
      </div>
      <div class="show-password">
        <input type="password" name="re_new_password" placeholder="Nhập lại mật khẩu" required>
        <i class="fa-regular fa-eye eye-icon" onclick="togglePassword(this)"></i>
      </div>
      <button type="submit" name="save_new_password" class="btn-modal">Lưu mật khẩu</button>
    </form>
    <?php endif; ?>
    <div class="modal-close" onclick="closeModal('forgotModal')">Đóng</div>
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
function showModal(id){document.getElementById(id).classList.add('show');}
function closeModal(id){document.getElementById(id).classList.remove('show');}
function togglePassword(el){
    const input=el.previousElementSibling;
    input.type=(input.type==='password')?'text':'password';
}

// OTP TIMER
function startOtpTimer(seconds,id){
    let timer=document.getElementById(id);
    if(!timer) return;
    let remaining=seconds;
    timer.innerText='OTP hết hạn sau '+remaining+' giây';
    let interval=setInterval(()=>{
        remaining--;
        if(remaining<=0){timer.innerText='OTP đã hết hạn';clearInterval(interval);}
        else timer.innerText='OTP hết hạn sau '+remaining+' giây';
    },1000);
}

// START TIMERS
<?php if($show_register_modal) echo "startOtpTimer(300,'registerOtpTimer');"; ?>
<?php if($show_forgot_modal && $forgot_step==2) echo "startOtpTimer(300,'forgotOtpTimer');"; ?>

// RESEND FORGOT OTP
function resendForgotOtp(){
    alert('Chức năng gửi lại OTP chưa implement, backend cần xử lý tương tự gửi OTP ban đầu.');
}

// AUTO HIDE MESSAGES
setTimeout(()=>{document.querySelectorAll('.success-msg,.error-msg').forEach(e=>e.style.opacity='0');},4000);
</script>
</body>
</html>

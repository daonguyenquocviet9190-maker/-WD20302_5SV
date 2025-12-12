<?php
if(session_status()===PHP_SESSION_NONE) session_start();
include_once __DIR__.'/../../../App/Model/user.php';
require_once __DIR__.'/../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$userObj = new User();
$pdo = $userObj->db->getConnection();

// --- SMTP Config ---
define('SMTP_HOST','smtp.gmail.com');
define('SMTP_PORT',587);
define('SMTP_USER','tronghoainguyen5@gmail.com'); // Gmail
define('SMTP_PASS','mhaw ycgd gups fuhk'); // app password
define('SMTP_FROM','tronghoainguyen5@gmail.com');
define('SMTP_FROM_NAME','5SV Sport');

// --- Helpers ---
function saveOtpToDb($pdo,$email,$otp,$ttl=300){
    $created = date('Y-m-d H:i:s');
    $expires = date('Y-m-d H:i:s',time()+$ttl);
    $stmt = $pdo->prepare("INSERT INTO otp_codes (email,otp,created_at,expires_at) VALUES (?,?,?,?)");
    $stmt->execute([$email,$otp,$created,$expires]);
    return $pdo->lastInsertId();
}

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
        $mail->setFrom(SMTP_FROM,SMTP_FROM_NAME);
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP xác thực - 5SV Sport';
        $mail->Body = '<div style="font-family:Arial,sans-serif;"><h2>Mã OTP của bạn: '.$otp.'</h2><p>Có hiệu lực 5 phút.</p></div>';
        $mail->send();
        return ['success'=>true];
    }catch(Exception $e){
        return ['success'=>false,'error'=>$mail->ErrorInfo??$e->getMessage()];
    }
}

function normalizePhonePlus($phone){
    $phone = preg_replace('/\D+/','',$phone);
    if(substr($phone,0,1)=='0') return '+84'.substr($phone,1);
    elseif(substr($phone,0,2)=='84') return '+'.$phone;
    elseif(substr($phone,0,3)=='+84') return $phone;
    else return '+84'.$phone;
}

// --- AJAX Handler ---
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])){
    header('Content-Type: application/json; charset=utf-8');
    $action = $_POST['action'];

    // REGISTER SEND OTP
    if($action==='send_otp_register'){
        $username = trim($_POST['username']??'');
        $email = trim($_POST['email']??'');
        $phone_input = trim($_POST['phone']??'');
        $password = $_POST['password']??'';
        $repassword = $_POST['repassword']??'';
        $errors=[];

        if($password!==$repassword) $errors[]='Mật khẩu không khớp';
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Email không hợp lệ';
        $normalized_phone = normalizePhonePlus($phone_input);

        $stmt=$pdo->prepare("SELECT * FROM user WHERE Username=? OR Email=? OR Phone=?");
        $stmt->execute([$username,$email,$normalized_phone]);
        if($stmt->rowCount()>0) $errors[]='Tài khoản/Email/SĐT đã tồn tại';

        if($errors) echo json_encode(['success'=>false,'errors'=>$errors]);
        else{
            $otp = rand(100000,999999);
            $_SESSION['pending_register'] = [
                'username'=>$username,'email'=>$email,'phone'=>$normalized_phone,
                'password'=>password_hash($password,PASSWORD_DEFAULT),
                'otp'=>$otp,'otp_time'=>time()
            ];
            saveOtpToDb($pdo,$email,$otp);
            $mailRes = sendOtpEmail($email,$otp);
            if($mailRes['success']) echo json_encode(['success'=>true,'message'=>"OTP đã gửi tới $email"]);
            else echo json_encode(['success'=>false,'errors'=>["Gửi OTP thất bại: ".($mailRes['error']??'unknown')]]); 
        }
        exit;
    }

    // REGISTER VERIFY OTP
    if($action==='verify_otp_register'){
        if(!isset($_SESSION['pending_register'])) exit(json_encode(['success'=>false,'errors'=>['Phiên đăng ký không tồn tại']]));
        $input_otp = trim($_POST['otp']??'');
        $pending = $_SESSION['pending_register'];
        $q=$pdo->prepare("SELECT * FROM otp_codes WHERE email=? ORDER BY id DESC LIMIT 1");
        $q->execute([$pending['email']]);
        $row=$q->fetch(PDO::FETCH_ASSOC);
        if(!$row) exit(json_encode(['success'=>false,'errors'=>['OTP không tìm thấy']]));
        if($row['otp']!=$input_otp) exit(json_encode(['success'=>false,'errors'=>['OTP không chính xác']]));
        if(strtotime($row['expires_at'])<time()) exit(json_encode(['success'=>false,'errors'=>['OTP đã hết hạn']]));

        $stmt=$pdo->prepare("INSERT INTO user (Username,Email,Phone,Password,Role) VALUES (?,?,?,?,0)");
        $stmt->execute([$pending['username'],$pending['email'],$pending['phone'],$pending['password']]);
        $del=$pdo->prepare("DELETE FROM otp_codes WHERE id=?");
        $del->execute([$row['id']]);
        unset($_SESSION['pending_register']);
        echo json_encode(['success'=>true,'message'=>'Đăng ký thành công']);
        exit;
    }

    // LOGIN
    if($action==='login'){
        $user_or_email = trim($_POST['user_or_email']??'');
        $password = $_POST['password']??'';
        $stmt=$pdo->prepare("SELECT * FROM user WHERE Username=? OR Email=? OR Phone=?");
        $stmt->execute([$user_or_email,$user_or_email,$user_or_email]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($password,$user['Password'])){
            $_SESSION['user_id']=$user['id_User'];
            echo json_encode(['success'=>true,'message'=>'Đăng nhập thành công','redirect'=>'index.php?page=home']);
        }else{
            echo json_encode(['success'=>false,'errors'=>['Tài khoản hoặc mật khẩu không đúng']]);
        }
        exit;
    }

    // FORGOT PASSWORD SEND OTP
    if($action==='forgot_send_otp'){
        $email = trim($_POST['forgot_email']??'');
        $stmt=$pdo->prepare("SELECT * FROM user WHERE Email=?");
        $stmt->execute([$email]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$user) exit(json_encode(['success'=>false,'errors'=>['Email không tồn tại']]));
        $otp = rand(100000,999999);
        $_SESSION['pending_forgot']=['email'=>$email,'otp'=>$otp,'time'=>time()];
        saveOtpToDb($pdo,$email,$otp);
        $mailRes = sendOtpEmail($email,$otp);
        if($mailRes['success']) echo json_encode(['success'=>true,'message'=>'OTP đã gửi']);
        else echo json_encode(['success'=>false,'errors'=>['Gửi OTP thất bại']]);
        exit;
    }

    // FORGOT PASSWORD VERIFY OTP
    if($action==='verify_forgot_otp'){
        if(!isset($_SESSION['pending_forgot'])) exit(json_encode(['success'=>false,'errors'=>['OTP không tồn tại']]));
        $input_otp = trim($_POST['otp_forgot']??'');
        $pending = $_SESSION['pending_forgot'];
        if($pending['otp']!=$input_otp) exit(json_encode(['success'=>false,'errors'=>['OTP không chính xác']]));
        echo json_encode(['success'=>true,'message'=>'OTP chính xác']);
        exit;
    }

    // SAVE NEW PASSWORD
    if($action==='save_new_password'){
        if(!isset($_SESSION['pending_forgot'])) exit(json_encode(['success'=>false,'errors'=>['Phiên quên mật khẩu không tồn tại']]));
        $pass = $_POST['new_password']??'';
        $repass = $_POST['re_new_password']??'';
        if($pass!==$repass) exit(json_encode(['success'=>false,'errors'=>['Mật khẩu không khớp']]));
        $hash = password_hash($pass,PASSWORD_DEFAULT);
        $stmt=$pdo->prepare("UPDATE user SET Password=? WHERE Email=?");
        $stmt->execute([$hash,$_SESSION['pending_forgot']['email']]);
        unset($_SESSION['pending_forgot']);
        echo json_encode(['success'=>true,'message'=>'Đổi mật khẩu thành công']);
        exit;
    }

    exit(json_encode(['success'=>false,'errors'=>['Action không hợp lệ']]));
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Đăng ký / Đăng nhập - 5SV Sport</title>
<link rel="stylesheet" href="App/public/shop/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;}
.auth-page{display:flex;justify-content:center;align-items:center;min-height:100vh;}
.auth-container{display:flex;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.1);}
.auth-image img{width:400px;}
.auth-form{padding:30px;width:360px;}
.auth-tabs{display:flex;margin-bottom:20px;}
.tab-btn{flex:1;text-align:center;padding:10px 0;cursor:pointer;border-bottom:2px solid #ccc;}
.tab-btn.active{color:#e31e24;border-bottom:4px solid #e31e24;}
.btn-submit{width:100%;padding:12px;background:#e31e24;color:#fff;border:none;border-radius:8px;cursor:pointer;}
.error-msg{background:#fdf2f2;color:#e74c3c;padding:10px;border-radius:6px;text-align:center;margin-bottom:10px;}
.success-msg{background:#e6ffed;color:#2a7a2a;padding:10px;border-radius:6px;text-align:center;margin-bottom:10px;}
.link-like{color:#e31e24;text-decoration:underline;cursor:pointer;}
.otp-input{width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;}
.modal-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.55);display:none;justify-content:center;align-items:center;z-index:9999;}
.modal-box{width:420px;background:#fff;border-radius:14px;padding:22px;animation:fadeIn .25s ease-in-out;}
@keyframes fadeIn{from{opacity:0;transform:translateY(-10px);}to{opacity:1;transform:translateY(0);}}
.modal-title{font-size:20px;font-weight:600;text-align:center;margin-bottom:18px;}
.btn-modal{width:100%;padding:12px;background:#e31e24;color:#fff;border:none;border-radius:8px;cursor:pointer;margin-top:10px;}
.modal-close{color:#666;text-align:center;margin-top:12px;cursor:pointer;}
.small-muted{font-size:13px;color:#666;margin-top:8px;text-align:center;}
</style>
</head>
<body>

<div class="auth-page">
  <div class="auth-container">
    <div class="auth-image"><img src="App/public/img/anhtrangdangky.png" alt="5SV Sport"></div>
    <div class="auth-form">
      <div class="auth-tabs">
        <div class="tab-btn active" onclick="showTab('register')">Đăng ký</div>
        <div class="tab-btn" onclick="showTab('login')">Đăng nhập</div>
      </div>

      <!-- REGISTER FORM -->
      <div id="register-form">
        <form id="form-register" onsubmit="return sendOtpRegister(event)">
          <input type="text" name="username" placeholder="Tên đăng nhập" required>
          <input type="email" name="email" placeholder="Email" required>
          <input type="text" name="phone" placeholder="SĐT" required>
          <input type="password" name="password" placeholder="Mật khẩu" required>
          <input type="password" name="repassword" placeholder="Nhập lại mật khẩu" required>
          <button type="submit" class="btn-submit">Gửi OTP & Đăng ký</button>
        </form>
        <p class="small-muted">Bạn sẽ nhận mã OTP vào email để hoàn tất đăng ký.</p>
      </div>

      <!-- LOGIN FORM -->
      <div id="login-form" style="display:none;">
        <form onsubmit="return loginAjax(event)">
          <input type="text" name="user_or_email" placeholder="Tên đăng nhập / Email / SĐT" required>
          <input type="password" name="password" placeholder="Mật khẩu" required>
          <div style="margin:10px 0;"><span class="link-like" onclick="showForgotPopup()">Quên mật khẩu?</span></div>
          <button type="submit" class="btn-submit">Đăng nhập</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- REGISTER OTP POPUP -->
<div id="registerModal" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-title">Xác thực Email</div>
    <p>Chúng tôi đã gửi mã OTP tới <strong id="reg-email-show"></strong></p>
    <form id="form-verify-register" onsubmit="return verifyOtpRegister(event)">
      <input type="text" class="otp-input" id="reg-otp" placeholder="Nhập OTP" required>
      <button type="submit" class="btn-modal">Xác nhận OTP</button>
    </form>
    <div style="margin-top:12px; display:flex; gap:8px;">
      <button class="btn-modal" onclick="resendOtpRegister()">Gửi lại mã</button>
      <div style="flex:1; text-align:center; align-self:center;"><span id="reg-timer">05:00</span></div>
    </div>
    <div class="modal-close" onclick="closeRegisterPopup()">Đóng</div>
  </div>
</div>

<!-- FORGOT PASSWORD POPUP -->
<div id="forgotModal" class="modal-overlay">
  <div class="modal-box">
    <div id="modalStep1">
      <div class="modal-title">Quên mật khẩu</div>
      <form onsubmit="return sendForgotOtp(event)">
        <input type="email" id="forgot-email" placeholder="Email" required>
        <button type="submit" class="btn-modal">Gửi OTP</button>
      </form>
      <div class="modal-close" onclick="closeForgotPopup()">Đóng</div>
    </div>
    <div id="modalStep2" style="display:none">
      <div class="modal-title">Nhập OTP</div>
      <form onsubmit="return verifyForgotOtp(event)">
        <input type="text" id="forgot-otp" placeholder="OTP" required>
        <button type="submit" class="btn-modal">Xác nhận OTP</button>
      </form>
      <div class="modal-close" onclick="closeForgotPopup()">Đóng</div>
    </div>
    <div id="modalStep3" style="display:none">
      <div class="modal-title">Mật khẩu mới</div>
      <form onsubmit="return saveNewPassword(event)">
        <input type="password" id="new-password" placeholder="Mật khẩu mới" required>
        <input type="password" id="re-new-password" placeholder="Nhập lại mật khẩu" required>
        <button type="submit" class="btn-modal">Lưu mật khẩu</button>
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

function openRegisterPopup(email){
  document.getElementById('reg-email-show').innerText=email;
  document.getElementById('registerModal').style.display='flex';
  startRegTimer(300);
}
function closeRegisterPopup(){ document.getElementById('registerModal').style.display='none'; }

function showForgotPopup(){
  document.getElementById('forgotModal').style.display='flex';
  document.getElementById('modalStep1').style.display='block';
  document.getElementById('modalStep2').style.display='none';
  document.getElementById('modalStep3').style.display='none';
}
function closeForgotPopup(){ document.getElementById('forgotModal').style.display='none'; }

async function postForm(data){
  try{
    const resp = await fetch(window.location.href,{method:'POST',body:data});
    return await resp.json();
  }catch(e){ console.error(e); return {success:false,errors:['Lỗi mạng']}; }
}

// REGISTER AJAX
async function sendOtpRegister(e){
  e.preventDefault();
  const fd = new FormData(e.target); fd.append('action','send_otp_register');
  const res = await postForm(fd);
  if(res.success) openRegisterPopup(fd.get('email'));
  else alert((res.errors||[]).join('\n'));
  return false;
}
async function verifyOtpRegister(e){
  e.preventDefault();
  const otp = document.getElementById('reg-otp').value;
  const fd = new FormData(); fd.append('action','verify_otp_register'); fd.append('otp',otp);
  const res = await postForm(fd);
  if(res.success){ alert(res.message); closeRegisterPopup(); showTab('login'); }
  else alert((res.errors||[]).join('\n'));
  return false;
}

// LOGIN AJAX
async function loginAjax(e){
  e.preventDefault();
  const fd = new FormData(e.target); fd.append('action','login');
  const res = await postForm(fd);
  if(res.success){ alert(res.message); window.location.href=res.redirect; }
  else alert((res.errors||[]).join('\n'));
  return false;
}

// FORGOT PASSWORD AJAX
async function sendForgotOtp(e){
  e.preventDefault();
  const fd = new FormData(); fd.append('action','forgot_send_otp'); fd.append('forgot_email',document.getElementById('forgot-email').value);
  const res = await postForm(fd);
  if(res.success){ document.getElementById('modalStep1').style.display='none'; document.getElementById('modalStep2').style.display='block'; }
  else alert((res.errors||[]).join('\n'));
  return false;
}
async function verifyForgotOtp(e){
  e.preventDefault();
  const fd = new FormData(); fd.append('action','verify_forgot_otp'); fd.append('otp_forgot',document.getElementById('forgot-otp').value);
  const res = await postForm(fd);
  if(res.success){ document.getElementById('modalStep2').style.display='none'; document.getElementById('modalStep3').style.display='block'; }
  else alert((res.errors||[]).join('\n'));
  return false;
}
async function saveNewPassword(e){
  e.preventDefault();
  const fd = new FormData(); fd.append('action','save_new_password'); 
  fd.append('new_password',document.getElementById('new-password').value);
  fd.append('re_new_password',document.getElementById('re-new-password').value);
  const res = await postForm(fd);
  if(res.success){ alert(res.message); closeForgotPopup(); }
  else alert((res.errors||[]).join('\n'));
  return false;
}

// TIMER
let regTimerInterval;
function startRegTimer(sec){
  clearInterval(regTimerInterval);
  let timer = sec;
  regTimerInterval = setInterval(()=>{
    const m = Math.floor(timer/60).toString().padStart(2,'0');
    const s = (timer%60).toString().padStart(2,'0');
    document.getElementById('reg-timer').innerText = `${m}:${s}`;
    if(timer--<=0) clearInterval(regTimerInterval);
  },1000);
}
function resendOtpRegister(){ alert('Chức năng gửi lại OTP sẽ được triển khai'); }
</script>

</body>
</html>

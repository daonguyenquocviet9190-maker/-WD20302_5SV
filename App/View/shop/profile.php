<?php
// profile.php (full) - PUT into the same location as your old profile file
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/../../../App/Model/database.php";
require_once __DIR__ . "/../../../vendor/autoload.php"; // PHPMailer + composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// DB connect
try {
    $db = new Database("localhost", "5svcode", "root", "");
    $pdo = $db->connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối DB: " . $e->getMessage());
}

// Auth check
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem thông tin'); window.location='login.php';</script>";
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* =========================
   Config mail (Gmail App Password)
   ========================= */
define('SMTP_HOST','smtp.gmail.com');
define('SMTP_PORT',587);
define('SMTP_USER','tronghoainguyen5@gmail.com');
define('SMTP_PASS','mhaw ycgd gups fuhk'); // your app password
define('SMTP_FROM','tronghoainguyen5@gmail.com');
define('SMTP_FROM_NAME','5SV Sport');

/* =========================
   Helpers
   ========================= */
function sendOtpEmail($toEmail, $otp, $pdo) {
    $created = date('Y-m-d H:i:s');
    $expires = date('Y-m-d H:i:s', time() + 300); // 5 phút

    try {
        $q = $pdo->prepare("INSERT INTO otp_codes (email, otp, created_at, expires_at) VALUES (?, ?, ?, ?)");
        $q->execute([$toEmail, $otp, $created, $expires]);
    } catch (Exception $e) {
        return ['success'=>false, 'msg'=>'Lỗi lưu OTP: '.$e->getMessage()];
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP xác thực - Thao tác thay đổi thông tin';
        $mail->Body = "
            <div style='font-family:Arial,Helvetica,sans-serif;max-width:600px;margin:auto;padding:20px;border-radius:10px;background:#f7fbff'>
              <h3 style='color:#111'>Mã xác thực OTP của bạn</h3>
              <p>Chúng tôi đã nhận yêu cầu thay đổi thông tin tài khoản. Mã xác thực để tiếp tục là:</p>
              <div style='font-size:28px;font-weight:700;color:#005eff;background:#fff;padding:12px 20px;border-radius:8px;display:inline-block;border:1px solid #e6eef6'>".$otp."</div>
              <p style='color:#666;margin-top:12px'>Mã có hiệu lực trong 5 phút. Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>
              <hr style='border:none;border-top:1px solid #eee;margin-top:18px'>
              <p style='color:#999;font-size:12px'>© ".date('Y')." 5SV Sport</p>
            </div>
        ";

        $mail->send();
        return ['success'=>true, 'msg'=>'Đã gửi OTP tới email: '.$toEmail];
    } catch (Exception $e) {
        return ['success'=>false, 'msg'=>'Lỗi gửi email: '.$mail->ErrorInfo];
    }
}

function normalizePhonePlus($phone){
    $phone = preg_replace('/\D+/', '', $phone);
    if(substr($phone,0,1)=='0') return '+84'.substr($phone,1);
    elseif(substr($phone,0,2)=='84') return '+'.$phone;
    elseif(substr($phone,0,3)=='+84') return $phone;
    else return '+84'.$phone;
}

/* =========================
   AJAX endpoints
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_POST['action'];

    $stmt = $pdo->prepare("SELECT Email FROM user WHERE id_User = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentEmail = $row['Email'] ?? '';

    if ($action === 'send_otp_profile') {
        if (empty($currentEmail) || !filter_var($currentEmail, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success'=>false, 'msg'=>'Không tìm thấy email hợp lệ của user.']);
            exit;
        }

        $q = $pdo->prepare("SELECT created_at FROM otp_codes WHERE email = ? ORDER BY id DESC LIMIT 1");
        $q->execute([$currentEmail]);
        $last = $q->fetch(PDO::FETCH_ASSOC);
        if ($last && time() - strtotime($last['created_at']) < 20) {
            echo json_encode(['success'=>false, 'msg'=>'Vừa gửi OTP, vui lòng chờ vài giây.']);
            exit;
        }

        $otp = rand(100000, 999999);
        $res = sendOtpEmail($currentEmail, $otp, $pdo);
        if ($res['success']) {
            $_SESSION['profile_otp_to'] = $currentEmail;
            $_SESSION['profile_otp_sent_at'] = time();
            echo json_encode(['success'=>true, 'msg'=>$res['msg']]);
            exit;
        } else {
            echo json_encode(['success'=>false, 'msg'=>$res['msg']]);
            exit;
        }
    }

    if ($action === 'verify_otp_profile') {
        $inputOtp = trim($_POST['otp'] ?? '');
        if (empty($inputOtp)) { echo json_encode(['success'=>false,'msg'=>'Vui lòng nhập OTP.']); exit; }

        $targetEmail = $_SESSION['profile_otp_to'] ?? $currentEmail;
        $q = $pdo->prepare("SELECT * FROM otp_codes WHERE email = ? ORDER BY id DESC LIMIT 1");
        $q->execute([$targetEmail]);
        $r = $q->fetch(PDO::FETCH_ASSOC);

        if (!$r) { echo json_encode(['success'=>false,'msg'=>'Không tìm thấy OTP.']); exit; }
        if ($r['otp'] !== $inputOtp) { echo json_encode(['success'=>false,'msg'=>'OTP không chính xác.']); exit; }
        if (strtotime($r['expires_at']) < time()) { echo json_encode(['success'=>false,'msg'=>'OTP đã hết hạn.']); exit; }

        $_SESSION['profile_otp_verified_at'] = time();
        $_SESSION['profile_otp_verified_for'] = $targetEmail;
        echo json_encode(['success'=>true,'msg'=>'Xác minh OTP thành công. Bạn có thể lưu thay đổi.']);
        exit;
    }

    echo json_encode(['success'=>false,'msg'=>'Action không hợp lệ.']);
    exit;
}

/* =========================
   Final save
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['final_save'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $normalized_phone = normalizePhonePlus($phone);

    $stmt = $pdo->prepare("SELECT Email, Phone FROM user WHERE id_User = ?");
    $stmt->execute([$user_id]);
    $cur = $stmt->fetch(PDO::FETCH_ASSOC);
    $curEmail = $cur['Email'];
    $curPhone = $cur['Phone'];

    $needs_otp = ($email !== $curEmail || $normalized_phone !== $curPhone);
    if ($needs_otp) {
        if (empty($_SESSION['profile_otp_verified_at']) || (time() - $_SESSION['profile_otp_verified_at'] > 300)) {
            echo "<script>alert('Bạn cần xác thực OTP trước khi thay đổi Email/SĐT.'); window.history.back();</script>";
            exit;
        }
        if (($_SESSION['profile_otp_verified_for'] ?? '') !== ($curEmail)) {
            echo "<script>alert('OTP không hợp lệ cho thao tác này.'); window.history.back();</script>";
            exit;
        }
    }

    // Avatar upload
    $avatarFilename = null;
    if (!empty($_FILES['avatar']['name'])) {
        $up = $_FILES['avatar'];
        if ($up['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (!in_array($ext, $allowed)) {
                echo "<script>alert('File avatar không hợp lệ.'); window.history.back();</script>"; exit;
            }
            $uploadDir = __DIR__ . '/../../uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $safe = 'user_'.$user_id.'_'.time().'.'.$ext;
            $dest = $uploadDir.$safe;
            if (move_uploaded_file($up['tmp_name'],$dest)) {
                $avatarFilename = 'uploads/avatars/'.$safe;
            } else { echo "<script>alert('Lỗi lưu avatar'); window.history.back();</script>"; exit; }
        }
    }

    // Update DB
    try {
        if ($avatarFilename) {
            $q = $pdo->prepare("UPDATE user SET Username=?, Email=?, Phone=?, Avatar=? WHERE id_User=?");
            $q->execute([$username, $email, $normalized_phone, $avatarFilename, $user_id]);
        } else {
            $q = $pdo->prepare("UPDATE user SET Username=?, Email=?, Phone=? WHERE id_User=?");
            $q->execute([$username, $email, $normalized_phone, $user_id]);
        }
    } catch (Exception $e) {
        echo "<script>alert('Lỗi lưu thông tin: ".addslashes($e->getMessage())."'); window.history.back();</script>"; exit;
    }

    unset($_SESSION['profile_otp_verified_at']);
    unset($_SESSION['profile_otp_verified_for']);
    unset($_SESSION['profile_otp_to']);
    unset($_SESSION['profile_otp_sent_at']);

    echo "<script>window.location='index.php?page=profile&success=1';</script>"; exit;
}

/* =========================
   Prepare view
   ========================= */
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_User = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$oldEmail = $user['Email'];
$oldPhone = $user['Phone'];
$avatarSrc = !empty($user['Avatar']) ? htmlspecialchars($user['Avatar']) : 'App/public/img/default-avatar.png';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Profile</title>
<style>
/* --- giữ nguyên CSS cũ của bạn --- */
body{background:#eef1f6;margin:0;padding:0}
.profile-wrapper{max-width:1150px;margin:50px auto;background:#fff;border-radius:16px;overflow:hidden;display:grid;grid-template-columns:300px 1fr;box-shadow:0 20px 50px rgba(0,0,0,.1)}
.profile-sidebar{background:linear-gradient(135deg,#6a11cb,#2575fc);padding:50px 25px;color:#fff;text-align:center}
.avatar{width:120px;height:120px;border-radius:50%;border:4px solid rgba(255,255,255,0.2);object-fit:cover}
.profile-name{font-size:22px;font-weight:700;margin-top:12px}
.profile-email{opacity:.9;margin-top:4px}
.profile-menu{margin-top:35px;text-align:left}
.profile-menu a{padding:12px 14px;margin:8px 0;background:rgba(255,255,255,.1);border-radius:10px;display:block;color:#fff;text-decoration:none}
.profile-menu a:hover{background:rgba(255,255,255,.18)}
.profile-content{padding:40px}
.profile-title{font-size:28px;font-weight:700;margin-bottom:18px}
.info-card{background:#fbfdff;padding:22px;border:1px solid #eef2f7;border-radius:14px;margin-bottom:20px}
.info-row{display:flex;gap:15px;border-bottom:1px dashed #eee;padding:12px 0}
.info-row:last-child{border-bottom:none}
.info-icon{width:46px;height:46px;border-radius:10px;background:#eef5ff;display:flex;align-items:center;justify-content:center;font-size:20px;color:#0b63d0}
.value{margin-top:4px;font-size:17px;font-weight:600;color:#1b2b45}
.btn{padding:12px 20px;border:0;border-radius:10px;cursor:pointer;font-weight:600}
.btn-edit{background:#2575fc;color:#fff;}
.btn-edit:hover{filter:brightness(.9)}
.popup-overlay{position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);z-index:10000}
.popup-box{background:white;padding:28px;border-radius:18px;min-width:350px;max-width:92%;position:relative;animation:popupScale .25s ease;box-shadow:0 20px 50px rgba(0,0,0,.15)}
@keyframes popupScale{from{opacity:0;transform:scale(.85)}to{opacity:1}}
.popup-close{position:absolute;top:10px;right:14px;font-size:22px;border:0;background:none;color:#6b7280;cursor:pointer}
.popup-title{font-size:22px;font-weight:700;margin-bottom:8px}
.popup-text{color:#475569;margin-bottom:18px}
.popup-icon{font-size:42px;margin-bottom:10px}
.popup-actions{text-align:right;margin-top:20px}
.btn-cancel{background:#e5e7eb;color:#111;margin-right:6px; margin-bottom: 10px;}
.btn-save{background:#6a11cb;color:#fff}
.popup-box input{width:100%;padding:12px;margin:8px 0;border-radius:10px;border:1px solid #d4d7dd;font-size:15px}
#otpCountdown{font-weight:600;margin-top:5px;margin-bottom:5px;}
#resendBtn{margin-top:10px;}
.note {font-size:13px;color:#666;margin-top:6px}
</style>
</head>
<body>
<div class="profile-wrapper">
<aside class="profile-sidebar">
    <img src="<?= $avatarSrc ?>" class="avatar" alt="avatar">
    <div class="profile-name"><?= htmlspecialchars($user['Username']) ?></div>
    <div class="profile-email"><?= htmlspecialchars($user['Email']) ?></div>
    <div class="profile-menu">
        <a href="index.php?page=profile">👤 Thông tin cá nhân</a>
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
    <button class="btn btn-edit" id="openEdit">✏️ Chỉnh sửa</button>
</section>
</div>

<!-- POPUP EDIT -->
<div id="popupEdit" class="popup-overlay">
  <div class="popup-box">
    <button class="popup-close" onclick="closeEdit()">&times;</button>
    <div class="popup-icon">✏️</div>
    <div class="popup-title">Chỉnh sửa thông tin</div>
    <form id="editForm" method="post" enctype="multipart/form-data">
      <input id="f_username" name="username" value="<?= htmlspecialchars($user['Username']) ?>" required>
      <input id="f_email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" type="email" required>
      <input id="f_phone" name="phone" value="<?= htmlspecialchars($user['Phone']) ?>" required>
      <label style="font-size:13px;margin-top:8px">Avatar (jpg, png, webp, gif)</label>
      <input id="f_avatar" name="avatar" type="file" accept="image/*">
      <div class="note">Nếu muốn thay avatar, chọn file rồi nhấn Lưu.</div>
    </form>
    <div class="popup-actions">
        <button class="btn btn-cancel" onclick="closeEdit()">Hủy</button>
        <button class="btn btn-save" onclick="startValidation()">Lưu</button>
    </div>
  </div>
</div>

<!-- POPUP OTP -->
<div id="popupOTP" class="popup-overlay">
  <div class="popup-box">
    <button class="popup-close" onclick="closeOTP()">&times;</button>
    <div class="popup-icon">🔐</div>
    <div class="popup-title">Xác thực OTP</div>
    <div class="popup-text">Mã OTP đã gửi tới email hiện tại của bạn: <strong><?= htmlspecialchars($oldEmail) ?></strong></div>
    <input id="otpCode" placeholder="Nhập mã OTP">
    <div id="otpCountdown">05:00</div>
    <button class="btn btn-save" id="resendBtn" onclick="requestSendOtp(true)" disabled>Gửi lại OTP</button>
    <div class="popup-actions">
        <button class="btn btn-cancel" onclick="closeOTP()">Hủy</button>
        <button class="btn btn-save" onclick="verifyOTP()">Xác nhận</button>
    </div>
  </div>
</div>

<!-- POPUP CONFIRM -->
<div id="popupConfirm" class="popup-overlay">
  <div class="popup-box">
    <div class="popup-icon">⚠️</div>
    <div class="popup-title">Xác nhận lưu</div>
    <div class="popup-text">Bạn có chắc muốn lưu thay đổi?</div>
    <div class="popup-actions">
        <button class="btn btn-cancel" onclick="closeConfirm()">Hủy</button>
        <button class="btn btn-save" onclick="finalSubmit()">Lưu</button>
    </div>
  </div>
</div>

<!-- POPUP SUCCESS -->
<div id="popupSuccess" class="popup-overlay">
  <div class="popup-box">
    <div class="popup-icon" style="color:#22c55e">✔️</div>
    <div class="popup-title">Thành công</div>
    <div class="popup-text">Thông tin đã được cập nhật.</div>
  </div>
</div>

<script>
let oldEmail = "<?= addslashes($oldEmail) ?>";
let oldPhone = "<?= addslashes($oldPhone) ?>";
let needVerify = false;

// OTP countdown
let otpCountdownTime = 300; // 5 phút
let otpTimerInterval = null;

function startOtpCountdown(){
    clearInterval(otpTimerInterval);
    otpCountdownTime = 300;
    updateOtpDisplay();
    otpTimerInterval = setInterval(()=>{
        otpCountdownTime--;
        updateOtpDisplay();
        if(otpCountdownTime<=0){
            clearInterval(otpTimerInterval);
            document.getElementById("resendBtn").disabled = false;
        }
    },1000);
}
function updateOtpDisplay(){
    const min = Math.floor(otpCountdownTime/60).toString().padStart(2,'0');
    const sec = (otpCountdownTime%60).toString().padStart(2,'0');
    document.getElementById("otpCountdown").textContent = `${min}:${sec}`;
}

function openEdit(){document.getElementById("popupEdit").style.display="flex";}
function closeEdit(){document.getElementById("popupEdit").style.display="none";}
function closeConfirm(){document.getElementById("popupConfirm").style.display="none";}
function closeOTP(){
    document.getElementById("popupOTP").style.display="none";
    clearInterval(otpTimerInterval);
    document.getElementById("resendBtn").disabled = true;
}
document.getElementById("openEdit").onclick = openEdit;

function startValidation(){
    let username = document.getElementById("f_username").value.trim();
    let email = document.getElementById("f_email").value.trim();
    let phone = document.getElementById("f_phone").value.trim();

    if(phone.length===9 && !phone.startsWith("0")) phone="0"+phone;

    let regexEmail=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!regexEmail.test(email)){ alert("Email không hợp lệ!"); return; }
    let regexPhone=/^0\d{9}$/;
    if(!regexPhone.test(phone)){ alert("Số điện thoại không hợp lệ!"); return; }

    needVerify=(email!==oldEmail)||(phone!==oldPhone);
    if(!needVerify){ openConfirm(); return; }

    requestSendOtp(false);
}

function requestSendOtp(forceResend){
    document.getElementById("resendBtn").disabled = true;
    fetch(window.location.href,{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:new URLSearchParams({action:'send_otp_profile', resend: forceResend?1:0})
    }).then(r=>r.json()).then(j=>{
        if(j.success){
            document.getElementById("popupEdit").style.display="none";
            document.getElementById("popupOTP").style.display="flex";
            startOtpCountdown();
            alert(j.msg);
        } else {
            alert("Không gửi được OTP: "+j.msg);
            document.getElementById("resendBtn").disabled = false;
        }
    }).catch(e=>{ alert("Lỗi mạng, thử lại."); document.getElementById("resendBtn").disabled=false; });
}

function verifyOTP(){
    let otp=document.getElementById("otpCode").value.trim();
    if(!otp){ alert("Nhập OTP"); return; }
    fetch(window.location.href,{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:new URLSearchParams({action:'verify_otp_profile', otp:otp})
    }).then(r=>r.json()).then(j=>{
        if(j.success){
            alert(j.msg);
            clearInterval(otpTimerInterval);
            document.getElementById("popupOTP").style.display="none";
            openConfirm();
        } else {
            alert("Xác thực thất bại: "+j.msg);
        }
    }).catch(e=>{ alert("Lỗi mạng, thử lại."); });
}

function openConfirm(){ document.getElementById("popupConfirm").style.display="flex"; }

function finalSubmit(){
    let form=document.getElementById("editForm");
    let fd=new FormData(form);
    fd.append('final_save','1');
    fetch(window.location.href,{method:'POST',body:fd})
    .then(r=>r.text())
    .then(html=>{ location.reload(); })
    .catch(e=>{ alert('Lỗi khi gửi form.'); });
}
</script>
</body>
</html>

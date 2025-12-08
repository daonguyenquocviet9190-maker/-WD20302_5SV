<?php
if (session_status() == PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/../../Model/database.php";
$db = new Database("localhost", "5svcode", "root", "");
$pdo = $db->connect();

// Bắt buộc login
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem thông tin'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin user
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_User = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$oldEmail = $user['Email'];
$oldPhone = $user['Phone'];

// Sau khi xác thực OTP → Lưu DB
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['final_save'])) {
    $stmt = $pdo->prepare("
        UPDATE user 
        SET Username = :u, Email = :e, Phone = :p
        WHERE id_User = :id
    ");
    $stmt->execute([
        'u' => $_POST['username'],
        'e' => $_POST['email'],
        'p' => $_POST['phone'],
        'id' => $user_id
    ]);

    echo "<script>window.location='index.php?page=profile&success=1';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Profile</title>
<style>
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
.btn-cancel{background:#e5e7eb;color:#111;margin-right:6px;}
.btn-save{background:#6a11cb;color:#fff}
.popup-box input{width:100%;padding:12px;margin:8px 0;border-radius:10px;border:1px solid #d4d7dd;font-size:15px}
#otpCountdown{font-weight:600;margin-top:5px;margin-bottom:5px;}
#resendBtn{margin-top:10px;}
</style>
</head>

<body>
<div class="profile-wrapper">
<aside class="profile-sidebar">
    <img src="" class="avatar">
    <div class="profile-name"><?= htmlspecialchars($user['Username']) ?></div>
    <div class="profile-email"><?= htmlspecialchars($user['Email']) ?></div>
    <div class="profile-menu">
        <a href="#">👤 Thông tin cá nhân</a>
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
    <form id="editForm">
      <input id="f_username" value="<?= $user['Username'] ?>" required>
      <input id="f_email" value="<?= $user['Email'] ?>" type="email" required>
      <input id="f_phone" value="<?= $user['Phone'] ?>" required>
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
    <div class="popup-text">Nhập mã OTP được gửi tới email cũ</div>
    <input id="otpCode" placeholder="Nhập mã OTP">
    <div id="otpCountdown">60 giây</div>
    <button class="btn btn-save" id="resendBtn" onclick="resendOTP()" disabled>Gửi lại OTP</button>
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
let oldEmail = "<?= $oldEmail ?>";
let oldPhone = "<?= $oldPhone ?>";
let generatedOTP = "";
let needVerifyEmail = false;
let otpTime = 60;
let otpTimer = null;

function openEdit(){document.getElementById("popupEdit").style.display="flex";}
function closeEdit(){document.getElementById("popupEdit").style.display="none";}
function closeConfirm(){document.getElementById("popupConfirm").style.display="none";}
function closeOTP(){
    document.getElementById("popupOTP").style.display="none";
    clearInterval(otpTimer);
}
document.getElementById("openEdit").onclick = openEdit;

// -------------------
// Validation & OTP
// -------------------
function startValidation(){
    let username = document.getElementById("f_username").value.trim();
    let email = document.getElementById("f_email").value.trim();
    let phone = document.getElementById("f_phone").value.trim();

    // Tự thêm 0 cho phone
    if(phone.length===9 && !phone.startsWith("0")){
        phone = "0"+phone;
        document.getElementById("f_phone").value = phone;
    }

    needVerifyEmail = email !== oldEmail;

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!regexEmail.test(email)){alert("Email không hợp lệ!");return;}

    let regexPhone = /^0\d{9}$/;
    if(!regexPhone.test(phone)){alert("Số điện thoại không hợp lệ!");return;}

    if(!needVerifyEmail){openConfirm();return;}

    // Tạo OTP
    generatedOTP = Math.floor(100000+Math.random()*900000);
    console.log("OTP:", generatedOTP);

    document.getElementById("popupEdit").style.display="none";
    document.getElementById("popupOTP").style.display="flex";

    // Start countdown
    otpTime = 60;
    document.getElementById("resendBtn").disabled = true;
    updateCountdown();
    otpTimer = setInterval(updateCountdown,1000);
}

function updateCountdown(){
    let display = document.getElementById("otpCountdown");
    if(otpTime>0){
        display.textContent = otpTime+" giây";
        otpTime--;
    } else{
        display.textContent = "OTP đã hết hạn";
        document.getElementById("resendBtn").disabled = false;
        clearInterval(otpTimer);
    }
}

function resendOTP(){
    generatedOTP = Math.floor(100000+Math.random()*900000);
    console.log("Resend OTP:", generatedOTP);
    otpTime=60;
    document.getElementById("resendBtn").disabled=true;
    otpTimer = setInterval(updateCountdown,1000);
    alert("OTP mới đã gửi tới email cũ: "+oldEmail+" (Test OTP: "+generatedOTP+")");
}

function verifyOTP(){
    let entered = document.getElementById("otpCode").value.trim();
    if(entered!=generatedOTP){alert("OTP không đúng!");return;}
    clearInterval(otpTimer);
    document.getElementById("popupOTP").style.display="none";
    openConfirm();
}

// -------------------
// Confirm & Submit
// -------------------
function openConfirm(){document.getElementById("popupConfirm").style.display="flex";}
function finalSubmit(){
    let form = document.createElement("form");
    form.method="POST";
    form.innerHTML = `
        <input name="username" value="${document.getElementById("f_username").value}">
        <input name="email" value="${document.getElementById("f_email").value}">
        <input name="phone" value="${document.getElementById("f_phone").value}">
        <input name="final_save" value="1">
    `;
    document.body.appendChild(form);
    document.getElementById("popupConfirm").style.display="none";
    document.getElementById("popupSuccess").style.display="flex";
    setTimeout(()=>form.submit(),800);
}
</script>
</body>
</html>

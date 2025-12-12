<?php
require_once 'App/Model/sms/SpeedSMSAPI.php';

// Dùng token bạn có
$token = "WmqIPsIR8zktKoqxv3_gvzt-00p40TDI";
$sms = new SpeedSMSAPI($token);

// Số điện thoại nhận thử OTP (ví dụ chính số của bạn)
$phone = "84937781823"; 
$otp = rand(100000, 999999);
$message = "Ma OTP test 5SV Sport: $otp";

try {
    $result = $sms->sendOTP($phone, $message);
    echo "<pre>";
    print_r($result);
    echo "</pre>";
} catch (Exception $e) {
    echo "Lỗi khi gửi SMS: " . $e->getMessage();
}
?>

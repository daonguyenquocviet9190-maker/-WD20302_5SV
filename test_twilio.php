<?php
// test_otp_twilio.php

require_once __DIR__ . '/vendor/autoload.php'; // đường dẫn tới autoload.php của Composer

use Twilio\Rest\Client;

// --- Cấu hình Twilio ---
$twilio_sid    = "ACa8f46a2839673948af6144605d60b5ed"; // Account SID
$twilio_token  = "425efb7c5ea8700112db9c200ad4eb4c";   // Auth Token
$twilio_number = "+12272847136";                       // Số Twilio bạn mua (From)
$to_number     = "+840937781823";                       // Số VN đã verify (To)

// Kiểm tra Twilio SDK
if(!class_exists('Twilio\Rest\Client')){
    die("Twilio SDK chưa load! Kiểm tra vendor/autoload.php");
}

// Tạo client Twilio
$client = new Client($twilio_sid, $twilio_token);

// Tạo OTP ngẫu nhiên 6 chữ số
$otp = rand(100000, 999999);

try {
    $message = $client->messages->create(
        $to_number,
        [
            'from' => $twilio_number,
            'body' => "OTP thử nghiệm từ 5SV Sport: $otp"
        ]
    );

    echo "Gửi SMS thành công!\n";
    echo "OTP: $otp\n";
    echo "SID: ".$message->sid."\n";
} catch (\Twilio\Exceptions\RestException $e){
    echo "Gửi SMS thất bại:\n";
    echo $e->getMessage()."\n";
}

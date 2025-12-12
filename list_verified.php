<?php
require_once __DIR__ . '/vendor/autoload.php'; // đường dẫn tới autoload.php

use Twilio\Rest\Client;

// --- Cấu hình Twilio ---
$twilio_sid   = "ACa8f46a2839673948af6144605d60b5ed"; // Account SID
$twilio_token = "425efb7c5ea8700112db9c200ad4eb4c";   // Auth Token

try {
    $client = new Client($twilio_sid, $twilio_token);

    echo "<h2>Danh sách số điện thoại đã verify:</h2><ul>";

    // Lấy danh sách các Verified Caller IDs
    $numbers = $client->incomingPhoneNumbers->read(); // Số Twilio mua
    if(!empty($numbers)){
        echo "<li><strong>Số Twilio bạn mua:</strong></li>";
        foreach($numbers as $num){
            echo "<li>{$num->phoneNumber}</li>";
        }
    }

    // Verified Caller IDs (số đã verify với trial account)
    $verified = $client->outgoingCallerIds->read();
    if(!empty($verified)){
        echo "<li><strong>Số đã verify:</strong></li>";
        foreach($verified as $v){
            echo "<li>{$v->phoneNumber}</li>";
        }
    }

    echo "</ul>";
} catch (Exception $e){
    echo "Lỗi: ".$e->getMessage();
}
?>
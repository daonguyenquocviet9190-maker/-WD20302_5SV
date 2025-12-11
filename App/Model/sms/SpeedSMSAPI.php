<?php
class SpeedSMSAPI {
    const ROOT_URL = "https://api.speedsms.vn/sms";

    private $access_token;

    public function __construct($token) {
        $this->access_token = $token;
    }

    /**
     * Chuẩn hóa số điện thoại sang +84
     */
    private function normalizePhone($phone) {
        $phone = trim($phone);
        $phone = preg_replace('/\D+/', '', $phone); // chỉ giữ số

        // Nếu bắt đầu là 0 → đổi thành +84
        if (substr($phone, 0, 1) === '0') {
            $phone = '+84' . substr($phone, 1);
        }
        // Nếu bắt đầu là 84 → thêm dấu +
        elseif (substr($phone, 0, 2) === '84') {
            $phone = '+' . $phone;
        }
        // Nếu chưa có +84 → thêm
        elseif (substr($phone, 0, 3) !== '+84') {
            $phone = '+84' . $phone;
        }
        return $phone;
    }

    /**
     * Gọi API SpeedSMS
     */
    private function callAPI($endpoint, $data = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::ROOT_URL . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->access_token . ":x");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // timeout 10s

        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return ["success" => false, "error" => $err];
        }

        $json = json_decode($res, true);
        if (!$json) $json = ["success" => false, "error" => "Không decode được JSON"];

        return $json;
    }

    /**
     * Gửi OTP SMS
     * @param string $phone Số điện thoại
     * @param string $content Nội dung SMS
     */
    public function sendOTP($phone, $content) {
        $phone = $this->normalizePhone($phone);

        $data = [
            "to" => [$phone],
            "content" => $content,
            "type" => 1 // 1 = SMS OTP
        ];

        $res = $this->callAPI("/send", $data);

        // Log chi tiết
        file_put_contents(
            "sms_debug.log",
            "[" . date("Y-m-d H:i:s") . "] Phone: $phone | Content: $content | Result: " . json_encode($res) . "\n",
            FILE_APPEND
        );

        if (isset($res['code']) && $res['code'] == 1000) {
            return ["success" => true];
        } else {
            $errorMsg = $res['error'] ?? ($res['message'] ?? "Lỗi không xác định từ SpeedSMS");
            return ["success" => false, "error" => $errorMsg];
        }
    }
}
?>

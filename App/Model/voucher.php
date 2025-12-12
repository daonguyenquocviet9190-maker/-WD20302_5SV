<?php
// File: App/Model/Voucher.php

class Voucher { 
    public $db;  // Đối tượng Database
    
    public function __construct(){ 
        // Sau khi chạy, $this->db là đối tượng Database
        $this->db = new Database('localhost', '5svcode', 'root', ''); 
        $this->db->connect(); // Kết nối được thiết lập
    }

    /**
     * Kiểm tra tính hợp lệ của Voucher
     * (Giữ nguyên logic của bạn)
     */
    public function checkValidity($voucher, $order_total, $user_id = null) {
        if (!$voucher) {
            return ['status' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.'];
        }

        $now = time();
        $start_date = strtotime($voucher['start_date']);
        $end_date = strtotime($voucher['end_date']);

        // 1. Kiểm tra thời gian hiệu lực
        if ($now < $start_date || $now > $end_date) {
            return ['status' => false, 'message' => 'Mã giảm giá đã hết hạn sử dụng.'];
        }

        // 2. Kiểm tra số lần sử dụng tối đa (usage_limit)
        if ($voucher['usage_limit'] > 0 && $voucher['used_count'] >= $voucher['usage_limit']) {
            return ['status' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        // 3. Kiểm tra giá trị đơn hàng tối thiểu (min_order_amount)
        if ($order_total < $voucher['min_order_amount']) {
            $min_amount_formatted = number_format($voucher['min_order_amount'], 0, ',', '.');
            return ['status' => false, 'message' => "Đơn hàng tối thiểu phải là {$min_amount_formatted}đ để áp dụng mã này."];
        }

        // 4. Kiểm tra giới hạn sử dụng cho mỗi người dùng (user_limit)
        if ($user_id && $voucher['user_limit'] > 0) {
            $used_by_user = $this->getUserUsageCount($voucher['id'], $user_id);
            if ($used_by_user >= $voucher['user_limit']) {
                return ['status' => false, 'message' => 'Bạn đã sử dụng mã giảm giá này tối đa số lần cho phép.'];
            }
        }
        
        return ['status' => true, 'message' => 'Áp dụng mã giảm giá thành công!'];
    }
    
    /**
     * Tính toán số tiền giảm giá thực tế (Giữ nguyên logic của bạn)
     */
    public function calculateDiscount($voucher, $order_total) {
        $discount_amount = 0;
        if ($voucher['discount_type'] === 'percent') {
            $percent_value = $voucher['discount_value'] / 100;
            $discount_amount = $order_total * $percent_value;
            if ($voucher['max_discount_amount'] > 0 && $discount_amount > $voucher['max_discount_amount']) {
                $discount_amount = $voucher['max_discount_amount'];
            }
        } elseif ($voucher['discount_type'] === 'fixed') {
            $discount_amount = $voucher['discount_value'];
            if ($discount_amount > $order_total) {
                $discount_amount = $order_total;
}
        }
        return round($discount_amount, 2);
    }

    /**
     * Lấy thông tin Voucher bằng Code (SỬ DỤNG $this->db->get_one)
     */
    public function getVoucherByCode($code) {
        $sql = "SELECT * FROM voucher WHERE code = ? AND is_active = 1";
        // get_one là phương thức public trong database.php để lấy 1 dòng
        return $this->db->get_one($sql, [$code]); 
    }

    /**
     * Lấy số lần User đã sử dụng Voucher này (SỬ DỤNG $this->db->getConnection)
     */
    public function getUserUsageCount($voucher_id, $user_id) {
        if (!$user_id) {
            return 0;
        }
        $sql = "SELECT COUNT(*) FROM donhang WHERE voucher_id = ? AND id_user = ?";
        
        // Dùng getConnection() để lấy đối tượng PDO thực tế và dùng fetchColumn()
        $conn = $this->db->getConnection(); 
        $stmt = $conn->prepare($sql);
        $stmt->execute([$voucher_id, $user_id]);
        return $stmt->fetchColumn(); 
    }
    
    /**
     * Tăng số lần đã sử dụng của Voucher (SỬ DỤNG $this->db->action)
     */
    public function incrementUsedCount($voucher_id) {
        $sql = "UPDATE voucher SET used_count = used_count + 1 WHERE id = ?";
        // action là phương thức public trong database.php cho INSERT/UPDATE/DELETE
        return $this->db->action($sql, [$voucher_id]); 
    }
    // File: App/Model/Voucher.php (Dán vào cuối Class Voucher)

    /**
     * Lấy tất cả Voucher (Dùng cho Admin Listing)
     */
  public function get_all_vouchers() {
    $sql = "SELECT * FROM voucher";
    // Phải đảm bảo hàm get_all() trong Database hoạt động đúng
    return $this->db->get_all($sql);
}

    /**
     * Lấy Voucher theo ID (Dùng cho Admin Edit)
     */
    public function get_voucher_by_id($id) {
        $sql = "SELECT * FROM voucher WHERE id = ?";
        // Sử dụng phương thức get_one() của class Database
        return $this->db->get_one($sql, [$id]); 
    }

    /**
     * Thêm mới Voucher
     */
    public function add_voucher($code, $type, $value, $max, $min, $start, $end, $usage, $user_limit, $p_ids, $active) {
        $sql = "INSERT INTO voucher (code, discount_type, discount_value, max_discount_amount, min_order_amount, start_date, end_date, usage_limit, user_limit, product_ids, is_active, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $params = [$code, $type, $value, $max, $min, $start, $end, $usage, $user_limit, $p_ids, $active];
        return $this->db->action($sql, $params);
    }

    /**
     * Cập nhật Voucher
     */
    public function update_voucher($id, $code, $type, $value, $max, $min, $start, $end, $usage, $user_limit, $p_ids, $active) {
        $sql = "UPDATE voucher SET 
                    code = ?, discount_type = ?, discount_value = ?, max_discount_amount = ?,
min_order_amount = ?, start_date = ?, end_date = ?, usage_limit = ?, 
                    user_limit = ?, product_ids = ?, is_active = ?, updated_at = NOW()
                WHERE id = ?";
        $params = [$code, $type, $value, $max, $min, $start, $end, $usage, $user_limit, $p_ids, $active, $id];
        return $this->db->action($sql, $params);
    }

    /**
     * Xóa Voucher
     */
    public function delete_voucher($id) {
        $sql = "DELETE FROM voucher WHERE id = ?";
        return $this->db->action($sql, [$id]);
    }
    
}
?>

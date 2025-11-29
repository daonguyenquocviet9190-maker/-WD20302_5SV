<?php
// FILE: App/Model/order.php

// Giả định bạn đã có class Database hoặc kết nối Database ở đây
class Order {
    //  connect DB
    public $db;  
    
    public function __construct(){ 
        // Đảm bảo class Database đã được require/autoload trước đó
        $this->db = new Database('localhost', '5svcode', 'root', '');
        $this->db->connect();
    }
    
    public function get_all_orders  () {
    // Ví dụ: Giả sử tên bảng chính xác là don_hang
    $sql = "SELECT dh.id_dh as id, u.Username as customer_name, u.Phone, dh.ngay_mua as time 
            FROM [DÁN TÊN BẢNG CHÍNH XÁC] dh // <--- DÁN TÊN VÀO ĐÂY
            JOIN users u ON dh.id_User = u.id_User
            ORDER BY dh.ngay_mua DESC";
    // ...
}

    // Lấy chi tiết đơn hàng
    public function get_order_by_id($id) {
        $sql = "SELECT * FROM don_hang WHERE id_dh = ?";
        
        // Sửa lỗi: Thay thế $this->pdo_query_one($sql, $id)
        // và truyền tham số $id vào phương thức get_one() của DB
        return $this->db->get_one($sql, [$id]); // <--- CHỖ SỬA ĐÂY
    }
    
    // Xóa đơn hàng và chi tiết
    public function remove_order($id) {
        // ... (Logic xóa an toàn)
        // Ví dụ, sử dụng phương thức action() của DB để xóa:
        $sql_detail = "DELETE FROM chi_tiet_don_hang WHERE id_dh = ?";
        $this->db->action($sql_detail, [$id]); 

        $sql_order = "DELETE FROM don_hang WHERE id_dh = ?";
        $this->db->action($sql_order, [$id]);
    }
}
?>
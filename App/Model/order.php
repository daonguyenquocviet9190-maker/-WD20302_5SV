<?php
// FILE: App/Model/order.php

// Giả định bạn đã có class Database hoặc kết nối Database ở đây
class Order {
    //  connect DB
    public $db;  // khởi tạo biến 
    public function __construct(){ // sau đó sử dụng hàm để kết nối
        $this->db = new Database('localhost', '5svcode', 'root', ''); // khai báo tham số đầu vào
        $this->db->connect();  // truy xuất tới hàm connect
    }
    public function get_all_orders  () {
        // Cần JOIN với bảng users (để lấy customer_name, phone) và tính tổng tiền
        $sql = "SELECT dh.id_dh as id, u.name as customer_name, u.phone, dh.ngay_mua as time 
                FROM don_hang dh
                JOIN users u ON dh.id_User = u.id_User
                ORDER BY dh.ngay_mua DESC";
        return $this->pdo_query($sql); // Giả sử dùng hàm truy vấn của bạn
    }

    // Lấy chi tiết đơn hàng
    public function get_order_by_id($id) {
        $sql = "SELECT * FROM don_hang WHERE id_dh = ?";
        return $this->pdo_query_one($sql, $id);
    }
    
    // Xóa đơn hàng và chi tiết
    public function remove_order($id) {
        // Cần thực hiện Transaction để xóa trong cả 2 bảng: chi_tiet_don_hang và don_hang
        // 1. Xóa chi tiết: DELETE FROM chi_tiet_don_hang WHERE id_dh = ?
        // 2. Xóa đơn hàng: DELETE FROM don_hang WHERE id_dh = ?
        // ... (Logic xóa an toàn)
    }
}
?>
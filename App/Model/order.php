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
    
    public function get_all_orders() {
        // Ví dụ: Giả sử tên bảng chính xác là don_hang
        $sql = "SELECT * FROM donhang";
        return $this->db->get_all($sql);
    }

    // Lấy chi tiết đơn hàng (Đã sửa lỗi, sử dụng get_one với tham số)
    public function get_order_by_id($id) {
        $sql = "SELECT * FROM donhang WHERE id_dh = ?"; // Sử dụng placeholder
        
        // Truyền $id vào phương thức get_one() của DB
        return $this->db->get_one($sql, [$id]); 
    }
    
    // Xóa đơn hàng và chi tiết (Sử dụng action() đã được sửa để nhận tham số)
    public function remove_order($id) {
        // Xóa chi tiết đơn hàng trước (quan trọng cho ràng buộc khóa ngoại)
        $sql_detail = "DELETE FROM chi_tiet_don_hang WHERE id_dh = ?";
        $this->db->action($sql_detail, [$id]); 

        // Xóa đơn hàng
        $sql_order = "DELETE FROM donhang WHERE id_dh = ?";
        $this->db->action($sql_order, [$id]);
    }
    
}
?>
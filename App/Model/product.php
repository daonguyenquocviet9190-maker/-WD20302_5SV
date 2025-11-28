<?php
include_once 'database.php';
class Product {
    //  connect DB
    public $db;  // khởi tạo biến 
    public function __construct(){ // sau đó sử dụng hàm để kết nối
        $this->db = new Database('localhost', '5svcode', 'root', ''); // khai báo tham số đầu vào
        $this->db->connect();  // truy xuất tới hàm connect
    }
    // phương thức lấy tất cả sản phẩm
     public function getall_sp(){
        $sql = "SELECT * FROM sanpham";
        return $this->db->get_all($sql);
    }
    public function get_sp_moi() {
    $sql = "SELECT * FROM sanpham ORDER BY id_SP ASC LIMIT 11";
    return $this->db->get_all($sql);
}
public function get_deal_111k() {
    $sql = "SELECT * FROM sanpham WHERE sale_price = 111000 ORDER BY id_SP ASC";
    return $this->db->get_all($sql);
}
    // phương thức lọc sản phẩm theo danh mục
     public function get_sp_byIDDM($cat_id){
        $sql = "SELECT * FROM sanpham WHERE Cat_ID = {$cat_id}";
        return $this->db->get_all($sql);
    }
    // phương thức lọc sản phẩm theo ID SP
   public function get_sp_byID($id){
    $sql = "SELECT * FROM sanpham WHERE id_SP = {$id}";
    return $this->db->get_one($sql);
}
    // phương thức lọc sản phẩm liên quan
   public function get_sp_lq($id, $id_dm){
    $sql = "SELECT * FROM sanpham 
            WHERE id_DM = {$id_dm} 
            AND id_SP != {$id}
            ORDER BY RAND() 
            LIMIT 3";
    return $this->db->get_all($sql);
}

    // Thêm sản phẩm
    public function add_sp($name, $price, $stock, $cat_id, $img){
        $sql = "INSERT INTO sanpham (Name, Price, stock, id_DM, img)
                VALUES ('{$name}', {$price}, {$stock}, {$cat_id}, '{$img}')";
        return $this->db->action($sql);
    }

    // Xóa sản phẩm
    public function remove_sp($id){
        $sql = "DELETE FROM sanpham WHERE id_SP = {$id}";
        return $this->db->action($sql);
    }

    // Sửa sản phẩm
    public function update_sp($id, $name, $price, $stock, $cat_id, $img){
        $sql = "UPDATE sanpham 
                SET Name = '{$name}',
                    Price = {$price},
                    stock = {$stock},
                    id_DM = {$cat_id},
                    img = '{$img}'
                WHERE id_SP = {$id}";
        return $this->db->action($sql);
    }
    // phương thức phân trang 
    public function phan_trang($cat_id, $lim, $offset){
        $sql = "SELECT * FROM sanpham WHERE Cat_ID = {$cat_id}
        LIMIT {$lim} OFFSET {$offset}";
        return $this->db->get_all($sql);
    }
    public function phantrang($lim, $offset){
        $sql = "SELECT * FROM sanpham LIMIT {$lim} OFFSET {$offset}";
        return $this->db->get_all($sql);
    }
}
?>
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
    // phương thức lọc sản phẩm theo danh mục
     public function get_sp_byIDDM($cat_id){
        $sql = "SELECT * FROM sanpham WHERE Cat_ID = {$cat_id}";
        return $this->db->get_all($sql);
    }
    // phương thức lọc sản phẩm theo ID SP
     public function get_sp_byID($id){
        $sql = "SELECT * FROM sanpham WHERE ID = {$id}";
        return $this->db->get_all($sql);
    }
    // phương thức lọc sản phẩm liên quan
    public function get_sp_lq($id, $cat_id){
        $sql = "SELECT * FROM sanpham WHERE Cat_ID = {$cat_id} AND id != {$id}
        ORDER BY RAND() LIMIT 3";
        return $this->db->get_all($sql);
    }
    public function add_sp($name, $price, $quantity, $cat_id, $img){
        // $price = intval($price);
        // $cat_id = intval($cat_id);
        $sql = "INSERT INTO sanpham (Name, Price, Quantity, Cat_ID, Image) VALUES ('{$name}', {$price}, {$quantity}, {$cat_id}, '{$img}')";
        return $this->db->action($sql);
    }
    public function remove_sp($id){
        $sql = "DELETE FROM sanpham WHERE `ID` = {$id}";
        return $this->db->action($sql);
    }
      public function update_sp($id, $name, $price, $quantity, $cat_id, $img){
        $sql = "UPDATE `sanpham` SET `Name` = '{$name}', `Price` = {$price}, `Quantity` = {$quantity}, `Cat_ID` = {$cat_id}, `Image` = '{$img}' WHERE `ID` = {$id}";
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
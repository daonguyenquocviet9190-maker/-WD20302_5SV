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
    $sql = "SELECT * FROM sanpham WHERE gender IS NULL OR gender != 'phukien'";
    return $this->db->get_all($sql);
}
    public function get_sp_moi() {
    $sql = "SELECT * FROM sanpham ORDER BY id_SP ASC LIMIT 11";
    return $this->db->get_all($sql);
}
public function getall_size() {
    $sizes = [];
    $sql = "SHOW COLUMNS FROM sanpham LIKE 'size'";
    $row = $this->db->get_one($sql); // Database->get_one() trả về 1 mảng
    
    if ($row && isset($row['Type'])) {
        if (preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches)) {
            $sizes = explode("','", $matches[1]);
        }
    }
    return $sizes;
}


public function get_deal_111k() {
    $sql = "SELECT * FROM sanpham WHERE sale_price = 111000 ORDER BY id_SP ASC";
    return $this->db->get_all($sql);
}
 public function get_sp_nu() {
    $sql = "SELECT * FROM sanpham WHERE gender = 'nu' ";
    return $this->db->get_all($sql);
}
 public function get_sp_nam() {
    $sql = "SELECT * FROM sanpham WHERE gender = 'nam' ";
    return $this->db->get_all($sql);
}
 public function get_sp_giay() {
    $sql = "SELECT * FROM sanpham WHERE gender = 'giay' ";
    return $this->db->get_all($sql);
}
 public function get_sp_phukien() {
    $sql = "SELECT * FROM sanpham WHERE gender = 'phukien' ";
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
// Trong app/Model/product.php

public function get_sp_bongda() {
    $sql = "SELECT * FROM sanpham WHERE hoatdong = 'bongda' ORDER BY id_SP DESC";
    return $this->db->get_all($sql);
}

public function get_sp_caulong() {
    $sql = "SELECT * FROM sanpham WHERE hoatdong = 'caulong' ORDER BY id_SP DESC";
    return $this->db->get_all($sql);
}

public function get_sp_chaybo() {
    $sql = "SELECT * FROM sanpham WHERE hoatdong = 'chaybo' ORDER BY id_SP DESC";
    return $this->db->get_all($sql);
}

public function get_sp_boiloi() {
    $sql = "SELECT * FROM sanpham WHERE hoatdong = 'boiloi' ORDER BY id_SP DESC";
    return $this->db->get_all($sql);
}

public function get_sp_gym() {
    $sql = "SELECT * FROM sanpham WHERE hoatdong = 'gym' ORDER BY id_SP DESC";
    return $this->db->get_all($sql);
}

public function get_sp_macngay() {
    $sql = "SELECT * FROM sanpham WHERE hoatdong = 'macngay' ORDER BY id_SP DESC";
    return $this->db->get_all($sql);
}

    // Thêm sản phẩm
  // Thêm sản phẩm
public function add_sp($name, $price, $stock, $cat_id, $size, $img){
    $sql = "INSERT INTO sanpham (Name, Price, stock, id_DM, size, img)
            VALUES (:name, :price, :stock, :id_dm, :size, :img)";
    $stmt = $this->db->connect()->prepare($sql);
    return $stmt->execute([
        'name' => $name,
        'price' => $price,
        'stock' => $stock,
        'id_dm' => $cat_id,
        'size' => $size,
        'img' => $img
    ]);
}

// Sửa sản phẩm
public function update_sp($id, $name, $price, $stock, $cat_id, $size, $img){
    $sql = "UPDATE sanpham
            SET Name = :name,
                Price = :price,
                stock = :stock,
                id_DM = :id_dm,
                size = :size,
                img = :img
            WHERE id_SP = :id";
    $stmt = $this->db->connect()->prepare($sql);
    return $stmt->execute([
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'stock' => $stock,
        'id_dm' => $cat_id,
        'size' => $size,
        'img' => $img
    ]);
}

// Xóa sản phẩm
public function remove_sp($id){
    $sql = "DELETE FROM sanpham WHERE id_SP = :id";
    $stmt = $this->db->connect()->prepare($sql);
    return $stmt->execute(['id' => $id]);
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
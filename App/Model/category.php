<?php
include_once 'database.php';
class Category {
    //  connect DB
    public $db;  // khởi tạo biến 
    public function __construct(){ // sau đó sử dụng hàm để kết nối
        $this->db = new Database('localhost', '5svcode', 'root', ''); // khai báo tham số đầu vào
        $this->db->connect();  // truy xuất tới hàm connect
    }
    // phương thức lấy tất cả danh mục
     public function getall_dm(){
        $sql = "SELECT * FROM danhmuc";
        return $this->db->get_all($sql);
    }
    public function get_dm_gender($gender){
        if ($gender == "all") {
            return $this->getall_dm();
        }
        $sql = "SELECT * FROM danhmuc WHERE gender = '{$gender}' ORDER BY id_DM ASC";
        return $this->db->get_all($sql);
    }
    // Thêm danh mục
    public function add_dm($name){
        $sql = "INSERT INTO danhmuc (Name) VALUES ('{$name}')";
        return $this->db->action($sql);
    }

    // Xóa danh mục
    public function remove_dm($id){
        $sql = "DELETE FROM danhmuc WHERE id_DM = {$id}";
        return $this->db->action($sql);
    }

    // Sửa danh mục
    public function update_dm($id, $name){
        $sql = "UPDATE danhmuc SET Name = '{$name}' WHERE id_DM = {$id}";
        return $this->db->action($sql);
    }

    // Lấy danh mục theo ID
    public function get_dm_byID($id){
        $sql = "SELECT * FROM danhmuc WHERE id_DM = {$id}";
        return $this->db->get_one($sql); // chỉ lấy 1 record
    }
}
?>
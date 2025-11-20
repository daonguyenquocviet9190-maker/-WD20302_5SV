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
    // phương thức thêm danh mục mới
    public function add_dm($name, $description){
        $sql = "INSERT INTO danhmuc (Name, Description) VALUES ('{$name}', '{$description}')";
        return $this->db->action($sql);
    }
    // phương thức xóa danh mục
      public function remove_dm($id){
        $sql = "DELETE FROM danhmuc WHERE `ID` = {$id}";
        return $this->db->action($sql);
    }
    // phương thức sửa danh mục
     public function update_dm($id, $name, $description){
        $sql = "UPDATE `danhmuc` SET `Name` = '{$name}', `Description` = '{$description}' WHERE `ID` = {$id}";
        return $this->db->action($sql);
    }
    // phương thức lọc danh mục theo ID
     public function get_dm_byID($id){
        $sql = "SELECT * FROM danhmuc WHERE ID = {$id}";
        return $this->db->get_all($sql);
    }
}
?>
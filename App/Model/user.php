<?php
include_once 'database.php';
class User {
    //  connect DB
    public $db;  // khởi tạo biến 
    public function __construct(){ // sau đó sử dụng hàm để kết nối
        $this->db = new Database('localhost', '5svcode', 'root', ''); // khai báo tham số đầu vào
        $this->db->connect();  // truy xuất tới hàm connect
    }
    // phương thức lấy tất cả sản phẩm
    public function getall_user(){
        $sql = "SELECT * FROM user";
        return $this->db->get_all($sql);
    }
     public function add_user($user, $pass){
        $sql = "INSERT INTO user (User, Pass) VALUES ('{$user}', '{$pass}')";
        return $this->db->action($sql);
    }
      public function remove_user($id){
        $sql = "DELETE FROM user WHERE `ID` = {$id}";
        return $this->db->action($sql);
    }
      public function update_user($id, $name, $pass){
        $sql = "UPDATE `user` SET `User` = '{$name}', `Pass` = '{$pass}'  WHERE `ID` = {$id}";
        return $this->db->action($sql);
    }
    // phương thức lọc danh mục theo ID
     public function get_user_byID($id){
        $sql = "SELECT * FROM user WHERE ID = {$id}";
        return $this->db->get_all($sql);
    }
    // phương thức tìm user dựa vào username và password
    public function get_user($user, $pass) {
        $sql= "SELECT * FROM user WHERE User = '{$user}' AND Pass = '{$pass}'";
        return $this->db->get_all($sql);
    }
}
?>
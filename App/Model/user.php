<?php
// include_once 'database.php';
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
    public function get_user_by_username($username) {
        $sql = "SELECT * FROM user WHERE Username = ?";
        return $this->db->get_one($sql, [$username]); // Dùng get_one + prepared
    }
    public function add_user($username, $password, $email, $phone = null, $role = 'customer'){
        // Mã hóa password (nên dùng password_hash cho bảo mật)
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO user (Username, Password, Email, Phone, Role) 
                VALUES ('{$username}', '{$hashed_pass}', '{$email}', " . ($phone ? "'{$phone}'" : "NULL") . ", '{$role}')";
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
    public function get_new_customer_count() {
    // Ví dụ SQL: SELECT COUNT(id_user) FROM user WHERE ngay_dang_ky >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    // ... (logic kết nối DB và trả về số lượng)
}
}
?>
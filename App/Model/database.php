<?php
class Database{
    // Khai báo thông tin của DB
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;
    private $conn;
    
    // Phương thức khởi tạo DB
    public function __construct($db_host,$db_name,$db_user,$db_pass) {
      $this->db_host = $db_host;
      $this->db_name = $db_name;
      $this->db_user = $db_user;
      $this->db_pass = $db_pass;
    }

    // Phương thức kết nối DB


 public function connect() {
    try {
        $this->conn = new PDO(
            "mysql:host=$this->db_host;dbname=$this->db_name;charset=utf8", 
            $this->db_user, 
            $this->db_pass
        );
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    return $this->conn; // <--- quan trọng, phải return kết nối
}

    // FILE: App/Model/database.php (Sửa trong connect)

// phương thức hiển thị tất cả
    // Phương thức hiển thị tất cả (SELECT nhiều dòng)
    // Đã được sửa để nhận tham số $params
    public function get_all($sql, $params = []) 
    {
        $stmt = $this->conn->prepare($sql); 
        $stmt->execute($params); 
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $result;
    }

    // Phương thức hiển thị một dòng (SELECT một dòng)
    // ĐÃ SỬA: Thêm tham số $params để sử dụng Prepared Statement
    public function get_one($sql, $params = []){ 
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params); 
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Phương thức thêm, sửa, xóa (INSERT, UPDATE, DELETE)
    // ĐÃ SỬA: Thêm tham số $params để sử dụng Prepared Statement
    public function action($sql, $params = []){
        $stmt = $this->conn->prepare($sql);
        // Trả về true/false hoặc số dòng bị ảnh hưởng nếu cần
        return $stmt->execute($params); 
    }
    
    // Phương thức lấy đối tượng kết nối (hữu ích cho các trường hợp đặc biệt)
    public function getConnection() {
        return $this->conn;
    }
    public function lastInsertId() {
    return $this->conn->lastInsertId();
}

    
  }
?>

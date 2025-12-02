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
  public function get_all($sql, $params = []) // <--- CHỖ SỬA ĐÂY (Thêm $params)
    {
        $stmt = $this->conn->prepare($sql); 
        $stmt->execute($params); // <--- CHỖ SỬA ĐÂY (Truyền $params)
        // set the resulting array to associative
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $result;
    }

  public function get_one($sql){
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
  // Phương thức thêm, sửa, xóa
  public function action($sql){
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(); 
  }
  public function getConnection() {
        return $this->conn;
    }

    public function lastInsertId() {
    return $this->conn->lastInsertId();
}

    
  }
?>
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
    // FILE: App/Model/database.php (Sửa trong connect)

public function connect(){
    try {
        // TẠO ARRAY OPTIONS NÀY
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Thêm tùy chọn này:
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC 
        ];

        // Sửa dòng tạo PDO
        $this->conn = new PDO(
            "mysql:host=$this->db_host;dbname=$this->db_name", 
            $this->db_user, 
            $this->db_pass,
            $options // <--- CHỖ SỬA ĐÂY (Truyền options)
        );
        
        // Bỏ setAttribute cũ
        // $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        // ... (phần còn lại)
    } catch(PDOException $e) {
        // ...
    }
}
// phương thức hiển thị tất cả
  public function get_all($sql, $params = []) // <--- CHỖ SỬA ĐÂY (Thêm $params)
    {
        $stmt = $this->conn->prepare($sql); 
        $stmt->execute($params); // <--- CHỖ SỬA ĐÂY (Truyền $params)
        // set the resulting array to associative
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $result;
    }

    public function get_one($sql, $params = []){ // <--- CHỖ SỬA ĐÂY (Thêm $params)
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params); // <--- CHỖ SỬA ĐÂY (Truyền $params)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Phương thức thêm, sửa, xóa
    public function action($sql, $params = []){ // <--- CHỖ SỬA ĐÂY (Thêm $params)
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params); // <--- CHỖ SỬA ĐÂY (Truyền $params)
    }
 }
 
?>
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
    public function connect(){
        try {
  $this->conn = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
  // set the PDO error mode to exception
  $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
}
// phương thức hiển thị tất cả
  public function get_all($sql)
  {
    $stmt = $this->conn->prepare($sql); // prepare(chuẩn bị) đẩy câu lệnh sql lên chờ và ktra sau khi xog nó đẩy xuống
    $stmt->execute(); // thực thi code
    // set the resulting array to associative
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // setFetchMode (FETCH_ASSOC: HT dạng mảng liên kết)
    return $result;
  }

  // Phương thức thêm, sửa, xóa
  public function action($sql){
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(); 
  }
 }
?>
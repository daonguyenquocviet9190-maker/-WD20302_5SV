<?php
class Voucher {
    private $pdo;

    public function __construct() {
        require_once 'app/Model/database.php';
        $db = new Database("localhost", "5svcode", "root", "");
        $this->pdo = $db->connect();
    }

    // Lấy voucher theo code
    public function get_by_code($code) {
        $sql = "SELECT * FROM voucher WHERE code = ? AND trangthai = 'active' LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
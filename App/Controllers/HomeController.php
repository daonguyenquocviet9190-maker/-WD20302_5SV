<?php
require 'app/Model/category.php';
require 'app/Model/product.php';
require 'app/Model/user.php';
class HomeController
{
  public $danhmuc;
  public $sanpham;
  public $user;
  public function __construct()
  {
    $this->danhmuc = new Category();
    $this->sanpham = new Product();
    $this->user = new User();
  }
  public function home()
  {
    $gender = $_GET['gender'] ?? 'nu';
    // Lấy danh mục theo gender
    $dsdm = $this->danhmuc->get_dm_gender($gender);
    $deal111k = $this->sanpham->get_deal_111k();
    $sp_moi = $this->sanpham->get_sp_moi();
    //     $dssp = $this->sanpham->getall_sp();
    //     $total_sp = count($dssp);
    // // khi người dùng chọn số trang 
    // // Khi bấm trang 1:  0 1 2 (1-1)*3 = 0
    // // Khi bấm trang 2: 3 4 5 (2-1)*3 = 3
    // // Khi bấm trang 3: 6 7 8 (3-1)*3 = 6
    // // => Quy luật tính offset: offset = (số trang - 1) * 3
    // if(isset($_GET['trang'])){
    //   $page_number = $_GET['trang'];
    // } else {
    //   $page_number = 1;
    // }
    //  $lim = 4;
    //  $offset = ($page_number-1) * $lim;
    //  $dssp_phantrang = $this->sanpham->phantrang($lim,$offset);
    include 'app/View/shop/home.php';
  }
  public function product()
  {
    $dsdm = $this->danhmuc->getall_dm(); // tạo biến và lưu mảng vào biến đó
    if (isset($_GET['trang'])) {
      $page_number = $_GET['trang'];
    } else {
      $page_number = 1;
    }
    if (isset($_GET['iddm'])) {
      $dssp_1 = $this->sanpham->get_sp_byIDDM($_GET['iddm']);
      $total_pro = count($dssp_1);
      $lim = 4;
      $sp_phantrang = $this->sanpham->phan_trang($_GET['iddm'], $lim, $page_number);
      // print_r($dssp_1);
    }
    $dssp = $this->sanpham->getall_sp();
    include 'app/View/shop/product.php';
  }
  public function product_detail()
  {
    if (isset($_GET['id'])) {

      // Lấy ID sản phẩm
      $id = intval($_GET['id']);

      // Lấy sản phẩm chính
      $ct_sp = $this->sanpham->get_sp_byID($id);

      // Kiểm tra sản phẩm tồn tại
      if (!$ct_sp) {
        echo "Sản phẩm không tồn tại!";
        return;
      }

      // Lấy danh mục của sản phẩm
      $id_dm = $ct_sp['id_DM'];

      // Lấy sản phẩm liên quan
      $sp_lq = $this->sanpham->get_sp_lq($id, $id_dm);
    }
    include 'app/View/shop/product_detail.php';
  }
  public function cart()
  {
    $dssp = $this->sanpham->getall_sp();
    include 'app/View/shop/cart.php';
  }
  public function contact()
  {
    include 'app/View/shop/contact.php';
  }
  public function login()
  {
    session_start();
    ob_start(); // gọi header
    if (isset($_POST['login']) && $_POST['login']) {
      $username = $_POST['username'];
      $pass = $_POST['pass'];
      $kq = $this->user->get_user($username, $pass);
      // print_r($kq);
      if (count($kq) > 0 && is_array($kq)) {
        $role = $kq[0]['Role'];
        $_SESSION['Role'] = $role;
        $_SESSION['username'] = $kq[0]['username'];

        if ($role == 1) {
          header('location: admin.php');
        } else {
          header('location: index.php');
        }
      } else {
        header('location: index.php?page=login');
      }
    }
    include 'app/View/shop/login.php';
  }
public function giohang()
{
    session_start(); // đảm bảo session hoạt động

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Giỏ hàng trống!";
        return;
    }

    $cart = $_SESSION['cart'];
    $total = 0;

    foreach($cart as $item){
        $total += $item['price'] * $item['quantity'];
    }

    $shipping = 30000;
    $grandTotal = $total + $shipping;

    $deal111k = $this->sanpham->get_deal_111k();
    include 'App/View/shop/giohang.php';
}



  public function bosuutap()
  {
    include 'App/View/shop/bosuutap.php';
  }
  public function order()
{
    include "App/View/shop/order.php";
}

  public function register()
  {
    include 'App/View/shop/register.php';
  }

  public function nu_product()
  {
    $sp_nu = $this->sanpham->get_sp_nu();
    include 'App/View/shop/nu_product.php';
  }
  public function nam_product()
  {
    $sp_nam = $this->sanpham->get_sp_nam();
    include 'App/View/shop/nam_product.php';
  }
  public function giay_product()
  {
    $sp_giay = $this->sanpham->get_sp_giay();
    include 'App/View/shop/giay_product.php';
  }
  public function phukien()
  {
    $sp_phukien = $this->sanpham->get_sp_phukien();
    include 'App/View/shop/phukien.php';
  }
  public function order_info()
  {
    include 'App/View/shop/order_info.php';
  }
  
    public function single_deal()
  {
    
    $all_products = $this->sanpham->getall_sp();

    // Lấy tham số price từ URL
    $target_price = $_GET['price'] ?? null;

    if ($target_price !== null) {
        // Chuyển về số (11k → 11000, 111k → 111000, 211k → 211000)
        $target = (int)$target_price * 1000;

        // Lọc sản phẩm có sale_price đúng bằng giá đồng giá
        $deal_products = array_filter($all_products, function($sp) use ($target) {
            return !empty($sp['sale_price']) && $sp['sale_price'] == $target;
        });

        // Tạo tiêu đề trang
        $page_title = "Đồng giá " . number_format($target, 0, ',', '.') . "đ";
    } else {
        // Nếu không chọn đồng giá nào → hiển thị tất cả sản phẩm đang giảm giá
        $deal_products = array_filter($all_products, function($sp) {
            return !empty($sp['sale_price']) && $sp['sale_price'] > 0 && $sp['sale_price'] < $sp['Price'];
        });
        $page_title = "Tất cả sản phẩm đang giảm giá";
    }

    // Đưa dữ liệu ra view
    $this->deal_products = $deal_products;
    $this->page_title    = $page_title;
      include 'App/View/shop/single_deal.php';
  }

  public function add_to_cart()
{
    session_start(); // Bắt buộc phải có để lưu session

    // Kiểm tra phương thức POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("Sai phương thức gửi!");
    }

    // Kiểm tra ID sản phẩm
    if (!isset($_POST['id_SP']) || empty($_POST['id_SP'])) {
        die("Thiếu ID sản phẩm!");
    }

    $id = intval($_POST['id_SP']); // sửa val() thành intval
    $size = $_POST['size'] ?? 'M';
    $qty = intval($_POST['qty'] ?? 1);

    // Lấy sản phẩm từ DB
    $sp = $this->sanpham->get_sp_byID($id);
    if (!$sp) {
        die("Không tìm thấy sản phẩm!");
    }

    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra nếu sản phẩm đã tồn tại (cùng id + size) → cộng dồn số lượng
    $found = false;
    foreach($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id && $item['size'] == $size) {
            $item['quantity'] += $qty;
            $found = true;
            break;
        }
    }
    // Nếu chưa có, thêm mới
    if (!$found) {
        $_SESSION['cart'][] = [
            "id"       => $id,
            "image"    => $sp['img'],
            "name"     => $sp['Name'],
            "size"     => $size,
            "price" => ($sp['sale_price'] > 0 && $sp['sale_price'] < $sp['Price']) ? $sp['sale_price'] : $sp['Price'],
            "quantity" => $qty
        ];
    }

    // Redirect về giỏ hàng
    header("Location: index.php?page=giohang");
    exit;
}
public function add_to_wishlist() {
    session_start();
    
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php?page=home");
        exit;
    }
    
    $id = intval($_GET['id']);
    
    // Khởi tạo wishlist nếu chưa có
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }
    
    // Kiểm tra đã có trong wishlist chưa
    $exists = false;
    foreach ($_SESSION['wishlist'] as $item) {
        if ($item['id'] == $id) {
            $exists = true;
            break;
        }
    }
    
    if (!$exists) {
        $_SESSION['wishlist'][] = ['id' => $id];
        
        // Optional: lưu vào DB nếu đã đăng nhập
        // if (isset($_SESSION['user_id'])) { ... }
    }
    
    // Quay lại trang trước hoặc trang chi tiết
    $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=home';
    header("Location: $referer");
    exit;
}

public function removefromwishlist() {
    session_start();
    
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $_SESSION['wishlist'] = array_filter($_SESSION['wishlist'], function($item) use ($id) {
            return $item['id'] != $id;
        });
        // Re-index mảng
        $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
    }
    
    $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=wishlist';
    header("Location: $referer");
    exit;
}

public function wishlist() {
    $this->sanpham = new Product(); // cần khởi tạo để lấy sản phẩm
    include 'App/View/shop/wishlist.php';
}
public function hd_bongda() {
    $sp_bongda = $this->sanpham->get_sp_bongda();
    // $dssp = $sp; // để view product.php dùng chung
    include 'App/View/shop/hd_bongda.php';
}

public function hd_caulong() {
    $sp_caulong = $this->sanpham->get_sp_caulong();
    // $dssp = $sp;
    include 'App/View/shop/hd_caulong.php';
}

public function hd_chaybo() {
    $sp_chaybo = $this->sanpham->get_sp_chaybo();
    // $dssp = $sp;
    include 'App/View/shop/hd_chaybo.php';
}

public function hd_boiloi() {
    $sp_boiloi= $this->sanpham->get_sp_boiloi();
    // $dssp = $sp;
    include 'App/View/shop/hd_boiloi.php';
}

public function hd_gym() {
    $sp_gym = $this->sanpham->get_sp_gym();
    // $dssp = $sp;
    include 'App/View/shop/hd_gym.php';
}

public function hd_macngay() {
    $sp_macngay = $this->sanpham->get_sp_macngay();
    // $dssp = $sp;
    include 'App/View/shop/hd_macngay.php';
}

public function order_history()
{
    // BƯỚC NÀY ĐÃ BỊ LOẠI BỎ: session_start();
    
    // !!! QUAN TRỌNG: 
    // Nếu bạn bỏ qua bước Đăng nhập, bạn cần phải có ID người dùng (user ID)
    // để truy vấn đơn hàng. Tôi sẽ đặt ID người dùng TẠM THỜI là 1 để tránh lỗi SQL.
    // TRONG ỨNG DỤNG THỰC TẾ: BẮT BUỘC PHẢI LẤY ID TỪ SESSION.
    
    // Giả định ID người dùng (Cần phải thay thế bằng logic thực tế)
    $user_id = 1; 

    /* // Logic gốc bị loại bỏ:
    // if (!isset($_SESSION['Username'])) { header("Location: index.php?page=login"); exit; }
    // $user = $this->user->get_user_by_username($_SESSION['Username']);
    // if (!$user) { ... }
    // $user_id = $user['id_User'];
    */

    // Khởi tạo kết nối DB
    require_once 'App/Model/database.php';
    $db = new Database("localhost", "5svcode", "root", "");
    $pdo = $db->connect();

    // 1. Lấy danh sách Đơn hàng của User
    $sql = "SELECT * FROM donhang WHERE id_User = ? ORDER BY ngay_mua DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Lấy Chi tiết cho từng Đơn hàng
    foreach ($orders as &$order) {
        $sql_detail = "SELECT ct.*, sp.Name, sp.img 
                       FROM chitiet_donhang ct 
                       JOIN sanpham sp ON ct.id_SP = sp.id_SP 
                       WHERE ct.id_dh = ?";
        $stmt_detail = $pdo->prepare($sql_detail);
        $stmt_detail->execute([$order['id_dh']]);
        $order['items'] = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Tải View
    include 'App/View/shop/order_history.php';
}

}
?>
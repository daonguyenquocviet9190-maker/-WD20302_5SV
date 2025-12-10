<?php
require 'App/Model/category.php';
require 'App/Model/product.php';
require 'App/Model/user.php';
require 'App/Model/order.php';
  class AdminController {
     public $danhmuc;
  public $sanpham;
  public $user;
  public $order;
  public function __construct()
  {
    $this->danhmuc = new Category();
    $this->sanpham = new Product();
    $this->user = new User();
    $this->order = new Order();
  }
    
    // ==========================================================
    // 🚀 HÀM home() ĐÃ ĐƯỢC CẬP NHẬT CHO PHÂN TRANG (LIMIT 10)
    // ==========================================================
    public function home(){
        // --- 1. Thiết lập các biến Phân trang ---
        $limit = 10; // 10 sản phẩm mỗi trang theo yêu cầu
        
        // ⚠️ BƯỚC QUAN TRỌNG: Lấy tổng số lượng sản phẩm từ Model
        // Bạn phải đảm bảo hàm get_total_sp_count() có trong Product Model
        $total_products = $this->sanpham->get_total_sp_count() ?? 100; // Giả định 100 nếu Model chưa có hàm COUNT

        $total_pages = ceil($total_products / $limit); 
        
        // Lấy số trang hiện tại từ URL (query parameter 'p')
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
        
        // Kiểm tra tính hợp lệ của trang hiện tại
        if ($current_page < 1) $current_page = 1;
        if ($total_pages > 0 && $current_page > $total_pages) $current_page = $total_pages;

        // Tính OFFSET (Vị trí bắt đầu lấy dữ liệu)
        $offset = ($current_page - 1) * $limit;

        // --- 2. Lấy danh sách sản phẩm theo trang (sử dụng LIMIT và OFFSET) ---
        // ⚠️ Bạn phải đảm bảo hàm getall_sp_paged($limit, $offset) có trong Product Model
        // Nếu không có, bạn hãy tạm thời dùng $this->sanpham->getall_sp() 
        // và xử lý cắt mảng trong home.php (như tôi đã làm ở bước 2).
        $dssp = $this->sanpham->getall_sp_paged($limit, $offset) ?? $this->sanpham->getall_sp();

        // Truyền các biến phân trang sang View
        $total_products = $total_products;
        $current_page = $current_page;
        $total_pages = $total_pages;
        
        include 'App/View/admin/home.php';
    }
    // ==========================================================
    
  public function product()
{
    $sizes = $this->sanpham->getall_size();
    $dsdm = $this->danhmuc->getall_dm(); 
    $sp_edit = null;

    // Xóa sản phẩm
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $this->sanpham->remove_sp($_GET['id']);
        header("Location: admin.php?page=product");
        exit;
    }

    // Thêm / Sửa sản phẩm
    if (isset($_POST['save_product'])) {
        $name = $_POST['ten_san_pham'];
        $price = $_POST['gia'];
        $sale_price = $_POST['gia_giam'] ?? $price; // nếu không nhập giá giảm, mặc định = giá gốc
        $quantity = $_POST['so_luong'];
        $cat_id = $_POST['category'];
        $size = $_POST['size'];

        // Xử lý ảnh
        $img = "";
        if (!empty($_FILES['img']['name'])) {
            $img = time() . "_" . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], "App/public/img/" . $img);
        } else {
            $img = $_POST['old_img'] ?? "";
        }

        // UPDATE
        if (!empty($_POST['idedit'])) {
            $this->sanpham->update_sp($_POST['idedit'], $name, $price, $sale_price, $quantity, $cat_id, $size, $img);
        }
        // INSERT
        else {
            $this->sanpham->add_sp($name, $price, $sale_price, $quantity, $cat_id, $size, $img);
        }

        header("Location: admin.php?page=product");
        exit;
    }

    // Edit form
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $sp_edit = $this->sanpham->get_sp_byID($_GET['id']);
        include "App/View/admin/them_product.php";
        return;
    }

    // Add form
    if (isset($_GET['action']) && $_GET['action'] == 'add') {
        include "App/View/admin/them_product.php";
        return;
    }

    // Danh sách sản phẩm
    $dssp = $this->sanpham->getall_sp();
    include "App/View/admin/product.php";
}


 public function category(){
    $dm_edit = null;

    // XÓA DANH MỤC
    if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])){
        $this->danhmuc->remove_dm($_GET['id']);
        header('Location: admin.php?page=category');
        exit;
    }

    // XỬ LÝ FORM THÊM/SỬA
    if(isset($_POST['save_category'])){
        $name = trim($_POST['cat_name']);
        if(!empty($name)){
            if(isset($_POST['idedit']) && $_POST['idedit'] != null){
                $this->danhmuc->update_dm($_POST['idedit'], $name);
            } else {
                $this->danhmuc->add_dm($name);
            }
            header('Location: admin.php?page=category');
            exit;
        }
    }

    // NẾU action=add hoặc action=edit → hiển thị form
    if(isset($_GET['action']) && $_GET['action'] == 'add'){
        include 'App/View/admin/them_loaisanpham.php';
        return; // dừng controller
    }

    if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['idedit'])){
        $dm_edit = $this->danhmuc->get_dm_byID($_GET['idedit']);
        include 'App/View/admin/them_loaisanpham.php';
        return; // dừng controller
    }

    // MẶC ĐỊNH → danh sách
    $dsdm = $this->danhmuc->getall_dm();
    include 'App/View/admin/category.php';
}

       public function user(){
//         if (isset($_POST['add_user']) && ($_POST['add_user'])) {
//        if($_POST['n_user'] != null) {
//       $name = $_POST['n_user'];
//       $pass = $_POST['pass'];
//       if(isset($_GET['idedit']) && $_GET['idedit'] != null){
//        $this->user->update_user($_GET['idedit'],$name, $pass);
//        header('location:admin.php?page=user');
//       } else {
//       $this->user->add_user($name, $pass);
//        header('location:admin.php?page=user');
//       }
//   }
//     }
//     // $dsuser = $this->user->getall_user();
// if(isset($_GET['idedit'])){
//       $user_edit = $this->user->get_user_byID($_GET['idedit']);
//       // print_r($user_edit);
//     }
//     if (isset($_GET['id'])) {
//       $this->user->remove_user($_GET['id']);
//       header('location:admin.php?page=user');
//     }
    $user = $this->user->getall_user();
        include 'App/View/admin/user.php';
    }
        public function update_product(){
        include 'App/View/admin/update_product.php';
    }
    public function them_loaisanpham()  {
      include 'App/View/admin/them_loaisanpham.php';
  } 

    // FILE: App/Controllers/AdminController.php (Sửa lại hàm order)

public function order() {
    $action = $_GET['action'] ?? 'list';
    $id = $_GET['id'] ?? null;

    // ... (logic xóa)
    
    /* ================== 2. XỬ LÝ HÀNH ĐỘNG CHI TIẾT ================== */
    if ($action == 'detail' && $id != null) {
        // Lấy thông tin đơn hàng
        $order_detail = $this->order->get_order_by_id($id);
        // ... (Bạn có thể thêm $items ở đây)

        // ✅ SỬA LỖI: Include giao diện chi tiết (tạo file order_detail.php nếu chưa có)
        include "App/View/admin/order_detail.php"; 
        return; // Dừng Controller
    }

    /* ================== 3. MẶC ĐỊNH → HIỂN THỊ DANH SÁCH ================== */
    $orders = $this->order->get_all_orders(); 
    
    // ✅ ĐÃ ĐÚNG: Include giao diện danh sách
    include "App/View/admin/order.php"; 
}
  }
?>
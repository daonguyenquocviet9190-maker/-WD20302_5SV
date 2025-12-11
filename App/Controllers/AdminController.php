<?php
require 'App/Model/database.php'; // <--- Bổ sung dòng này
require 'App/Model/category.php';
require 'App/Model/product.php';
require 'App/Model/user.php';
require 'App/Model/order.php';
require 'App/Model/voucher.php';

  class AdminController {
     public $danhmuc;
  public $sanpham;
  public $user;
  public $order;
  public $voucher;
  public function __construct()
  {
    $this->danhmuc = new Category();
    $this->sanpham = new Product();
    $this->user = new User();
    $this->order = new Order();
    $this->voucher = new Voucher();
  }
     
    // ==========================================================
    // 🚀 HÀM home() ĐÃ ĐƯỢC CẬP NHẬT CHO PHÂN TRANG (LIMIT 10)
    // ==========================================================
   public function home(){
    
    // ⚠️ BẬT HIỂN THỊ LỖI (Nên thêm vào đầu Controller để kiểm tra)
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);

    // --- 1. Thiết lập các biến chung và Tìm kiếm ---
    $limit = 10; // 10 sản phẩm mỗi trang
    $search_term = $_GET['search'] ?? null; // Lấy từ khóa tìm kiếm từ URL
    $where_clause = ""; // Điều kiện WHERE cho SQL
    $params = []; // Tham số cho Prepared Statement trong Model

    // --- 2. XỬ LÝ LOGIC TÌM KIẾM VÀ PHÂN TRANG BAN ĐẦU ---
    if (!empty($search_term)) {
        // A. CÓ TÌM KIẾM
        $search_key = trim($search_term);
        
        // Định nghĩa điều kiện WHERE cho Model (sử dụng placeholder '?')
        $where_clause = " WHERE LOWER(Name) LIKE ?";
        $params[] = '%' . strtolower($search_key) . '%'; 
        
        // Khi có tìm kiếm, luôn bắt đầu từ trang 1
        $current_page = 1;
        
    } else {
        // B. KHÔNG CÓ TÌM KIẾM (Chỉ phân trang)
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
    }
    
    // --- 3. Lấy tổng số sản phẩm (dựa trên điều kiện tìm kiếm hoặc không) ---
    // ⚠️ BẠN PHẢI SỬA HÀM get_total_sp_count() trong Model để nhận 2 tham số $where_clause và $params
    $total_products = $this->sanpham->get_total_sp_count($where_clause, $params) ?? 0;

    $total_pages = ceil($total_products / $limit); 
    
    // Kiểm tra tính hợp lệ của trang hiện tại
    if ($current_page < 1) $current_page = 1;
    if ($total_pages > 0 && $current_page > $total_pages) $current_page = $total_pages;

    // Tính OFFSET (Vị trí bắt đầu lấy dữ liệu)
    $offset = ($current_page - 1) * $limit;
    
    // --- 4. Lấy danh sách sản phẩm theo trang và điều kiện tìm kiếm ---
    // ⚠️ BẠN PHẢI SỬA HÀM getall_sp_paged() trong Model để nhận 4 tham số
    $dssp = $this->sanpham->getall_sp_paged($limit, $offset, $where_clause, $params) ?? [];

    
    // ==========================================================
    // 5. LOGIC THỐNG KÊ (Giữ nguyên)
    // ==========================================================
    $order_count = $this->order->get_order_count() ?? 0;
    $new_orders = $this->order->get_new_order_count() ?? 0;
    $new_customers = $this->user->get_new_customer_count() ?? 0;
    $revenue_today = 5000000; 
    $return_rate = 2.5; 
    
    // --- 6. Truyền các biến sang View ---
    
    $dssp = $dssp;
    $search_term = $search_term; // QUAN TRỌNG: Truyền biến tìm kiếm sang View
    $current_page = $current_page;
    $total_pages = $total_pages;

    // Truyền biến thống kê sang View
    $revenue_today = $revenue_today;
    $order_count = $order_count;
    $new_orders = $new_orders;
    $new_customers = $new_customers;
    $return_rate = $return_rate;
    
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
// DÁN TOÀN BỘ 3 HÀM NÀY VÀO CUỐI AdminController.php

    // === QUẢN LÝ VOUCHER (CRUD) ===

    /**
     * R - Read: Hiển thị danh sách Voucher
     */
    public function vouchers()
{
    // Lấy tất cả voucher từ Voucher Model
    // Phương thức get_all_vouchers() có sẵn trong Model Voucher.php
    $ds_vouchers = $this->voucher->get_all_vouchers();

    // Load View để hiển thị danh sách
    // File view này sẽ dùng biến $ds_vouchers
    include 'App/View/admin/vouchers_list.php';
}

    /**
     * C-U - Create/Update: Thêm mới hoặc Chỉnh sửa Voucher
     */
    public function voucher_form()
    {
        $id = intval($_GET['id'] ?? 0);
        $data = null; 
        
        // 1. Xử lý POST (Thêm/Sửa)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
            
            // Lấy dữ liệu từ POST
            $code = trim($_POST['code']);
            $discount_type = $_POST['discount_type'];
            $discount_value = floatval($_POST['discount_value']);
            $max_discount_amount = floatval($_POST['max_discount_amount'] ?? 0);
            $min_order_amount = floatval($_POST['min_order_amount'] ?? 0);
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $usage_limit = intval($_POST['usage_limit'] ?? 0);
            $user_limit = intval($_POST['user_limit'] ?? 0);
            $product_ids = trim($_POST['product_ids'] ?? '');
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $result = false;
            
            if ($_POST['action_type'] === 'add') {
                $result = $this->voucher->add_voucher(
                    $code, $discount_type, $discount_value, $max_discount_amount, 
                    $min_order_amount, $start_date, $end_date, $usage_limit, 
                    $user_limit, $product_ids, $is_active
                );
            } elseif ($_POST['action_type'] === 'edit' && $id) {
                $result = $this->voucher->update_voucher(
                    $id, $code, $discount_type, $discount_value, $max_discount_amount, 
                    $min_order_amount, $start_date, $end_date, $usage_limit, 
                    $user_limit, $product_ids, $is_active
                );
            }

            if ($result) {
                // Đặt thông báo thành công và chuyển hướng về trang danh sách
                $_SESSION['message'] = "Cập nhật Voucher thành công!";
                header('Location: admin.php?page=vouchers');
                exit;
            } else {
                // Đặt thông báo lỗi, giữ lại dữ liệu form
                $data = $_POST; 
                $_SESSION['error'] = "Cập nhật Voucher thất bại. Vui lòng kiểm tra lại dữ liệu.";
            }
        }
        
        // 2. Lấy dữ liệu cho Form Edit
        if (!$data && $id > 0) {
             // Lấy voucher từ Model (get_voucher_by_id cần được định nghĩa trong voucher.php)
            $data = $this->voucher->get_voucher_by_id($id); 
            if (!$data) {
                $_SESSION['error'] = "Voucher không tồn tại.";
                header('Location: admin.php?page=vouchers');
                exit;
            }
        }
        
        // 3. Tải View (Giả sử file view là 'app/View/admin/voucher_form.php')
        // Biến $data sẽ được truyền vào view voucher_form.php
        include 'app/View/admin/voucher_form.php';
    }
    
    /**
     * D - Delete: Xóa Voucher
     */
    public function delete_voucher()
    {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $this->voucher->delete_voucher($id);

            if ($result) {
                $_SESSION['message'] = "Xóa Voucher thành công!";
            } else {
                 $_SESSION['error'] = "Xóa Voucher thất bại hoặc Voucher không tồn tại.";
            }
        }
        
        // Chuyển hướng về trang danh sách Voucher
        header('Location: admin.php?page=vouchers');
        exit;
    }
  }
?>
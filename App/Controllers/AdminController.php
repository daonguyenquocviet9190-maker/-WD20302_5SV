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
    public function home(){
      $dssp = $this->sanpham->getall_sp();  
    // $dsuser = $this->user->getall_user();
    include 'App/View/admin/home.php';
  }
  public function product()
{
    $dsdm = $this->danhmuc->getall_dm(); 
    $sp_edit = null;
    /* ================== 1. XÓA SẢN PHẨM ================== */
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $this->sanpham->remove_sp($_GET['id']);
        header("Location: admin.php?page=product");
        exit;
    }
    /* ================== 2. LƯU THÊM / SỬA ================== */
    if (isset($_POST['save_product'])) {
        $name = $_POST['ten_san_pham'];
        $price = $_POST['gia'];
        $quantity = $_POST['so_luong'];
        $cat_id = $_POST['category'];
        // Xử lý ảnh
        $img = "";
        if (!empty($_FILES['img']['name'])) {
            $img = time() . "_" . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], "App/public/img/" . $img);
        } else {
            $img = $_POST['old_img']; // sửa mà không đổi ảnh
        }
        // UPDATE
        if (!empty($_POST['idedit'])) {
            $this->sanpham->update_sp($_POST['idedit'], $name, $price, $quantity, $cat_id, $img);
        }
        // INSERT
        else {
            $this->sanpham->add_sp($name, $price, $quantity, $cat_id, $img);
        }
        header("Location: admin.php?page=product");
        exit;
    }
    /* ================== 3. NHẤN SỬA → HIỂN THỊ FORM ================== */
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $sp_edit = $this->sanpham->get_sp_byID($_GET['id']);
        $dm = $this->danhmuc->getall_dm();
        include "App/View/admin/them_product.php";
        return;
    }
        /* ================== 4. NHẤN THÊM → HIỂN THỊ FORM ================== */
    if (isset($_GET['action']) && $_GET['action'] == 'add') {
        include "App/View/admin/them_product.php";
        return;
    }
    /* ================== 5. MẶC ĐỊNH → DANH SÁCH ================== */
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
        if (isset($_POST['add_user']) && ($_POST['add_user'])) {
       if($_POST['n_user'] != null) {
      $name = $_POST['n_user'];
      $pass = $_POST['pass'];
      if(isset($_GET['idedit']) && $_GET['idedit'] != null){
       $this->user->update_user($_GET['idedit'],$name, $pass);
       header('location:admin.php?page=user');
      } else {
      $this->user->add_user($name, $pass);
       header('location:admin.php?page=user');
      }
  }
    }
    // $dsuser = $this->user->getall_user();
if(isset($_GET['idedit'])){
      $user_edit = $this->user->get_user_byID($_GET['idedit']);
      // print_r($user_edit);
    }
    if (isset($_GET['id'])) {
      $this->user->remove_user($_GET['id']);
      header('location:admin.php?page=user');
    }
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
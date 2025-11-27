<?php
require 'App/Model/category.php';
require 'App/Model/product.php';
require 'App/Model/user.php';
  class AdminController {
     public $danhmuc;
  public $sanpham;
  public $user;
  public function __construct()
  {
    $this->danhmuc = new Category();
    $this->sanpham = new Product();
    $this->user = new User();
  }
    public function home(){
    //   $dssp = $this->sanpham->getall_sp();  
    // $dsuser = $this->user->getall_user();
    include 'App/View/admin/home.php';
  }
  public function product(){
    // if (isset($_POST['add_sp']) && ($_POST['add_sp'])) {
    //   if($_POST['n_pro'] != null) {
    //   $name = $_POST['n_pro'];
    //   $price = $_POST['price'];
    //   $quantity = $_POST['quantity'];
    //   $cat_id = $_POST['cat_id'];
    //   $img = $_POST['product_image'];
    //   if(isset($_GET['idedit']) && $_GET['idedit'] != null){
    //     $this->sanpham->update_sp($_GET['idedit'],$name, $price, $quantity, $cat_id, $img);
    //     header('location:admin.php?page=product');
    //   } else {
    //   $this->sanpham->add_sp($name, $price, $quantity, $cat_id, $img);
    //    header('location:admin.php?page=product');
    //   }
    // }
    // }
    $dssp = $this->sanpham->getall_sp();
    //  if(isset($_GET['idedit'])){
    //   $sp_edit = $this->sanpham->get_sp_byID($_GET['idedit']);
    //   print_r($sp_edit);
    // }
    // if (isset($_GET['id'])) {
    //   $this->sanpham->remove_sp($_GET['id']);
    //   header('location:admin.php?page=product');
    // }
    if (isset($_GET['action']) && $_GET['action'] == 'add') {
        // Tên file của bạn là them_product.php, nên sẽ include nó
        include 'App/View/admin/them_product.php';
        return; // Dừng lại, không chạy code bên dưới
    }
        include 'App/View/admin/product.php';
    }
     public function category(){
   $dm_edit = null; // dùng cho form sửa

        // XÓA DANH MỤC
        if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
            $this->danhmuc->remove_dm($_GET['id']);
            header("Location: admin.php?page=category");
            exit;
        }

        // NHẤN THÊM → HIỂN THỊ FORM
        if (isset($_GET['action']) && $_GET['action'] == 'add') {
            include 'App/View/admin/them_loaisanpham.php';
            return;
        }

        // NHẤN SỬA → HIỂN THỊ FORM EDIT
        if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $dm_edit = $this->danhmuc->get_dm_byID($_GET['id']);
            include 'App/View/admin/them_loaisanpham.php';
            return;
        }

        // LƯU FORM (THÊM + SỬA)
        if (isset($_POST['save_category'])) {
            $name = trim($_POST['cat_name']);

            if ($name != "") {
                // Sửa
                if (!empty($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
                    $this->danhmuc->update_dm($_GET['id'], $name);
                }
                // Thêm
                else {
                    $this->danhmuc->add_dm($name);
                }
            }

            header("Location: admin.php?page=category");
            exit;
        }

        // MẶC ĐỊNH → HIỂN THỊ DANH SÁCH
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
    $dsuser = $this->user->getall_user();
if(isset($_GET['idedit'])){
      $user_edit = $this->user->get_user_byID($_GET['idedit']);
      // print_r($user_edit);
    }
    if (isset($_GET['id'])) {
      $this->user->remove_user($_GET['id']);
      header('location:admin.php?page=user');
    }
        include 'App/View/admin/user.php';
    }
        public function update_product(){
        include 'App/View/admin/update_product.php';
    }
  }
?>
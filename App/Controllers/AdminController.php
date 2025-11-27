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
      $dssp = $this->sanpham->getall_sp();  
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
      // thêm danh mục mới
    // if (isset($_POST['add_cat']) && ($_POST['add_cat'])) {
    //   if($_POST['cat_name'] != null){
    //    $name = $_POST['cat_name'];
    //    $description = $_POST['cat_des'];
    //    if(isset($_GET['idedit']) && $_GET['idedit'] != null){
    //     $this->danhmuc->update_dm($_GET['idedit'],$name, $description);
    //     header('location:admin.php?page=category');
    //    } else{
    //    $this->danhmuc->add_dm($name, $description);
    //    header('location:admin.php?page=category');
    //    }
    //   }
    // }
    $dsdm = $this->danhmuc->getall_dm();
    // if(isset($_GET['idedit'])){
    //   $dm_edit = $this->danhmuc->get_dm_byID($_GET['idedit']);
    //   // print_r($dm_edit);
    // }
    // // print_r($dsdm);

    // // xóa danh mục
    // if (isset($_GET['id'])) {
    //   $this->danhmuc->remove_dm($_GET['id']);
    //   header('location:admin.php?page=category');
    // }
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
    public function order() {
        include "App/View/admin/order.php"; 
    }
  }
  
?>
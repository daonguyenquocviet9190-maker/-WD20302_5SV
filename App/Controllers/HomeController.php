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
    $deal111k = $this->sanpham->get_deal_111k();
    include 'App/View/shop/giohang.php';
  }
  public function bosuutap()
  {
    include 'App/View/shop/bosuutap.php';
  }
  public function order()
  {
    include 'App/View/shop/order.php';
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

}
?>
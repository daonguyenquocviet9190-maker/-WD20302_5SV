<?php
include "App/Controllers/HomeController.php";
 $controller = new HomeController();
 include 'App/View/shop/header.php';

  if(!isset($_GET['page'])){
   header('location:index.php?page=home');
  } else {
    $page = $_GET['page'];
    $controller -> $page();
  }
 include 'App/View/shop/footer.php';
?>
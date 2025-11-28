<?php
include "App/Controllers/AdminController.php";
    $controller = new AdminController();
include "App/View/admin/header.php";

if (!isset($_GET['page'])){
       header('location:admin.php?page=home');
    }else{
        $page = $_GET['page'];
        $controller->$page();
    }

include "App/View/admin/footer.php";
?>
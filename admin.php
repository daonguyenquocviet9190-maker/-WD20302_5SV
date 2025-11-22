<?php
include "App/Controllers/AdminController.php";
    $controller = new AdminController();
include "App/View/admin/header.php";

if (!isset($_GET['page'])){
        include "App/View/admin/home.php";
    }else{
        $page = $_GET['page'];
        $controller->$page();
    }

// include "App/View/admin/footer.php";
?>
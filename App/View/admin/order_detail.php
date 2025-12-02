<?php 
// FILE: App/View/admin/order_detail.php

// *** BỔ SUNG: Kiểm tra và khởi tạo $items nếu chưa được định nghĩa (Fix Warning) ***
if (!isset($items) || !is_array($items)) {
    $items = [];
}
// *** END BỔ SUNG ***


// Hàm định dạng tiền tệ
function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

// Tính tổng tiền dựa trên chi tiết sản phẩm
$subtotal = 0;
foreach ($items as $item) {
    // Đảm bảo các khóa tồn tại trước khi truy cập
    $quantity = $item['so_luong'] ?? 0;
    $price = $item['don_gia'] ?? 0;
    $subtotal += $quantity * $price;
}

// Giả sử $order_detail['id_dh'] là mã đơn hàng
$order_id = $order_detail['id_dh'] ?? 'N/A';
$order_status = $order_detail['trang_thai'] ?? 'Chờ xử lý';
?>

<style>
/* Đặt font chữ cơ bản và reset nhẹ */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7f9;
    color: #333;
}

/* --- PHẦN CSS CĂN CHỈNH QUAN TRỌNG --- */
.main-content.order-detail {
    /* Đẩy nội dung xuống và sang phải để tránh Header và Sidebar */
    margin-top: 20px;  /* Bù trừ cho Header/Navbar (ước tính 60px) */
    margin-left: 250px; /* Bù trừ cho Sidebar (ước tính 250px) */
    
    padding: 20px 30px; /* Giảm padding trên/dưới một chút */
    
    /* Thiết lập bố cục */
    width: auto; 
    margin-right: 20px; /* Thêm khoảng cách bên phải để đối xứng */
    min-height: calc(100vh - 60px); /* Đảm bảo nội dung cao ít nhất bằng phần còn lại của viewport */
    
    background-color: #f4f7f9; /* Đặt nền trùng với body nếu Admin Panel có nền chung */
    /* Loại bỏ box-shadow và border-radius trên khối chính để nó hòa vào giao diện */
    box-shadow: none; 
    border-radius: 0;
}
/* --- KẾT THÚC PHẦN CSS CĂN CHỈNH QUAN TRỌNG --- */


/* Header Nội dung */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #eee;
    padding-bottom: 15px;
    margin-bottom: 25px;
}

.content-header h2 {
    color: #343a40; /* Đặt màu chữ tối hơn cho tiêu đề */
    margin: 0;
    font-size: 1.8em;
}

.btn {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s;
}

/* Nút Quay lại - Giống trong ảnh */
.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Info Blocks - Bọc thông tin Khách hàng và Đơn hàng */
.row {
    display: flex;
    margin-bottom: 20px;
    gap: 20px;
}

.col-md-6 {
    flex: 1;
    padding: 20px;
    background-color: #fff; /* Nền trắng cho các khối thông tin */
    border: 1px solid #dee2e6;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05); /* Thêm bóng nhẹ */
}

.col-md-6 h3 {
    margin-top: 0;
    color: #343a40;
    font-size: 1.25em;
    border-bottom: 1px solid #f0f0f0; /* Thay đổi dashed thành solid nhẹ */
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.table-info {
    width: 100%;
    border-collapse: collapse;
}

.table-info th, .table-info td {
    padding: 6px 0;
    text-align: left;
    vertical-align: top;
    font-size: 0.95em;
}

.table-info th {
    width: 30%; /* Điều chỉnh chiều rộng tiêu đề */
    font-weight: 600;
    color: #495057;
}

/* Product Table */
h3 {
    margin-top: 30px;
    margin-bottom: 15px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.data-table th, .data-table td {
    border: 1px solid #dee2e6;
    padding: 12px;
    text-align: left;
}

.data-table th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.9em;
}

/* ... (Các style khác giữ nguyên như Trạng thái, Tfoot, v.v.) ... */
.data-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.data-table tfoot td {
    background-color: #e9ecef;
}

.data-table tfoot tr:last-child td {
    color: #dc3545;
    font-size: 1.1em;
}

.order-status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    font-size: 0.85em;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.status-chờ-xử-lý {
    background-color: #ffc107;
    color: #333;
}

</style>
<div class="main-content order-detail">
    <div class="content-header">
        <h2>Chi tiết Đơn hàng #<?= $order_id ?></h2>
        <a href="admin.php?page=order" class="btn btn-secondary">← Quay lại danh sách</a>
    </div>
    
    <div class="row info-blocks">
        <div class="col-md-6">
            <h3>Thông tin Khách hàng</h3>
            <table class="table-info">
                <tr><th>Họ và tên:</th><td><?= $order_detail['customer_name'] ?? 'N/A' ?></td></tr>
                <tr><th>Email:</th><td><?= $order_detail['email'] ?? 'N/A' ?></td></tr>
                <tr><th>Điện thoại:</th><td><?= $order_detail['phone'] ?? 'N/A' ?></td></tr>
                <tr><th>Địa chỉ Giao hàng:</th><td><?= $order_detail['address'] ?? 'Chưa cập nhật' ?></td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <h3>Thông tin Đơn hàng</h3>
            <table class="table-info">
                <tr><th>Mã Đơn hàng:</th><td>#<?= $order_id ?></td></tr>
                <tr><th>Ngày đặt:</th><td><?= $order_detail['ngay_mua'] ?? 'N/A' ?></td></tr>
                <tr><th>Trạng thái:</th><td><span class="order-status status-<?= str_replace(' ', '-', strtolower($order_status)) ?>"><?= $order_status ?></span></td></tr>
                <tr><th>Voucher áp dụng:</th><td><?= $order_detail['id_voucher'] ?? 'Không' ?></td></tr>
            </table>
        </div>
    </div>
    
    <hr style="border: 0; border-top: 1px solid #ccc; margin: 25px 0;">
    
    <h3>Sản phẩm đã đặt</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th style="width: 10%;">Số lượng</th>
                <th style="width: 15%;">Đơn giá</th>
                <th style="width: 20%;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['ten_san_pham'] ?? 'Sản phẩm không tên' ?></td>
                        <td><?= $item['so_luong'] ?? 0 ?></td>
                        <td><?= format_currency($item['don_gia'] ?? 0) ?></td>
                        <td><?= format_currency(($item['so_luong'] ?? 0) * ($item['don_gia'] ?? 0)) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Không có sản phẩm nào trong đơn hàng này.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">Tạm tính:</td>
                <td style="font-weight: bold;"><?= format_currency($subtotal) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">Tổng tiền (Cuối cùng):</td>
                <td style="font-weight: bold;"><?= format_currency($order_detail['tong_tien'] ?? $subtotal) ?></td>
            </tr>
        </tfoot>
    </table>

</div>
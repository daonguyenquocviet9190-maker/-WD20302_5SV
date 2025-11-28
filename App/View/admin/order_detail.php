<?php 
// FILE: App/View/admin/order_detail.php

// Hàm định dạng tiền tệ
function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

// Tính tổng tiền dựa trên chi tiết sản phẩm
$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['so_luong'] * $item['don_gia'];
}

// Giả sử $order_detail['id_dh'] là mã đơn hàng
$order_id = $order_detail['id_dh'];
?>

<div class="main-content order-detail">
    <div class="content-header">
        <h2>Chi tiết Đơn hàng #<?= $order_id ?></h2>
        <a href="admin.php?page=order" class="btn btn-secondary">← Quay lại danh sách</a>
    </div>

    <div class="row info-blocks">
        <div class="col-md-6">
            <h3>Thông tin Khách hàng</h3>
            <table class="table-info">
                <tr><th>Họ và tên:</th><td><?= $order_detail['customer_name'] ?></td></tr>
                <tr><th>Email:</th><td><?= $order_detail['email'] ?></td></tr>
                <tr><th>Điện thoại:</th><td><?= $order_detail['phone'] ?></td></tr>
                <tr><th>Địa chỉ Giao hàng:</th><td><?= $order_detail['address'] ?? 'Chưa cập nhật' ?></td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <h3>Thông tin Đơn hàng</h3>
            <table class="table-info">
                <tr><th>Mã Đơn hàng:</th><td>#<?= $order_id ?></td></tr>
                <tr><th>Ngày đặt:</th><td><?= $order_detail['ngay_mua'] ?></td></tr>
                <tr><th>Trạng thái:</th><td><span class="order-status status-<?= str_replace(' ', '-', strtolower($order_detail['trang_thai'] ?? 'pending')) ?>"><?= $order_detail['trang_thai'] ?? 'Chờ xử lý' ?></span></td></tr>
                <tr><th>Voucher áp dụng:</th><td><?= $order_detail['id_voucher'] ?? 'Không' ?></td></tr>
            </table>
        </div>
    </div>
    
    <h3>Sản phẩm đã đặt</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['ten_san_pham'] ?></td>
                        <td><?= $item['so_luong'] ?></td>
                        <td><?= format_currency($item['don_gia']) ?></td>
                        <td><?= format_currency($item['so_luong'] * $item['don_gia']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Không có sản phẩm nào trong đơn hàng này.</td>
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
                <td style="font-weight: bold; color: red;"><?= format_currency($order_detail['tong_tien'] ?? $subtotal) ?></td>
            </tr>
        </tfoot>
    </table>

</div>
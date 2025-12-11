<h2>Quản Lý Mã Giảm Giá (Voucher)</h2>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<a href="admin.php?page=voucher_form" class="btn btn-primary">Thêm Voucher Mới</a>

<?php 
// Biến $ds_vouchers được lấy từ AdminController::vouchers()
// Kiểm tra nếu không có voucher nào
if (empty($ds_vouchers)): 
?>
    <div class="alert alert-info mt-3">
        Chưa có mã giảm giá (Voucher) nào được tạo.
    </div>
<?php else: ?>

<table class="table table-bordered table-striped mt-3">
    <thead>
        <tr class="bg-primary text-white"> <th>ID</th>
            <th>Mã Code</th>
            <th>Loại</th>
            <th>Giá Trị</th>
            <th>Đơn tối thiểu</th>
            <th>Hạn sử dụng</th>
            <th>Đã dùng</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ds_vouchers as $voucher): ?>
        <tr>
            <td><?= $voucher['is_active'] == 1 ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-danger">Khóa</span>' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php
// FILE: app/View/admin/voucher_form.php (HOÀN THIỆN)
// Lấy dữ liệu hoặc đặt giá trị mặc định cho form
// $data được truyền từ AdminController::voucher_form()
$voucher = $data ?? [];
$is_edit = isset($voucher['id']) && $voucher['id'] > 0;
$title = $is_edit ? 'Chỉnh Sửa Voucher: ' . ($voucher['code'] ?? '') : 'Thêm Voucher Mới';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h2 class="h5 m-0 font-weight-bold text-primary"><?= $title ?></h2>
    </div>
    <div class="card-body">
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="admin.php?page=voucher_form<?= $is_edit ? '&id=' . ($voucher['id'] ?? '') : '' ?>">
            
            <input type="hidden" name="action_type" value="<?= $is_edit ? 'edit' : 'add' ?>">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?= $voucher['id'] ?? '' ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Mã Code (*)</label>
                <input type="text" name="code" value="<?= $voucher['code'] ?? '' ?>" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Loại Giảm Giá (*)</label>
                    <select name="discount_type" class="form-control" required>
                        <option value="fixed" <?= ($voucher['discount_type'] ?? '') == 'fixed' ? 'selected' : '' ?>>Giá trị cố định (VND)</option>
                        <option value="percent" <?= ($voucher['discount_type'] ?? '') == 'percent' ? 'selected' : '' ?>>Phần trăm (%)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Giá Trị Giảm (*)</label>
                    <input type="number" name="discount_value" value="<?= $voucher['discount_value'] ?? '' ?>" class="form-control" required min="1">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Giảm Tối Đa (Áp dụng cho loại %)</label>
                    <input type="number" name="max_discount_amount" value="<?= $voucher['max_discount_amount'] ?? 0 ?>" class="form-control" min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Đơn Hàng Tối Thiểu Áp Dụng (*)</label>
                    <input type="number" name="min_order_amount" value="<?= $voucher['min_order_amount'] ?? 0 ?>" class="form-control" required min="0">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày Bắt Đầu (*)</label>
                    <input type="date" name="start_date" 
                           value="<?= date('Y-m-d', strtotime($voucher['start_date'] ?? date('Y-m-d'))) ?>" 
                           class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày Kết Thúc (*)</label>
                    <input type="date" name="end_date" 
                           value="<?= date('Y-m-d', strtotime($voucher['end_date'] ?? date('Y-m-d', strtotime('+30 days')))) ?>" 
                           class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tổng Lượt Sử Dụng (0 = Vô hạn)</label>
                    <input type="number" name="usage_limit" value="<?= $voucher['usage_limit'] ?? 0 ?>" class="form-control" min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Giới Hạn Mỗi Người Dùng (0 = Vô hạn)</label>
                    <input type="number" name="user_limit" value="<?= $voucher['user_limit'] ?? 0 ?>" class="form-control" min="0">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">ID Sản Phẩm Áp Dụng (Để trống nếu áp dụng toàn bộ)</label>
                <input type="text" name="product_ids" value="<?= $voucher['product_ids'] ?? '' ?>" class="form-control" placeholder="Ví dụ: 1,2,5">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" 
                       <?= ($voucher['is_active'] ?? 1) == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Hoạt động (Kích hoạt Voucher này)</label>
            </div>

            <button type="submit" class="btn btn-primary mt-3"><?= $is_edit ? 'Lưu Thay Đổi' : 'Thêm Voucher' ?></button>
            <a href="admin.php?page=vouchers" class="btn btn-secondary mt-3">Quay Lại</a>
        </form>
    </div>
</div>
<style>
    /* =========================================================================
   ADMIN DASHBOARD CSS - CẦN THIẾT CHO LAYOUT VÀ FORM
   (Tương tự như giao diện Bootstrap/SB Admin 2)
   ========================================================================= */

/* 1. THIẾT LẬP CHUNG & TYPOGRAPHY */
body {
    font-family: 'Arial', sans-serif;

    margin: 50px;
    /* padding: 50px 40px; */
    background-color: #f4f7f9; 
    color: #333;
}

#wrapper {
    display: flex;
    width: 100%;
}

/* Sidebar bên trái (Dashboard, Sản phẩm, Khách hàng...) */
.sidebar {
    width: 300px; /* Chiều rộng cố định của sidebar */
    flex-shrink: 0; /* Ngăn sidebar bị co lại */
    background-color: #4e73df; /* Màu nền sidebar */
    color: white;
    /* Giả định style cơ bản cho sidebar */
}

/* Vùng nội dung chính (phần chứa form voucher) */
.content-wrapper {
    flex-grow: 1; /* Cho phép vùng nội dung chính chiếm hết không gian còn lại */
    padding: 20px; /* Thêm padding cho nội dung chính */
    background-color: #f4f7f9;
}

/* Cập nhật phần body hiện tại */
body {
    font-family: 'Arial', sans-serif;
    /* Xóa margin và padding đã có, thay thế bằng: */
    /* background-color: #f4f7f9;  */
    color: #333;
    /* Đã thêm display: flex, margin: 0, padding: 0 ở trên */
}

/* Điều chỉnh vị trí của .card để nó không bị ép sát vào lề nếu không có wrapper */
/* 2. CARD (Khung chứa nội dung chính) */
/* 2. CARD (Khung chứa nội dung chính) */
.card {
    background-color: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem; 
    margin-bottom: 1.5rem; 
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); 
    
    /* === ĐIỀU CHỈNH LÀM GỌN VÀ CĂN GIỮA === */
    max-width: 990px; /* Giới hạn chiều rộng tối đa */
    margin: 1.5rem auto; /* Căn giữa và giữ khoảng cách trên dưới */
    /* ======================================= */
}
/* Thay đổi padding cho card body để có thêm không gian trống */
.card-body {
    padding: 1.75rem 2rem; /* Tăng padding để có thêm khoảng trống, làm form thoáng hơn */
}ding: 2rem; /* Tăng padding để tạo khoảng trống lớn hơn */
   
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 20px; /* Padding cho nội dung chính */
    background-color: #f4f7f9; /* Nền nhẹ nhàng */
    color: #333;
}

a {
    text-decoration: none;
    color: inherit;
}

.h5 {
    font-size: 1.25rem;
    font-weight: 500;
}

.m-0 { margin: 0 !important; }

.font-weight-bold {
    font-weight: 700 !important;
}

.text-primary {
    color: #4e73df !important; /* Màu xanh admin chủ đạo */
}

/* 2. CARD (Khung chứa nội dung chính) */
.card {
    background-color: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem; /* Bo góc */
    margin-bottom: 1.5rem; /* mb-4 */
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); /* shadow */
}

.card-header {
    padding: 1rem 1.25rem; /* Tăng padding trên dưới lên 1rem (khoảng 16px) */
    margin-bottom: 0;
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    display: flex;
    align-items: center;
}

.card-body {
    padding: 1.25rem;
}

/* 3. GRID & KHOẢNG CÁCH */
.row {
    /* --bs-gutter-x: 1.5rem; */ /* Không cần định nghĩa biến nếu không dùng SCSS */
    --bs-gutter-y: 0;
    display: flex;
    flex-wrap: wrap;
    margin-right: -0.75rem; /* Bù trừ cho padding-right của col */
    margin-left: -0.75rem; /* Bù trừ cho padding-left của col */
}

.col-md-6 {
    flex: 0 0 auto;
    width: 50%;
    padding-right: 0.75rem;
    padding-left: 0.75rem;
}

.mb-3 {
    margin-bottom: 1rem !important;
}

/* 4. FORM ELEMENTS (Input, Select, Label) */
.form-label {
    margin-bottom: 0.5rem;
    font-weight: 600; /* Làm nhãn nổi bật */
    color: #3a3b45;
}

.form-control, .form-select {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #6e707e;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    color: #6e707e;
    background-color: #fff;
    border-color: #bac8f3;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25); /* Shadow tập trung vào màu primary */
}

/* 5. BUTTONS */
.btn {
    display: inline-block;
    font-weight: 400;
    line-height: 1.5;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    border-radius: 0.35rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

/* Button Primary (Lưu, Thêm) */
.btn-primary {
    color: #fff;
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    color: #fff;
    background-color: #2e59d9;
    border-color: #2653d4;
}

/* Button Secondary (Quay lại) */
.btn-secondary {
    color: #fff;
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #6f7188;
    border-color: #66687b;
}

/* 6. ALERTS (Message/Error) */
.alert {
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

/* 7. CHECKBOX/RADIO (Form-check) */
.form-check {
    display: block;
    min-height: 1.5rem;
    padding-left: 1.5em;
    margin-bottom: 0.125rem;
}

.form-check-input {
    width: 1em;
    height: 1em;
    margin-top: 0.25em;
    vertical-align: top;
    background-color: #fff;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    border: 1px solid rgba(0, 0, 0, 0.25);
    appearance: none;
    float: left;
    margin-left: -1.5em;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.form-check-input#is_active:checked {
    /* Custom checkmark cho checkbox */
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
}

.form-check-label {
    margin-bottom: 0;
}
</style>
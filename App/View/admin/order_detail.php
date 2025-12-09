<?php
session_start();
require_once __DIR__ . "/../../Model/database.php";

$db = new Database("localhost", "5svcode", "root", "");
$pdo = $db->connect();

// Kiểm tra ID đơn hàng
if (!isset($_GET['id'])) {
    echo "<script>alert('Không tìm thấy ID đơn hàng!'); window.location='admin.php?page=order';</script>";
    exit;
}

$order_id = intval($_GET['id']);

// Lấy đơn hàng + chi tiết + sản phẩm
$sql = "SELECT 
            dh.*,
            ct.id_ctdh, ct.id_SP, ct.soluong, ct.giamua, ct.size,
            sp.Name AS ten_SP, 
            sp.img
        FROM donhang dh
        JOIN chitiet_donhang ct ON dh.id_dh = ct.id_dh
        JOIN sanpham sp ON sp.id_SP = ct.id_SP
        WHERE dh.id_dh = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$order_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$result) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location='admin.php?page=order';</script>";
    exit;
}

// Thông tin đơn hàng
$order_detail = $result[0];
$order_status = $order_detail['status'];
$subtotal = $order_detail['subtotal'];

// Gom các sản phẩm
$items = [];
foreach ($result as $row) {
    $items[] = [
        'img' => $row['img'],              // ảnh từ bảng sanpham
        'ten_san_pham' => $row['ten_SP'], // Name
        'so_luong' => $row['soluong'],
        'don_gia' => $row['giamua'],
    ];
}

// Hàm format tiền
function format_currency($n) {
    return number_format($n, 0, ',', '.') . "đ";
}
?>

<style>
/* CSS của bạn để vào đây nếu muốn */
/* =============================
   CHUNG CHO ORDER DETAIL
============================= */
.main-content.order-detail {
    margin-top: 70px;
    margin-left: 250px;
    padding: 20px;
    font-family: 'Poppins', sans-serif;
    background-color: #f9f9f9;
}

/* HEADER */
.main-content.order-detail .content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.main-content.order-detail .content-header h2 {
    font-size: 28px;
    color: #333;
}

.main-content.order-detail .content-header .btn {
    background-color: #007bff; /* xanh dương */
    color: #fff;
    text-decoration: none;
    padding: 8px 15px;
    border-radius: 5px;
    transition: 0.3s;
}

.main-content.order-detail .content-header .btn:hover {
    background-color: #0056b3;
}

/* THÔNG TIN KHÁCH HÀNG + ĐƠN HÀNG */
.info-blocks {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.info-blocks .col-md-6 {
    flex: 1 1 45%;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.info-blocks h3 {
    margin-bottom: 15px;
    font-size: 20px;
    color: #007bff;
    /* border-bottom: 2px solid #007bff; xanh dương */
    display: inline-block;
    padding-bottom: 5px;
}

.table-info {
    width: 100%;
    border-collapse: collapse;
}

.table-info th, .table-info td {
    text-align: left;
    padding: 8px 5px;
    vertical-align: top;
}

.table-info th {
    width: 40%;
    color: #555;
    font-weight: 600;
}

.table-info td {
    color: #333;
}

/* STATUS */
.order-status {
    padding: 4px 10px;
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
    text-transform: capitalize;
    font-size: 0.9em;
}

.order-status.status-pending { background-color: #ffc107; }
.order-status.status-completed { background-color: #007bff; } /* xanh dương */
.order-status.status-cancelled { background-color: #dc3545; }

/* =============================
   BẢNG SẢN PHẨM
============================= */
.data-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin-top: 20px;
}

.data-table th, .data-table td {
    padding: 12px 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.data-table th {
    background-color: #007bff; /* xanh dương */
    color: #fff;
    font-weight: 600;
}

.data-table tbody tr:hover {
    background-color: #e6f0ff; /* xanh nhạt khi hover */
}

.data-table img {
    border-radius: 6px;
    object-fit: cover;
}

.data-table tfoot td {
    font-weight: bold;
    /* border-top: 2px solid #007bff; xanh dương */
}

/* RESPONSIVE */
@media screen and (max-width: 992px) {
    .info-blocks {
        flex-direction: column;
    }

    .info-blocks .col-md-6 {
        flex: 1 1 100%;
    }

    .main-content.order-detail {
        margin-left: 0;
        margin-top: 20px;
    }
}


</style>

<div class="main-content order-detail" style="margin-top: 70px; margin-left: 250px;">
    <div class="content-header">
        <h2>Chi tiết Đơn hàng #<?= $order_id ?></h2>
        <a href="admin.php?page=order" class="btn btn-secondary">← Quay lại danh sách</a>
    </div>

    <div class="row info-blocks">
        <div class="col-md-6">
            <h3>Thông tin Khách hàng</h3>
            <table class="table-info">
                <tr>
                    <th>Họ và tên:</th>
                    <td><?= $order_detail['fullname'] ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?= $order_detail['email'] ?></td>
                </tr>
                <tr>
                    <th>Điện thoại:</th>
                    <td><?= $order_detail['phone'] ?></td>
                </tr>
                <tr>
                    <th>Địa chỉ Giao hàng:</th>
                    <td><?= $order_detail['address'] ?></td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <h3>Thông tin Đơn hàng</h3>
            <table class="table-info">
                <tr>
                    <th>Mã Đơn hàng:</th>
                    <td>#<?= $order_detail['id_dh'] ?></td>
                </tr>
                <tr>
                    <th>Ngày đặt:</th>
                    <td><?= $order_detail['ngay_mua'] ?></td>
                </tr>
                <tr>
                    <th>Trạng thái:</th>
                    <td>
                        <span class="order-status status-<?= str_replace(' ', '-', strtolower($order_status)) ?>">
                            <?= $order_status ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Voucher áp dụng:</th>
                    <td><?= $order_detail['id_voucher'] ?? "Không" ?></td>
                </tr>
            </table>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #ccc; margin: 25px 0;">

    <h3>Sản phẩm đã đặt</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">Ảnh</th>
                <th style="width: 35%;">Tên sản phẩm</th>
                <th style="width: 10%;">Số lượng</th>
                <th style="width: 15%;">Đơn giá</th>
                <th style="width: 20%;">Thành tiền</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <img src="App/public/img/<?= $item['img'] ?>" 
                             style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
                    </td>
                    <td><?= $item['ten_san_pham'] ?></td>
                    <td><?= $item['so_luong'] ?></td>
                    <td><?= format_currency($item['don_gia']) ?></td>
                    <td><?= format_currency($item['so_luong'] * $item['don_gia']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Tạm tính:</td>
                <td style="font-weight: bold;"><?= format_currency($subtotal) ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Tổng tiền:</td>
                <td style="font-weight: bold;"><?= format_currency($order_detail['total']) ?></td>
            </tr>
        </tfoot>
    </table>

</div>

<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
if (!defined('APP_PATH'))
    die('No direct access');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem lịch sử đơn hàng'); window.location='index.php?page=account';</script>";
    exit;
}
$id_User = $_SESSION['user_id'];

require_once __DIR__ . "/../../Model/database.php";
$db = new Database("localhost", "5svcode", "root", "");
$pdo = $db->connect();

/* ======================= TỰ ĐỘNG CẬP NHẬT TRẠNG THÁI ĐƠN ======================= */
function autoUpdateOrderStatus($pdo) {
    // "Chờ xử lý" -> "Đang giao" nếu quá 1 ngày
    $sql1 = "UPDATE donhang 
             SET status='Đang giao' 
             WHERE status='Chờ xử lý' AND ngay_mua <= DATE_SUB(NOW(), INTERVAL 1 DAY)";
    $pdo->exec($sql1);

    // "Đang giao" -> "Đã hoàn thành" nếu quá 4 ngày (1+3)
    $sql2 = "UPDATE donhang 
             SET status='Đã hoàn thành' 
             WHERE status='Đang giao' AND ngay_mua <= DATE_SUB(NOW(), INTERVAL 4 DAY)";
    $pdo->exec($sql2);
}
autoUpdateOrderStatus($pdo);

/* ======================= HỦY ĐƠN ======================= */
if (isset($_POST['action']) && $_POST['action'] == "cancel_order") {
    $id_dh = $_POST['id_dh'];

    $sql = "UPDATE donhang SET status='Đã hủy'
            WHERE id_dh=? AND id_User=? AND status='Chờ xử lý'";

    $stm = $pdo->prepare($sql);
    if ($stm->execute([$id_dh, $id_User])) {
        header("Location: index.php?page=order_history");
        exit;
    }
}

/* ======================= GỬI ĐÁNH GIÁ ======================= */
if (isset($_POST['action']) && $_POST['action'] == "submit_review") {

    $id_sp = intval($_POST['id_sp']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['review']);

    if ($id_sp > 0) {

        // Check đã đánh giá chưa
        $chk = $pdo->prepare("SELECT id_cmt FROM comment WHERE id_User=? AND id_SP=? LIMIT 1");
        $chk->execute([$id_User, $id_sp]);

        if ($chk->rowCount() == 0) {

            // INSERT đúng tên cột bảng bạn
            $sql = "INSERT INTO comment (Noi_dung, date, id_SP, id_User, rating, created_at)
                    VALUES (?, NOW(), ?, ?, ?, NOW())";

            $stm = $pdo->prepare($sql);
            $stm->execute([$comment, $id_sp, $id_User, $rating]);
        }
    }

    // Reload chuẩn (không dùng alert + reload)
    header("Location: index.php?page=order_history");
    exit;
}

/* ======================= LẤY DANH SÁCH ĐƠN ======================= */
$sql = "SELECT * FROM donhang WHERE id_User=? ORDER BY id_dh DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_User]);
$list_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ======================= HÀM ======================= */
function statusClass($s)
{
    return match ($s) {
        "Chờ xử lý" => "status-cho",
        "Đang giao" => "status-danggiao",
        "Đã hoàn thành" => "status-hoanthanh",
        "Đã hủy" => "status-huy",
        default => ""
    };
}

function hasReviewed($pdo, $user, $sp)
{
    $stm = $pdo->prepare("SELECT id_cmt FROM comment WHERE id_User=? AND id_SP=? LIMIT 1");
    $stm->execute([$user, $sp]);
    return $stm->fetch();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử mua hàng</title>
    <style>
        body { background: #f5f5f7; font-family: Arial; }
        .order-history-page { max-width: 930px; margin: 40px auto; }
        .order-card { background: #fff; padding: 25px; border-radius: 16px; margin-bottom: 28px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
        .order-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .status { padding: 6px 16px; border-radius: 12px; font-weight: bold; font-size: 13px; }
        .status-cho { background: #ffe8c6; color: #b87300; }
        .status-danggiao { background: #c6e7ff; color: #005a93; }
        .status-hoanthanh { background: #d2ffd2; color: #0a7c00; }
        .status-huy { background: #ffd3d3; color: #b10000; }
        .order-item { display: flex; gap: 15px; padding: 12px 0; border-bottom: 1px solid #eee; }
        .order-item img { width: 90px; height: 90px; border-radius: 8px; object-fit: cover; }
        .order-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 14px; margin-top: 10px; }
        .footer-left { display: flex; gap: 10px; align-items: center; }
        .btn { padding: 8px 14px; border: none; border-radius: 6px; color: #fff; font-size: 14px; cursor: pointer; white-space: nowrap; }
        .btn-cancel { background: #e63946; }
        .btn-review { background: #007bff; }
        .btn-rebuy { background: #28a745; }
        .popup-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.6); display:none; justify-content:center; align-items:center; }
        .popup-box { background:#fff; padding:20px; width:330px; border-radius:12px; }
        .star { font-size:26px; cursor:pointer; color:#ccc; }
        .star.filled { color:#FFD700; }
        textarea { width:100%; height:80px; margin-top:10px; border-radius:8px; border:1px solid #ccc; padding:8px; resize:none; }
    </style>
</head>

<body>

<div class="order-history-page">
    <h2 style="text-align:center; margin-bottom:30px;">Lịch sử mua hàng</h2>

    <?php foreach ($list_orders as $order): ?>

        <?php
        $st = $pdo->prepare("SELECT ctdh.*, sp.Name, sp.img 
                             FROM chitiet_donhang ctdh 
                             JOIN sanpham sp ON sp.id_SP = ctdh.id_SP 
                             WHERE ctdh.id_dh=?");
        $st->execute([$order['id_dh']]);
        $items = $st->fetchAll(PDO::FETCH_ASSOC);

        $needReview = false;
        foreach ($items as $i) {
            if (!hasReviewed($pdo, $id_User, $i['id_SP'])) {
                $needReview = $i['id_SP'];
                break;
            }
        }
        ?>

        <div class="order-card">
            <div class="order-header">
                <div>
                    <h3>Đơn #<?= $order['id_dh'] ?></h3>
                    <p style="color:#777;">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['ngay_mua'])) ?></p>
                </div>
                <span class="status <?= statusClass($order['status']) ?>">
                    <?= $order['status'] ?>
                </span>
            </div>

            <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <img src="App/public/img/<?= $item['img'] ?>">
                    <div>
                        <p><b><?= $item['Name'] ?></b></p>
                        <p>Size: <?= $item['size'] ?> | SL: <?= $item['soluong'] ?></p>
                        <p><b><?= number_format($item['giamua']) ?>đ</b></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="order-footer">

                <div class="footer-left">

                    <?php if ($order['status'] == "Chờ xử lý"): ?>
                        <form method="POST">
                            <input type="hidden" name="action" value="cancel_order">
                            <input type="hidden" name="id_dh" value="<?= $order['id_dh'] ?>">
                            <button class="btn btn-cancel">Hủy đơn</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($order['status'] == "Đã hoàn thành"): ?>
                        <?php if ($needReview): ?>
                            <button class="btn btn-review" onclick="openReviewPopup(<?= $needReview ?>)">Đánh giá</button>
                        <?php else: ?>
                            <span style="color:#28a745; font-weight:bold;">Bạn đã đánh giá</span>
                        <?php endif; ?>

                        <form action="index.php?page=add_to_cart" method="post">
                            <input type="hidden" name="id_SP" value="<?= $item['id_SP'] ?>">
                            <input type="hidden" name="size" value="<?= $item['size'] ?>">
                            <input type="hidden" name="qty" value="<?= $item['soluong'] ?>">
                            <button type="submit" class="btn btn-rebuy">Mua lại</button>
                        </form>

                    <?php endif; ?>

                </div>

                <div style="text-align:right;">
                    <p><b>Tổng:</b> <?= number_format($order['total']) ?>đ</p>
                    <p style="color:#777;">Phí ship: <?= number_format($order['shipping']) ?>đ</p>
                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<!-- POPUP -->
<div class="popup-overlay" id="popupReview">
    <div class="popup-box">
        <h3>Đánh giá sản phẩm</h3>
        <form method="POST">
            <input type="hidden" name="action" value="submit_review">
            <input type="hidden" name="id_sp" id="popup_sp">

            <div id="starArea">
                <span class="star" data-val="1">★</span>
                <span class="star" data-val="2">★</span>
                <span class="star" data-val="3">★</span>
                <span class="star" data-val="4">★</span>
                <span class="star" data-val="5">★</span>
            </div>

            <input type="hidden" name="rating" id="ratingValue" value="5">
            <textarea name="review" placeholder="Viết đánh giá của bạn..."></textarea>

            <button class="btn btn-review" type="submit" style="margin-top:10px;">Gửi đánh giá</button>
            <button type="button" class="btn" onclick="closePopup()" style="background:#666; margin-top:10px;">Đóng</button>
        </form>
    </div>
</div>

<script>
    function openReviewPopup(id) {
        document.getElementById("popup_sp").value = id;
        document.getElementById("popupReview").style.display = "flex";
    }
    function closePopup() {
        document.getElementById("popupReview").style.display = "none";
    }

    let stars = document.querySelectorAll('#starArea .star');
    stars.forEach(s => s.addEventListener('click', function () {
        let v = this.dataset.val;
        document.getElementById("ratingValue").value = v;
        stars.forEach(st => st.classList.remove('filled'));
        for (let i = 0; i < v; i++) stars[i].classList.add('filled');
    }));
    stars.forEach(s => s.classList.add('filled')); // mặc định 5 sao
</script>

</body>
</html>

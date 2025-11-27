<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng - 5SV Sport</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 40px auto; display: grid; grid-template-columns: 1fr 420px; gap: 30px; }
        .left, .right { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h2 { font-size: 22px; margin-bottom: 20px; color: #d60000; }
        .step { display: inline-flex; align-items: center; gap: center; gap: 10px; margin-bottom: 30px; font-weight: 600; }
        .step span { background: #eee; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .step span.active { background: #d60000; color: white; }
        input, select { width: 100%; padding: 12px; margin: 8px 0 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; }
        label { font-weight: 500; color: #333; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .note { background: #fff8e1; padding: 15px; border-radius: 8px; font-size: 14px; color: #ff6d00; margin-top: 20px; }

        /* Phải - Đơn hàng */
        .order-summary { position: sticky; top: 20px; }
        .product { display: flex; gap: 15px; padding: 20px 0; border-bottom: 1px solid #eee; }
        .product img { width: 90px; height: 90px; object-fit: cover; border-radius: 8px; }
        .product-info h4 { margin: 0; font-size: 16px; }
        .product-info p { margin: 5px 0 0; color: #666; font-size: 14px; }
        .price-row { display: flex; justify-content: space-between; padding: 12px 0; }
        .total { font-size: 20px; font-weight: 700; color: #d60000; border-top: 2px solid #eee; padding-top: 15px; margin-top: 15px; }
        .btn { background: #000; color: white; padding: 16px; text-align: center; border: none; border-radius: 8px; font-size: 18px; font-weight: 600; cursor: pointer; margin-top: 80px; line-height: 80px; }
        .btn:hover { background: #333; }
        .payment-methods { display: flex; gap: 15px; margin: 20px 0; }
        .payment-methods img { height: 34px; }
        @media (max-width: 992px) {
            .container { grid-template-columns: 1fr; }
            .right { order: -1; }
        }
        /* ================== RADIO BUTTON ĐẸP ĐỀU - DÁN VÀO CUỐI style.css ================== */
.payment-section {
    margin: 30px 0;
}

.payment-section h2 {
    color: #d60000;
    font-size: 24px;
    margin-bottom: 25px;
    text-align: left;
}

/* Ẩn nút radio mặc định */
.payment-section input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

/* Tạo nút tròn giả */
.payment-section label {
    display: flex;
    align-items: center;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin: 18px 0;
    cursor: pointer;
    padding-left: 38px;
    position: relative;
    user-select: none;
}

/* Vòng tròn ngoài */
.payment-section label::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    border: 2px solid #ccc;
    border-radius: 50%;
    background: white;
    transition: all 0.3s ease;
}

/* Chấm tròn bên trong khi được chọn */
.payment-section label::after {
    content: '';
    position: absolute;
    left: 2px;
    top:50%;
    transform: translateY(-50%) scale(0);
    width: 24px;
    height: 24px;
    background: #d60000;
    border-radius: 50%;
    transition: transform 0.3s ease;
}

/* Khi được chọn */
.payment-section input[type="radio"]:checked + label::before {
    border-color: #d60000;
    box-shadow: 0 0 0 4px rgba(214, 0, 0, 0.1);
}

.payment-section input[type="radio"]:checked + label::after {
    transform: translateY(-50%) scale(1);
}

/* Hover nhẹ */
.payment-section label:hover::before {
    border-color: #d60000;
}
    </style>
</head>
<body>

<div class="container">

    <!-- CỘT TRÁI - THÔNG TIN GIAO HÀNG -->
    <div class="left">
        <div class="step">
            <span class="active">1</span> Giỏ hàng >
            <span class="active">2</span> Thanh toán >
            <span>3</span> Đơn hàng
        </div>

        <h2>THÔNG TIN MUA HÀNG</h2>
        <form method="POST" action="order.php">
            <label>Họ và tên *</label>
            <input type="text" name="fullname" required placeholder="Nguyễn Văn A">

            <div class="row">
                <div>
                    <label>Số điện thoại *</label>
                    <input type="text" name="phone" required placeholder="0901234567">
                </div>
                <div>
                    <label>Địa chỉ email</label>
                    <input type="email" name="email" placeholder="abc@gmail.com">
                </div>
            </div>

            <label>Địa chỉ nhận hàng *</label>
            <input type="text" name="address" required placeholder="Số nhà, tên đường...">

            <div class="row">
                <div>
                    <label>Chọn tỉnh/ thành phố *</label>
                    <select name="city" required>
                        <option value="">-- Chọn tỉnh/thành --</option>
                        <option>Hồ Chí Minh</option>
                        <option>Hà Nội</option>
                        <option>Đà Nẵng</option>
                        <!-- thêm tùy ý -->
                    </select>
                </div>
                <div>
                    <label>Chọn quận/ huyện *</label>
                    <select name="district" required>
                        <option value="">-- Chọn quận/huyện --</option>
                    </select>
                </div>
            </div>

            <label>Ghi chú đơn hàng (không bắt buộc)</label>
            <textarea name="note" rows="3" placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn..."></textarea>

            <div class="payment-section">
    <h2>PHƯƠNG THỨC THANH TOÁN</h2>

    <input type="radio" name="payment" value="cod" id="cod" checked>
    <label for="cod">Thanh toán khi nhận hàng (COD)</label>

    <input type="radio" name="payment" value="bank" id="bank">
    <label for="bank">Chuyển khoản ngân hàng</label>

    <input type="radio" name="payment" value="momo" id="momo">
    <label for="momo">Thanh toán qua Momo</label>
</div>
            </label>

            <div class="payment-methods">
                <img src="https://i.imgur.com/IzY8MWG.png" alt="Visa">
                <img src="https://i.imgur.com/7QXu9gu.png" alt="Momo">
                <img src="https://i.imgur.com/9x3V4lG.png" alt="ZaloPay">
            </div>

            <button type="submit" name="submit" class="btn">ĐẶT HÀNG</button>
        </form>
    </div>

    <!-- CỘT PHẢI - TÓM TẮT ĐƠN HÀNG -->
    <div class="right order-summary">
        <h2>Đơn hàng của bạn</h2>

        <div class="product">
            <img src="App/public/img/Ao-polo_nu.jpg" alt="Áo">
            <div class="product-info">
                <h4>Áo thun thể thao nam tay ngắn phối lưới</h4>
                <p>Màu sắc: Xanh Đen / Size: L</p>
            </div>
        </div>

        <div class="price-row"><span>Tạm tính</span> <strong>111.000đ</strong></div>
        <div class="price-row"><span>Vận chuyển</span> <strong>10.000đ</strong></div>
        <div class="price-row total"><span>Tổng</span> <strong style="color:#d60000">121.000đ</strong></div>

        <div style="margin-top:25px; font-size:14px; color:#666; line-height:1.6;">
            Thông tin cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng, tăng trải nghiệm mua sắm trên website này và cho các mục đích khác theo chính sách của chúng tôi.
        </div>

        <a href="#" class="btn">QUAY LẠI GIỎ HÀNG</a>
    </div>

</div>

<?php
if(isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $note = $_POST['note'];

    echo "<script>alert('Đặt hàng thành công! Cảm ơn $fullname đã mua hàng ❤️ Chúng tôi sẽ liên hệ sớm nhất.');</script>";
    // Sau này cháu thêm lưu vào CSDL, gửi mail, v.v...
}
?>

</body>
</html>
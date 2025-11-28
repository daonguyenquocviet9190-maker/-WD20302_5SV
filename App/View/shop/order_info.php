<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mua hàng thành công - 5SV Sport Fashion</title>
    <link rel="stylesheet" href="App/public/shop/css/style.css"> <!-- Giả sử bạn có file CSS chung -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5}
        .success-container { max-width: 800px; margin: 50px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .success-check { color: green; font-size: 50px; margin-bottom: 20px; }
        .success-title { font-size: 24px; margin-bottom: 10px; }
        .success-subtitle { font-size: 16px; margin-bottom: 30px; }
        .order-info { text-align: left; margin-bottom: 30px; }
        .order-info p { margin: 8px 0; font-size: 15px; }
        .btn-continue { display: inline-block; background: #000; color: white; padding: 12px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; transition: background 0.3s; }
        .btn-continue:hover { background: #333; }
    </style>
</head>
<body>

<div class="success-container">
    <i class="fas fa-check-circle success-check"></i>
    <h1 class="success-title">Mua hàng thành công!</h1>
    <p class="success-subtitle">Cảm ơn bạn đã mua sắm tại 5SV. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
    
    <div class="order-info">
        <p><strong>Thông tin đơn hàng của bạn</strong></p>
        <p>Mã đơn hàng: <span id="order-id">23246</span></p> <!-- Thay bằng dữ liệu thực từ database hoặc session -->
        <p>Ngày: <span id="order-date">28/11/2025</span></p>
        <p>Tổng cộng: <span id="order-total">474.000 đ</span></p>
        <p>Phương thức thanh toán: <span id="payment-method">Thanh toán khi nhận hàng</span></p>
        
        <p><strong>Thông tin nhận hàng</strong></p>
        <p>Người nhận: <span id="receiver-name">Nguyễn Thị Hằng</span></p>
        <p>Địa chỉ: <span id="receiver-address">37/10 Thạnh Hòa A, Thạnh Phú, Bến Tre</span></p>
        <p>Điện thoại: <span id="receiver-phone">+84359045813</span></p>
        <p>Email: <span id="receiver-email">vuaxuan101@gmail.com</span></p>
    </div>
    
    <a href="index.php?page=home" class="btn-continue">Tiếp tục mua sắm</a>
</div>


<script>
    // Nếu cần, bạn có thể dùng JS để điền dữ liệu động từ PHP/session
    // Ví dụ: document.getElementById('order-id').textContent = '<?php echo $order_id; ?>';
</script>

</body>
</html>
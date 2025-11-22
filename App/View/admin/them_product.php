<div class="main-content">
    <div class="content-header">
        <h2>Thêm sản phẩm</h2>
        <a href="?page=product" class="btn-add" style="background-color: #5cb85c;">← Quay lại</a>
    </div>

    <?php 
    // HIỂN THỊ THÔNG BÁO TỪ PHẦN PHP
    // Đã thay đổi class wrapper từ .container thành .main-content
    // if (!empty($thong_bao)) {
    //     $alert_class = (strpos($thong_bao, '✅') !== false) ? 'alert-success' : 'alert-danger';
    //     echo "<div class='alert $alert_class'>" . nl2br($thong_bao) . "</div>";
    // }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="ten_san_pham">TÊN SẢN PHẨM</label>
            <input type="text" id="ten_san_pham" name="ten_san_pham" placeholder="Nhập tên sản phẩm" required 
                   value="<?php echo isset($ten_san_pham) ? htmlspecialchars($ten_san_pham) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="gia">GIÁ (VNĐ)</label>
            <input type="number" step="0" id="gia" name="gia" placeholder="Nhập giá sản phẩm" required
                   value="<?php echo isset($gia) ? htmlspecialchars($gia) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="so_luong">SỐ LƯỢNG</label>
            <input type="number" step="1" id="so_luong" name="so_luong" placeholder="Nhập số lượng tồn kho" required
                   value="<?php echo isset($so_luong) ? htmlspecialchars($so_luong) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="category">LOẠI SẢN PHẨM</label>
            <select id="category" name="category" required>
                <option value="">-- Chọn loại sản phẩm --</option>
                <option value="1">Áo</option>
                <option value="2">Quần</option>
                <option value="3">Phụ kiện</option>
            </select>
        </div>

        <div class="form-group">
            <label for="img">HÌNH ẢNH</label>
            <input type="file" id="img" name="img" accept="image/*" required> 
        </div>

        <div class="form-group">
            <label for="mo_ta">MÔ TẢ</label>
            <textarea id="mo_ta" name="mo_ta" rows="5" placeholder="Nhập mô tả chi tiết sản phẩm"><?php echo isset($mo_ta) ? htmlspecialchars($mo_ta) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <button type="submit" name="them_san_pham" class="btn-them">
                <span class="icon-plus">+</span> Thêm sản phẩm
            </button>
        </div>

    </form>
</div>

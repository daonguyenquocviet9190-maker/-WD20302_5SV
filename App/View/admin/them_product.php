<div class="main-content">
    <div class="content-header">
        <h2><?= !empty($sp_edit) ? "Sửa sản phẩm" : "Thêm sản phẩm" ?></h2>
        <a href="admin.php?page=product" class="btn-add" style="background-color:#5cb85c;">← Quay lại</a>
    </div>

    <form id="productForm" action="" method="post" enctype="multipart/form-data">

        <?php if (!empty($sp_edit)): ?>
            <input type="hidden" name="idedit" value="<?= $sp_edit['id_SP'] ?>">
            <input type="hidden" name="old_img" value="<?= $sp_edit['img'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>TÊN SẢN PHẨM</label>
            <input type="text" name="ten_san_pham"
                   value="<?= !empty($sp_edit) ? htmlspecialchars($sp_edit['Name']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>GIÁ</label>
            <input type="number" name="gia" id="gia"
                   value="<?= !empty($sp_edit) ? $sp_edit['Price'] : '' ?>" min="0" required>
        </div>

        <div class="form-group">
            <label>GIÁ GIẢM</label>
            <input type="number" name="gia_giam" id="gia_giam"
                   value="<?= !empty($sp_edit) ? $sp_edit['sale_price'] : '' ?>" min="0">
        </div>

        <div class="form-group">
            <label>SỐ LƯỢNG</label>
            <input type="number" name="so_luong" min="1"
                   value="<?= !empty($sp_edit) ? $sp_edit['stock'] : '' ?>" required>
        </div>

        <div class="form-group">
            <label>SIZE</label>
            <select name="size" required>
                <option value="">-- Chọn size --</option>
                <?php foreach ($sizes as $s): ?>
                    <option value="<?= $s ?>" <?= (!empty($sp_edit) && $sp_edit['size']==$s) ? "selected" : "" ?>>
                        <?= htmlspecialchars($s) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>LOẠI SẢN PHẨM</label>
            <select name="category" required>
                <option value="">-- Chọn loại --</option>
                <?php foreach ($dsdm as $row): ?>
                    <option value="<?= $row['id_DM'] ?>"
                        <?= (!empty($sp_edit) && $sp_edit['id_DM'] == $row['id_DM']) ? "selected" : "" ?>>
                        <?= htmlspecialchars($row['Name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>HÌNH ẢNH</label>
            <input type="file" name="img" accept="image/*" id="imgInput">
            <div style="margin-top:10px;">
                <?php if (!empty($sp_edit)): ?>
                    <img id="imgPreview" src="App/public/img/<?= $sp_edit['img'] ?>" width="80">
                <?php else: ?>
                    <img id="imgPreview" src="" width="80" style="display:none;">
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" name="save_product" class="btn-them">
            <?= !empty($sp_edit) ? "Cập nhật" : "Thêm sản phẩm" ?>
        </button>
    </form>
</div>

<script>
    // Preview ảnh khi chọn file mới
    const imgInput = document.getElementById('imgInput');
    const imgPreview = document.getElementById('imgPreview');

    imgInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.style.display = 'block';
        } else {
            imgPreview.style.display = 'none';
        }
    });

    // Validate giá giảm <= giá gốc
    const form = document.getElementById('productForm');
    form.addEventListener('submit', function(e) {
        const price = parseFloat(document.getElementById('gia').value);
        const salePrice = parseFloat(document.getElementById('gia_giam').value || 0);

        if (salePrice > price) {
            alert('Giá giảm không được lớn hơn giá gốc!');
            e.preventDefault();
        }
    });
</script>

<div class="main-content">
    <div class="content-header">
        <h2><?= !empty($sp_edit) ? "Sửa sản phẩm" : "Thêm sản phẩm" ?></h2>
        <a href="admin.php?page=product" class="btn-add" style="background-color:#5cb85c;">← Quay lại</a>
    </div>

    <form action="" method="post" enctype="multipart/form-data">

        <?php if (!empty($sp_edit)): ?>
            <input type="hidden" name="idedit" value="<?= $sp_edit['id_SP'] ?>">
            <input type="hidden" name="old_img" value="<?= $sp_edit['img'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>TÊN SẢN PHẨM</label>
            <input type="text" name="ten_san_pham"
                   value="<?= !empty($sp_edit) ? $sp_edit['Name'] : '' ?>" required>
        </div>

        <div class="form-group">
            <label>GIÁ</label>
            <input type="number" name="gia"
                   value="<?= !empty($sp_edit) ? $sp_edit['Price'] : '' ?>" required>
        </div>

        <div class="form-group">
    <label>SỐ LƯỢNG</label>
    <input type="number" name="so_luong" min="1"
           value="<?= !empty($sp_edit) ? $sp_edit['stock'] : '' ?>" required>
</div>

<select name="size" required>
    <option value="">-- Chọn size --</option>
    <?php foreach ($sizes as $s): ?>
        <option value="<?= $s ?>" <?= (!empty($sp_edit) && $sp_edit['size']==$s) ? "selected" : "" ?>>
            <?= $s ?>
        </option>
    <?php endforeach; ?>
</select>



        <div class="form-group">
            <label>LOẠI SẢN PHẨM</label>
            <select name="category" required>
                <option value="">-- Chọn loại --</option>
                <?php foreach ($dsdm as $row): ?>
                <option value="<?= $row['id_DM'] ?>"
                    <?= (!empty($sp_edit) && $sp_edit['id_DM'] == $row['id_DM']) ? "selected" : "" ?>>
                    <?= $row['Name'] ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>HÌNH ẢNH</label>
            <input type="file" name="img" accept="image/*">

            <?php if (!empty($sp_edit)): ?>
                <img src="App/public/img/<?= $sp_edit['img'] ?>" width="80" style="margin-top:10px;">
            <?php endif; ?>
        </div>

        <button type="submit" name="save_product" class="btn-them">
            <?= !empty($sp_edit) ? "Cập nhật" : "Thêm sản phẩm" ?>
        </button>
    </form>
</div>

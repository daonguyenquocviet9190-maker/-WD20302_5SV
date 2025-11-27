<div class="main-content">
    <div class="content-header">
        <h2>
            <?php echo !empty($dm_edit) ? "Sửa danh mục" : "Thêm danh mục"; ?>
        </h2>
        <a href="admin.php?page=category" class="btn-add" style="background-color:#5cb85c;">← Quay lại</a>
    </div>

    <form action="" method="post">
        <div class="form-group">
            <label for="cat_name">TÊN DANH MỤC</label>
            <input type="text" id="cat_name" name="cat_name" 
                   placeholder="Nhập tên danh mục" required
                   value="<?php echo !empty($dm_edit) ? htmlspecialchars($dm_edit['Name']) : ''; ?>">
        </div>

        <div class="form-group">
            <button type="submit" name="save_category" class="btn-them">
                <span class="icon-plus">+</span>
                <?php echo !empty($dm_edit) ? "Cập nhật" : "Thêm danh mục"; ?>
            </button>
        </div>
    </form>
</div>
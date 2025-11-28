<?php
$name = '';
if(!empty($dm_edit) && isset($dm_edit['Name'])){
    $name = $dm_edit['Name'];
}
?>

<div class="main-content">
    <div class="content-header">
        <h2><?= !empty($dm_edit) ? "Sửa danh mục" : "Thêm danh mục" ?></h2>
        <a href="admin.php?page=category" class="btn-add" style="background-color:#5cb85c;">← Quay lại</a>
    </div>

    <form action="" method="post">
    <input type="text" name="cat_name" value="<?= htmlspecialchars($name) ?>">
    <?php if(!empty($dm_edit)): ?>
        <input type="hidden" name="idedit" value="<?= $_GET['idedit'] ?>">
    <?php endif; ?>
    <button type="submit" name="save_category" class="btn-them">
        <?= !empty($dm_edit) ? 'Cập nhật' : 'Thêm danh mục' ?>
    </button>
</form>

</div>

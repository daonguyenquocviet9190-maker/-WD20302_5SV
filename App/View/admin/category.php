
    <div class="main-content">
        <div class="content-header">
            <h2>Quản lý loại sản phẩm</h2>
            <a href="?page=category&action=add" class="btn-add">Thêm</a>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TÊN LOẠI</th>
                    <th>HÀNH ĐỘNG</th>
                </tr>
            </thead>
            <tbody>
               <?php if (!empty($dsdm)) : ?>
    <?php foreach ($dsdm as $row) : ?>
        <tr>
            <td><?= $row['id_DM'] ?></td>
            <td><?= $row['Name'] ?></td>

            <td class="actions">
                <!-- Nút Sửa -->
                <a href="admin.php?page=category&idedit=<?= $row['id_DM'] ?>" title="Sửa">
                    <i class="fas fa-edit"></i>
                </a>

                <!-- Nút Xóa -->
                <a href="admin.php?page=category&id=<?= $row['id_DM'] ?>"
                   onclick="return confirm('Bạn có chắc muốn xóa loại sản phẩm này?');"
                   title="Xóa">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else : ?>
    <tr><td colspan="3">Không có loại sản phẩm nào.</td></tr>
<?php endif; ?>

            </tbody>
        </table>
    </div>
    
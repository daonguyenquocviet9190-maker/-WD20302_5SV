<div class="main-content">
    <div class="content-header">
        <h2>Quản lý danh mục</h2>
        <a href="admin.php?page=category&action=add" class="btn-add">Thêm</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>TÊN DANH MỤC</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($dsdm)): ?>
                <?php foreach($dsdm as $row): ?>
                    <tr>
                        <td><?= $row['id_DM'] ?></td>
                        <td><?= htmlspecialchars($row['Name']) ?></td>
                        <td class="actions">
                            <a href="admin.php?page=category&action=edit&idedit=<?= $row['id_DM'] ?>" title="Sửa"><i class="fas fa-edit"></i></a>
                            <!-- <a href="admin.php?page=category&action=delete&id=<?= $row['id_DM'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?');" title="Xóa"><i class="fas fa-trash-alt"></i></a> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">Không có danh mục nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

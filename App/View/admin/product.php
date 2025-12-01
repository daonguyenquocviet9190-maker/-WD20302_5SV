<div class="main-content product-management">
    <div class="content-header">
        <h2>Quản lý sản phẩm</h2>
        <a href="admin.php?page=product&action=add" class="btn-add">Thêm</a>
    </div>

    <table class="data-table product-table">
        <thead>
            <tr>
                <th style="width: 80px;">ẢNH</th>
                <th style="width: 30%;">TÊN</th>
                <th style="width: 10%;">GIÁ</th>
                <th style="width: 10%;">SỐ LƯỢNG</th>
                <th style="width: 15%;">LOẠI</th>
                <th style="width: 100px;">HÀNH ĐỘNG</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($dssp)): ?>
                <?php foreach ($dssp as $p): ?>
                    <tr>
                        <td>
                            <img src="App/public/img/<?= $p['img'] ?>" alt="<?= $p['Name'] ?>" class="product-thumb"
                                style="width:60px;height:70px;object-fit:cover;">
                        </td>

                        <td><?= $p['Name'] ?></td>

                        <td><?= number_format($p['Price'], 0, ',', '.') ?>đ</td>
                        <td><?= $p['stock'] ?></td>
                        <td>
                            <?php
                            // Hiển thị tên danh mục
                            foreach ($dsdm as $dm) {
                                if ($dm['id_DM'] == $p['id_DM']) {
                                    echo $dm['Name'];
                                }
                            }
                            ?>
                        </td>
                        <td class="actions">
                            <!-- Sửa -->
                            <a href="admin.php?page=product&action=edit&id=<?= $p['id_SP'] ?>" class="btn-edit" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Xóa -->
                            <!-- <a href="admin.php?page=product&action=delete&id=<?= $p['id_SP'] ?>"
                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');" class="btn-delete" title="Xóa">
                                <i class="fas fa-trash-alt"></i> -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Không có sản phẩm nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
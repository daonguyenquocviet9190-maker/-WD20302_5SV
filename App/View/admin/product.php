<div class="main-content product-management">
    <div class="content-header">
        <h2>Quản lý sản phẩm</h2>
        <a href="?page=product&action=add" class="btn-add">Thêm</a> 
    </div>

    <table class="data-table product-table">
        <thead>
            <tr>
                <th style="width: 80px;">ẢNH</th>
                <th style="width: 30%;">TÊN</th>
                <th style="width: 10%;">GIÁ</th>
                <th style="width: 10%;">SỐ LƯỢNG</th>
                <th style="width: 10%;">LOẠI</th>
                <th style="width: 100px;">HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>" class="product-thumb">
                    </td>
                    <td><?= $p['name'] ?></td>
                    <td>₫ <?= number_format($p['price'], 0, ',', '.') ?></td>
                    <td><?= $p['quantity'] ?></td>
                    <td><?= $p['category'] ?></td>
                    <td class="actions">
                        <a href="?page=product&action=edit&id=<?= $p['id'] ?>" title="Sửa"><i class="fas fa-edit"></i></a>
                        <a href="?page=product&action=delete&id=<?= $p['id'] ?>" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Không có sản phẩm nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="main-content order-management">
    <div class="content-header">
        <h2>Quản lý đơn hàng</h2>
        </div>

    <table class="data-table order-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 20%;">KHÁCH HÀNG</th>
                <th style="width: 15%;">ĐIỆN THOẠI</th>
                <th style="width: 15%;">TỔNG TIỀN</th>
                <th style="width: 15%;">THỜI GIAN</th>
                <th style="width: 15%;">TRẠNG THÁI</th>
                <th style="width: 100px;">HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= $o['id_dh'] ?></td>
                    <td><?= $o['fullname'] ?></td>
                    <td><?= $o['phone'] ?></td>
                    <td><?= number_format($o['subtotal'], 0, ',', '.') ?> VNĐ</td>
                    <td><?= $o['ngay_dg'] ?></td>
                    <td>
                        <span class="order-status status-<?= str_replace(' ', '-', strtolower($o['status'])) ?>">
                            <?= $o['status'] ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="?page=order&action=detail&id=<?= $o['id_dh'] ?>" title="Chi tiết"><i class="fas fa-eye"></i></a>
                        <a href="?page=order&action=delete&id=<?= $o['id_dh'] ?>" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?');"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Không có đơn hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

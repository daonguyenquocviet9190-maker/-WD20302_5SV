<div class="main-content customer-management">
    <div class="content-header">
        <h2>Quản lý khách hàng</h2>
        <a href="?page=customer&action=add" class="btn-add">Thêm</a>
    </div>

    <table class="data-table customer-table">
        <thead>
            <tr>
                <th style="width: 50px;">ID</th>
                <th style="width: 20%;">TÊN</th>
                <th style="width: 25%;">EMAIL</th>
                <th style="width: 10%;">VAI TRÒ</th>
                <th style="width: 15%;">LẦN ĐĂNG NHẬP CUỐI</th>
                <th style="width: 10%;">TRẠNG THÁI</th>
                <th style="width: 100px;">XEM ĐƠN HÀNG</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= $c['name'] ?></td>
                    <td><?= $c['email'] ?></td>
                    <td><?= $c['role'] ?></td>
                    <td><?= $c['last_login'] ?></td>
                    <td>
                        <span class="status-badge status-<?= $c['status'] ?>">
                            <?= ucfirst($c['status']) ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="?page=order&customer_id=<?= $c['id'] ?>" title="Xem Đơn hàng">Xem</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Không có khách hàng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
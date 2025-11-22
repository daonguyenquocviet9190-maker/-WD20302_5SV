
    <div class="main-content">
        <div class="content-header">
            <h2>Quản lý loại sản phẩm</h2>
            <a href="them_loaisanpham.php" class="btn-add">Thêm</a>
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
                <?php
                // BƯỚC 3: HIỂN THỊ DỮ LIỆU TRONG BẢNG
                if ($result->num_rows > 0) {
                    // Lặp qua từng dòng dữ liệu
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["ten_loai"] . "</td>";
                        echo "<td class='actions'>";
                        
                        // Nút Sửa (Edit)
                        echo "<a href='sua_loaisanpham.php?id=" . $row["id"] . "' title='Sửa'><i class='fas fa-edit'></i></a>";
                        
                        // Nút Xóa (Delete)
                        // Cần thêm JavaScript để xác nhận trước khi xóa
                        echo "<a href='xoa_loaisanpham.php?id=" . $row["id"] . "' title='Xóa' onclick='return confirm(\"Bạn có chắc muốn xóa loại sản phẩm này?\");'><i class='fas fa-trash-alt'></i></a>";
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Không có loại sản phẩm nào.</td></tr>";
                }
                // ?>
            </tbody>
        </table>
    </div>
    
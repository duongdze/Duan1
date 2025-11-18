<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Quản lý Tour</h1>
                <p class="text-muted">Toàn bộ các tour đang được quản lý trên hệ thống.</p>
            </div>
            <a href="<?= BASE_URL_ADMIN . '&action=tours/create'?>" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm Tour Mới</a>
        </div>

        <div class="card">
            <div class="card-header">
                Danh sách Tour
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên Tour</th>
                                <th>Loại Tour</th>
                                <th>Ngày tạo</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $tour) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($tour['name']) ?></td>
                                    <td><?= htmlspecialchars($tour['type']) ?></td>
                                    <td><?= htmlspecialchars($tour['created_at']) ?></td>
                                    <td><?= htmlspecialchars($tour['base_price']) ?></td>
                                    <td><span class="badge bg-success">Đang mở bán</span></td>
                                    <td>
                                        <a href="<?= BASE_URL_ADMIN . '&action=tours/detail&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                        <a href="<?= BASE_URL_ADMIN . '&action=tours/edit&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="<?= BASE_URL_ADMIN . '&action=tours/delete&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>

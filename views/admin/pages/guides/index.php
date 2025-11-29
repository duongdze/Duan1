<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Quản lý Hướng dẫn viên</h1>
                <p class="text-muted">Danh sách các hướng dẫn viên trong công ty.</p>
            </div>
            <a href="<?= BASE_URL_ADMIN . '&action=guides/create' ?>">
                <button class="btn btn-primary"><i class="fas fa-user-plus"></i> Thêm HDV mới</button>
            </a>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                Danh sách HDV (<?= count($guides) ?>)
            </div>
            <div class="card-body">
                <?php if (!empty($guides)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Họ và tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Ngôn ngữ</th>
                                    <th>Kinh nghiệm</th>
                                    <th>Đánh giá</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($guides as $guide): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($guide['avatar'])): ?>
                                                <img src="<?= htmlspecialchars($guide['avatar']) ?>"
                                                    alt="Avatar"
                                                    class="rounded-circle"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($guide['full_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($guide['email'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($guide['phone'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($guide['languages'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($guide['experience_years'] ?? 0) ?> năm</td>
                                        <td>
                                            <?php
                                            $rating = $guide['rating'] ?? 0;
                                            for ($i = 1; $i <= 5; $i++):
                                                if ($i <= $rating): ?>
                                                    <i class="fas fa-star text-warning"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star text-warning"></i>
                                            <?php endif;
                                            endfor; ?>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL_ADMIN . '&action=guides/detail&id=' . $guide['id'] ?>"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= BASE_URL_ADMIN . '&action=guides/edit&id=' . $guide['id'] ?>"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= BASE_URL_ADMIN . '&action=guides/delete&id=' . $guide['id'] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Xóa"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa hướng dẫn viên này?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Chưa có hướng dẫn viên nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
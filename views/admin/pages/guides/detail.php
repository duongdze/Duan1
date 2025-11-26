<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Chi tiết Hướng Dẫn Viên</h1>
                <p class="text-muted">Thông tin chi tiết về hướng dẫn viên</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL_ADMIN . '&action=guides/edit&id=' . $guide['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="<?= BASE_URL_ADMIN . '&action=guides' ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
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

        <div class="row g-3">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- Ảnh đại diện -->
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <?php if (!empty($guide['avatar'])): ?>
                            <img src="<?= htmlspecialchars($guide['avatar']) ?>"
                                alt="Avatar"
                                class="rounded-circle mb-3"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-4x text-white"></i>
                            </div>
                        <?php endif; ?>
                        <h4><?= htmlspecialchars($guide['full_name']) ?></h4>
                        <p class="text-muted mb-2">Hướng dẫn viên</p>

                        <!-- Rating -->
                        <div class="mb-2">
                            <?php
                            $rating = $guide['rating'] ?? 0;
                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= $rating): ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php else: ?>
                                    <i class="far fa-star text-warning"></i>
                            <?php endif;
                            endfor; ?>
                            <span class="text-muted">(<?= number_format($rating, 1) ?>)</span>
                        </div>
                    </div>
                </div>

                <!-- Thông tin liên hệ -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-address-card"></i> Thông tin liên hệ
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="width: 40%;"><i class="fas fa-envelope"></i> Email:</td>
                                    <td><?= htmlspecialchars($guide['email'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold"><i class="fas fa-phone"></i> Điện thoại:</td>
                                    <td><?= htmlspecialchars($guide['phone'] ?? 'N/A') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-8">
                <!-- Thông tin chuyên môn -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-briefcase"></i> Thông tin chuyên môn
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Ngôn ngữ sử dụng:</label>
                                <p><?= htmlspecialchars($guide['languages'] ?? 'N/A') ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Kinh nghiệm:</label>
                                <p><?= htmlspecialchars($guide['experience_years'] ?? 0) ?> năm</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Tình trạng sức khỏe:</label>
                                <p><?= htmlspecialchars($guide['health_status'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ghi chú -->
                <?php if (!empty($guide['notes'])): ?>
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-sticky-note"></i> Ghi chú
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($guide['notes'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
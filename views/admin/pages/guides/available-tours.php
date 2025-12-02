<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">
                <i class="fas fa-map-marked-alt"></i> Tour Khả Dụng
            </h1>
            <p class="text-muted">Danh sách tour chưa có HDV phụ trách - Bạn có thể nhận tour để quản lý</p>
        </div>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($availableTours)): ?>
            <div class="row g-3">
                <?php foreach ($availableTours as $tour): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    <i class="fas fa-route"></i>
                                    <?= htmlspecialchars($tour['name']) ?>
                                </h5>

                                <p class="card-text text-muted small mb-3">
                                    <?= htmlspecialchars(substr($tour['description'] ?? 'Không có mô tả', 0, 100)) ?>
                                    <?php if (strlen($tour['description'] ?? '') > 100): ?>...<?php endif; ?>
                                </p>

                                <div class="mb-2">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-calendar-alt text-info"></i>
                                        <strong>Booking gần nhất:</strong>
                                        <?php if ($tour['nearest_booking_date']): ?>
                                            <?= date('d/m/Y', strtotime($tour['nearest_booking_date'])) ?>
                                        <?php else: ?>
                                            <span class="text-warning">Chưa có booking</span>
                                        <?php endif; ?>
                                    </small>
                                </div>

                                <div class="mb-3 d-flex gap-2 flex-wrap">
                                    <span class="badge bg-info">
                                        <i class="fas fa-users"></i>
                                        <?= $tour['booking_count'] ?> booking
                                    </span>
                                    <span class="badge bg-success">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <?= number_format($tour['base_price'], 0, ',', '.') ?> ₫
                                    </span>
                                    <?php if ($tour['duration'] ?? null): ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-clock"></i>
                                            <?= $tour['duration'] ?> ngày
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <a href="<?= BASE_URL_ADMIN ?>&action=guides/tour-bookings&tour_id=<?= $tour['id'] ?>"
                                    class="btn btn-primary w-100">
                                    <i class="fas fa-eye me-2"></i>
                                    Xem Booking (<?= $tour['booking_count'] ?>)
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">Không có tour khả dụng</h5>
                    <p class="mb-0">Hiện tại tất cả tour đã có HDV phụ trách. Vui lòng quay lại sau.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="<?= BASE_URL ?>assets/admin/js/tour-claim.js"></script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
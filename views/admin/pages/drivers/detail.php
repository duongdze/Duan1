<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Calculate stats (you may need to pass these from controller)
$totalTrips = $driver['total_trips'] ?? 0;
$rating = $driver['rating'] ?? 4.5;
?>

<main class="dashboard driver-detail-page">
    <div class="dashboard-container">
        <!-- Modern Page Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="breadcrumb-modern">
                        <a href="<?= BASE_URL_ADMIN ?>&action=/" class="breadcrumb-link">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <a href="<?= BASE_URL_ADMIN ?>&action=drivers" class="breadcrumb-link">
                            <i class="fas fa-car"></i>
                            <span>Quản lý Tài xế</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current">Chi tiết Tài xế</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-user-circle title-icon"></i>
                            <?= htmlspecialchars($driver['full_name']) ?>
                        </h1>
                        <p class="page-subtitle">Tài xế - <?= htmlspecialchars($driver['vehicle_plate'] ?? 'Chưa có xe') ?></p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= BASE_URL_ADMIN ?>&action=drivers/edit&id=<?= $driver['id'] ?>" class="btn btn-modern btn-secondary">
                        <i class="fas fa-edit me-2"></i>
                        Chỉnh sửa
                    </a>
                    <a href="<?= BASE_URL_ADMIN ?>&action=drivers" class="btn btn-modern btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </header>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-modern alert-success alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span><?= $_SESSION['success'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-modern alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?= $_SESSION['error'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-route"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $totalTrips ?></div>
                        <div class="stat-label">Tổng chuyến</div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= htmlspecialchars($driver['vehicle_type'] ?? 'N/A') ?></div>
                        <div class="stat-label">Loại xe</div>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= number_format($rating, 1) ?></div>
                        <div class="stat-label">Đánh giá</div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">Hoạt động</div>
                        <div class="stat-label">Trạng thái</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Main Column (Left) -->
            <div class="col-lg-8">
                <!-- Personal Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user text-primary me-2"></i>
                            Thông tin cá nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Họ và tên</label>
                                    <div class="info-value"><?= htmlspecialchars($driver['full_name']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Email</label>
                                    <div class="info-value"><?= htmlspecialchars($driver['email'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Số điện thoại</label>
                                    <div class="info-value"><?= htmlspecialchars($driver['phone'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Địa chỉ</label>
                                    <div class="info-value"><?= htmlspecialchars($driver['address'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-car text-success me-2"></i>
                            Thông tin phương tiện
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Biển số xe</label>
                                    <div class="info-value fw-bold text-primary"><?= htmlspecialchars($driver['vehicle_plate'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Loại xe</label>
                                    <div class="info-value"><?= htmlspecialchars($driver['vehicle_type'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Hiệu xe</label>
                                    <div class="info-value"><?= htmlspecialchars($driver['vehicle_model'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Năm sản xuất</label>
                                    <div class="info-value"><?= htmlspecialchars($driver['vehicle_year'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- License Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-id-card text-warning me-2"></i>
                            Thông tin bằng lái
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Số bằng lái</label>
                                    <div class="info-value"><?= htmlspecialchars($driver['license_number'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Loại bằng lái</label>
                                    <div class="info-value">
                                        <?php if (!empty($driver['license_type'])): ?>
                                            <span class="badge bg-info"><?= htmlspecialchars($driver['license_type']) ?></span>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Ngày hết hạn</label>
                                    <div class="info-value">
                                        <?php if (!empty($driver['license_expiry'])): ?>
                                            <?= date('d/m/Y', strtotime($driver['license_expiry'])) ?>
                                            <?php
                                            $daysLeft = (strtotime($driver['license_expiry']) - time()) / 86400;
                                            if ($daysLeft < 30 && $daysLeft > 0): ?>
                                                <span class="badge bg-warning ms-2">Sắp hết hạn</span>
                                            <?php elseif ($daysLeft <= 0): ?>
                                                <span class="badge bg-danger ms-2">Đã hết hạn</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                <?php if (!empty($driver['notes'])): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-comment text-info me-2"></i>
                                Ghi chú
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($driver['notes'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar (Right) -->
            <div class="col-lg-4">
                <!-- Contact Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-address-card text-primary me-2"></i>
                            Thông tin liên hệ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="avatar-placeholder rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                style="width: 120px; height: 120px; background: #e9ecef;">
                                <i class="fas fa-user fa-4x text-muted"></i>
                            </div>
                            <h5><?= htmlspecialchars($driver['full_name']) ?></h5>
                            <p class="text-muted mb-2">Tài xế</p>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $rating ? 'text-warning' : 'text-muted' ?>"></i>
                                <?php endfor; ?>
                                <span class="text-muted ms-1">(<?= number_format($rating, 1) ?>)</span>
                            </div>
                        </div>
                        <hr>
                        <div class="contact-item mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <span><?= htmlspecialchars($driver['email'] ?? 'N/A') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-success me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Điện thoại</small>
                                    <span><?= htmlspecialchars($driver['phone'] ?? 'N/A') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Summary Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-car text-success me-2"></i>
                            Tóm tắt phương tiện
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="vehicle-summary text-center">
                            <i class="fas fa-car fa-3x text-primary mb-3"></i>
                            <h4 class="mb-2"><?= htmlspecialchars($driver['vehicle_plate'] ?? 'N/A') ?></h4>
                            <p class="text-muted mb-1"><?= htmlspecialchars($driver['vehicle_model'] ?? 'N/A') ?></p>
                            <p class="text-muted mb-0"><?= htmlspecialchars($driver['vehicle_type'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Thao tác nhanh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= BASE_URL_ADMIN ?>&action=drivers/edit&id=<?= $driver['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>
                                Chỉnh sửa thông tin
                            </a>
                            <a href="<?= BASE_URL_ADMIN ?>&action=bookings/create&driver_id=<?= $driver['id'] ?>" class="btn btn-success">
                                <i class="fas fa-calendar-plus me-2"></i>
                                Tạo booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .info-item {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .info-label {
        display: block;
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
    }

    .contact-item {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .vehicle-summary {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
    }

    .vehicle-summary i {
        color: white !important;
    }

    .vehicle-summary h4,
    .vehicle-summary p {
        color: white !important;
    }
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
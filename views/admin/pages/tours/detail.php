<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Decode JSON data
$pricingOptions = !empty($tour['pricing_options']) ? json_decode($tour['pricing_options'], true) : [];
$itinerarySchedule = !empty($tour['itinerary_schedule']) ? json_decode($tour['itinerary_schedule'], true) : [];
$partnerServices = !empty($tour['partner_services']) ? json_decode($tour['partner_services'], true) : [];
$galleryImages = !empty($tour['gallery_images']) ? json_decode($tour['gallery_images'], true) : [];

// Get supplier info
$supplierModel = new Supplier();
$supplier = null;
if (!empty($tour['supplier_id'])) {
    $supplier = $supplierModel->find('*', 'id = :id', ['id' => $tour['supplier_id']]);
}

// Format date
$createdDate = $tour['created_at'] ? date('d/m/Y H:i', strtotime($tour['created_at'])) : 'N/A';
$updatedDate = $tour['updated_at'] ? date('d/m/Y H:i', strtotime($tour['updated_at'])) : 'N/A';

// Type labels
$typeLabels = [
    'trong_nuoc' => 'Tour Trong Nước',
    'quoc_te' => 'Tour Quốc Tế',
    'theo_yeu_cau' => 'Tour Theo Yêu Cầu'
];
$typeLabel = $typeLabels[$tour['type']] ?? 'Tour';
$typeBadgeColor = match ($tour['type']) {
    'trong_nuoc' => 'success',
    'quoc_te' => 'info',
    'theo_yeu_cau' => 'warning',
    default => 'secondary'
};
?>

<style>
    .tour-detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 0.5rem;
    }

    .tour-image {
        max-height: 400px;
        object-fit: cover;
        border-radius: 0.5rem;
        width: 100%;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
        height: 150px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.05);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-section {
        margin-bottom: 2rem;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: #212529;
        margin-top: 0.25rem;
        font-size: 1rem;
    }

    .pricing-card {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .pricing-card .price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .itinerary-item {
        background: #f8f9fa;
        border-left: 4px solid #764ba2;
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .itinerary-day {
        font-size: 0.9rem;
        font-weight: 600;
        color: #764ba2;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .itinerary-time {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .itinerary-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
    }

    .partner-item {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .partner-service-badge {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .policy-text {
        line-height: 1.8;
        color: #495057;
    }

    .description-content {
        line-height: 1.8;
        color: #495057;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* Fixed Sidebar Layout */
    .tour-layout-wrapper {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 1.5rem;
        padding: 0;
    }
    .tour-detail-container {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }

    .tour-sidebar-fixed {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        height: fit-content;
    }

    .tour-content-main {
        padding-right: 20px;
    }

    /* Responsive Layout */
    @media (max-width: 1200px) {
        .tour-layout-wrapper {
            grid-template-columns: 1fr;
        }

        .tour-sidebar-fixed {
            position: relative;
            right: auto;
            top: auto;
            width: 100%;
            max-height: none;
        }

        .tour-content-main {
            padding-right: 0;
        }
    }

    @media (max-width: 768px) {
        .tour-detail-header {
            padding: 2rem 0;
        }

        .action-buttons {
            justify-content: flex-start;
        }

        .tour-image {
            max-height: 300px;
        }

        .tour-layout-wrapper {
            padding: 0 0.5rem;
        }
    }
</style>

<main class="wrapper">
    <div class="main-content">
        <!-- Header -->
        <div class="tour-detail-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="h2 mb-2"><?= htmlspecialchars($tour['name']) ?></h1>
                        <p class="mb-0">
                            <span class="badge bg-light text-<?= $typeBadgeColor ?>"><?= $typeLabel ?></span>
                        </p>
                    </div>
                    <div class="action-buttons">
                        <a href="<?= BASE_URL_ADMIN . '&action=tours/edit&id=' . $tour['id'] ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <a href="<?= BASE_URL_ADMIN . '&action=tours' ?>" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid tour-detail-container">
            <div class="tour-layout-wrapper">
                <!-- Main Content -->
                <div class="tour-content-main">
                    <!-- Ảnh (Hero + Thumbnails) - luôn hiển thị khung, dùng ảnh nếu có, placeholder nếu không -->
                    <div class="card mb-4">
                        <div class="card-body p-0">
                            <div class="collage" style="padding:8px; display:grid; grid-template-columns: 1fr 320px; gap:8px; align-items:start;">
                                <div class="hero" style="overflow:hidden; border-radius:6px; min-height:360px;">
                                    <?php if (!empty($tour['image'])): ?>
                                        <img src="<?= htmlspecialchars($tour['image']) ?>" alt="<?= htmlspecialchars($tour['name']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
                                    <?php else: ?>
                                        <div class="image-placeholder" style="width:100%; height:100%; background:#e9ecef; display:flex; align-items:center; justify-content:center; color:#6c757d; font-weight:600;">Ảnh đại diện chưa có</div>
                                    <?php endif; ?>
                                </div>

                                <div class="thumbs" style="display:grid; grid-auto-rows:120px; gap:6px;">
                                    <?php
                                    // Prepare thumbnails (exclude hero if present)
                                    $thumbs = array_values(array_filter($galleryImages));
                                    if (!empty($tour['image'])) {
                                        $filtered = [];
                                        foreach ($thumbs as $t) {
                                            if ($t !== $tour['image']) {
                                                $filtered[] = $t;
                                            }
                                        }
                                        $thumbs = array_values($filtered);
                                    }
                                    // Render up to 4 thumbnails (placeholders if missing)
                                    for ($i = 0; $i < 4; $i++) {
                                        $src = $thumbs[$i] ?? null;
                                    ?>
                                        <div class="thumb" style="overflow:hidden; border-radius:6px; background:#f8f9fa; display:flex; align-items:center; justify-content:center;">
                                            <?php if ($src): ?>
                                                <img src="<?= htmlspecialchars($src) ?>" alt="thumb<?= $i ?>" style="width:100%; height:100%; object-fit:cover; display:block;" onerror="this.style.display='none'">
                                            <?php else: ?>
                                                <div style="color:#6c757d; font-size:0.9rem;">Chưa có ảnh</div>
                                            <?php endif; ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <?php if (!empty($tour['description'])): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-align-left"></i> Mô tả tour</h5>
                            </div>
                            <div class="card-body">
                                <div class="description-content">
                                    <?= $tour['description'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Gói giá -->
                    <?php if (!empty($pricingOptions)): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-tags"></i> Gói giá & Dịch vụ</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($pricingOptions as $pricing): ?>
                                    <div class="pricing-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($pricing['label'] ?? 'Gói không tên') ?></h6>
                                                <?php if (!empty($pricing['price'])): ?>
                                                    <div class="price"><?= number_format((float)$pricing['price'], 0, ',', '.') ?> VNĐ</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($pricing['description'])): ?>
                                            <p class="mb-0 text-muted small">
                                                <strong>Dịch vụ:</strong> <?= htmlspecialchars($pricing['description']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Lịch trình -->
                    <?php if (!empty($itinerarySchedule)): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Lịch trình chi tiết</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($itinerarySchedule as $index => $item): ?>
                                    <div class="itinerary-item">
                                        <div class="itinerary-day">
                                            <?= htmlspecialchars($item['day'] ?? 'Ngày ' . ($index + 1)) ?>
                                        </div>
                                        <?php if (!empty($item['time_start']) || !empty($item['time_end'])): ?>
                                            <div class="itinerary-time">
                                                <i class="fas fa-clock"></i>
                                                <?php if (!empty($item['time_start'])): ?>
                                                    <?= htmlspecialchars($item['time_start']) ?>
                                                    <?php if (!empty($item['time_end'])): ?>
                                                        - <?= htmlspecialchars($item['time_end']) ?>
                                                    <?php endif; ?>
                                                <?php elseif (!empty($item['time_end'])): ?>
                                                    Đến <?= htmlspecialchars($item['time_end']) ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="itinerary-title"><?= htmlspecialchars($item['title'] ?? '') ?></div>
                                        <?php if (!empty($item['description'])): ?>
                                            <p class="mb-0 text-muted"><?= htmlspecialchars($item['description']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Đối tác dịch vụ -->
                    <?php if (!empty($partnerServices)): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-handshake"></i> Đối tác cung cấp dịch vụ</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($partnerServices as $partner): ?>
                                    <div class="partner-item">
                                        <div class="mb-2">
                                            <span class="partner-service-badge">
                                                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($partner['service_type'] ?? 'Dịch vụ') ?>
                                            </span>
                                        </div>
                                        <h6 class="mb-1"><?= htmlspecialchars($partner['name'] ?? 'N/A') ?></h6>
                                        <?php if (!empty($partner['contact'])): ?>
                                            <p class="mb-1 text-muted small">
                                                <strong>Liên hệ:</strong> <?= htmlspecialchars($partner['contact']) ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($partner['notes'])): ?>
                                            <p class="mb-0 text-muted small">
                                                <strong>Ghi chú:</strong> <?= htmlspecialchars($partner['notes']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Chính sách -->
                    <?php if (!empty($tour['policy'])): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-file-contract"></i> Chính sách & Điều khoản</h5>
                            </div>
                            <div class="card-body">
                                <div class="policy-text">
                                    <?= $tour['policy'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar Info -->
                <div class="tour-sidebar-fixed">
                    <!-- Thông tin cơ bản -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin cơ bản</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-section">
                                <div class="info-label">Mã tour</div>
                                <div class="info-value">#<?= htmlspecialchars($tour['id']) ?></div>
                            </div>

                            <div class="info-section">
                                <div class="info-label">Loại tour</div>
                                <div class="info-value">
                                    <span class="badge bg-<?= $typeBadgeColor ?> text-white"><?= $typeLabel ?></span>
                                </div>
                            </div>

                            <div class="info-section">
                                <div class="info-label">Giá cơ bản</div>
                                <div class="info-value" style="font-size: 1.3rem; color: #667eea; font-weight: 700;">
                                    <?= number_format((float)$tour['base_price'], 0, ',', '.') ?> VNĐ
                                </div>
                            </div>

                            <?php if (!empty($supplier)): ?>
                                <div class="info-section">
                                    <div class="info-label">Nhà cung cấp</div>
                                    <div class="info-value">
                                        <i class="fas fa-building text-muted me-2"></i>
                                        <?= htmlspecialchars($supplier['name'] ?? 'N/A') ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <hr class="my-3">

                            <div class="info-section">
                                <div class="info-label">Ngày tạo</div>
                                <div class="info-value text-muted">
                                    <i class="fas fa-calendar-plus me-2"></i><?= $createdDate ?>
                                </div>
                            </div>

                            <div class="info-section">
                                <div class="info-label">Lần cập nhật cuối</div>
                                <div class="info-value text-muted">
                                    <i class="fas fa-sync-alt me-2"></i><?= $updatedDate ?>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL_ADMIN . '&action=tours/edit&id=' . $tour['id'] ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                                <a href="<?= BASE_URL_ADMIN . '&action=tours/delete&id=' . $tour['id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Bạn chắc chắn muốn xóa tour này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Thống kê -->
                    <?php
                    // Get booking and version statistics
                    $bookingModel = new Booking();
                    $tourVersionModel = new TourVersion();
                    $bookings = $bookingModel->select('*', 'tour_id = :tour_id', ['tour_id' => $tour['id']]);
                    $versions = $tourVersionModel->select('*', 'tour_id = :tour_id', ['tour_id' => $tour['id']]);
                    $bookingCount = count($bookings) ?? 0;
                    $versionCount = count($versions) ?? 0;
                    ?>
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-bar-chart"></i> Thống kê</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <div class="stat-value" style="font-size: 1.8rem; font-weight: 700; color: #667eea;">
                                            <?= $versionCount ?>
                                        </div>
                                        <div class="stat-label text-muted small">Phiên bản</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <div class="stat-value" style="font-size: 1.8rem; font-weight: 700; color: #764ba2;">
                                            <?= $bookingCount ?>
                                        </div>
                                        <div class="stat-label text-muted small">Đơn đặt</div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL_ADMIN . '&action=tours/versions&id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-layer-group"></i> Xem phiên bản
                                </a>
                                <a href="<?= BASE_URL_ADMIN . '&action=bookings&tour_id=' . $tour['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-list"></i> Xem đơn đặt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
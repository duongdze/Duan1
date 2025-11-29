<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$tour = $tour ?? null;
$allImages = $allImages ?? [];
$pricingOptions = $pricingOptions ?? [];
$itinerarySchedule = $itinerarySchedule ?? [];
$partnerServices = $partnerServices ?? [];
$versions = $versions ?? [];
$policies = $assignedPolicies ?? [];

// Helper for price formatting
function formatPrice($price)
{
    if ($price >= 1000000000) {
        return round($price / 1000000000, ($price / 1000000000) >= 10 ? 0 : 1) . ' tỷ';
    } elseif ($price >= 1000000) {
        return round($price / 1000000, 1) . ' tr';
    } else {
        return number_format($price, 0, ',', '.') . 'đ';
    }
}

$mainImage = !empty($tour['main_image']) ?
    ((strpos($tour['main_image'], 'http') === 0) ? $tour['main_image'] : BASE_ASSETS_UPLOADS . $tour['main_image']) :
    BASE_URL . 'assets/admin/image/no-image.png';

// Prepare gallery URLs for lightbox
$galleryUrls = [];
foreach ($allImages as $img) {
    $url = $img['url'] ?? '';
    if ($url) {
        $galleryUrls[] = (strpos($url, 'http') === 0) ? $url : BASE_ASSETS_UPLOADS . $url;
    }
}
if (empty($galleryUrls) && !empty($tour['main_image'])) {
    $galleryUrls[] = $mainImage;
}
?>

<main class="wrapper">
    <div class="main-content">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-modern mb-4" aria-label="breadcrumb">
            <a href="<?= BASE_URL_ADMIN ?>&action=dashboard">Dashboard</a>
            <span class="separator">/</span>
            <a href="<?= BASE_URL_ADMIN ?>&action=tours">Quản lý Tour</a>
            <span class="separator">/</span>
            <span class="active">Chi tiết Tour</span>
        </nav>

        <!-- Page Header -->
        <div class="page-header-modern">
            <div>
                <h1 class="h2 page-title"><?= htmlspecialchars($tour['name'] ?? 'Tên Tour') ?></h1>
                <p class="text-muted lead"><?= htmlspecialchars($tour['category_name'] ?? 'Chưa có danh mục') ?></p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL_ADMIN ?>&action=tours/edit&id=<?= $tour['id'] ?>" class="btn-modern btn-secondary">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="<?= BASE_URL_ADMIN ?>&action=bookings/create&tour_id=<?= $tour['id'] ?>" class="btn-modern btn-primary-gradient">
                    <i class="fas fa-calendar-plus"></i> Tạo Booking
                </a>
            </div>
        </div>

        <!-- Key Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-primary"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Giá gốc</div>
                        <div class="stat-value"><?= formatPrice($tour['base_price'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-success"><i class="fas fa-layer-group"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Số phiên bản</div>
                        <div class="stat-value"><?= count($versions) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-warning"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Số ngày</div>
                        <div class="stat-value"><?= count($itinerarySchedule) ?> ngày</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-danger"><i class="fas fa-star"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Đánh giá</div>
                        <div class="stat-value">4.5 <span class="small text-muted">(12)</span></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row g-4">
            <!-- Main Column (Left) -->
            <div class="col-lg-8">
                <!-- Description Card -->
                <div class="card-modern">
                    <div class="card-header">
                        <h3 class="card-title-modern"><i class="fas fa-file-alt text-primary"></i> Mô tả chi tiết</h3>
                    </div>
                    <div class="card-body">
                        <p><?= !empty($tour['description']) ? nl2br(htmlspecialchars($tour['description'])) : '<span class="text-muted">Chưa có mô tả chi tiết cho tour này.</span>' ?></p>
                    </div>
                </div>

                <!-- Itinerary Card -->
                <div class="card-modern">
                    <div class="card-header">
                        <h3 class="card-title-modern"><i class="fas fa-map-signs text-success"></i> Lịch trình Tour</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($itinerarySchedule)): ?>
                            <div class="itinerary-timeline">
                                <?php foreach ($itinerarySchedule as $item): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-day"><span><?= htmlspecialchars($item['day_label'] ?? 'Ngày ' . $item['day_number']) ?></span></div>
                                        <div class="timeline-content">
                                            <h5 class="timeline-title"><?= htmlspecialchars($item['title'] ?? '') ?></h5>
                                            <p class="timeline-description text-muted"><?= htmlspecialchars($item['description'] ?? '') ?></p>
                                            <?php if (!empty($item['time_start'])): ?>
                                                <small class="text-muted"><i class="fas fa-clock"></i> <?= date('H:i', strtotime($item['time_start'])) ?> - <?= date('H:i', strtotime($item['time_end'])) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-map-marked-alt"></i></div>
                                <div class="empty-state-title">Chưa có lịch trình</div>
                                <p class="empty-state-description">Lịch trình chi tiết cho tour này chưa được cập nhật.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Versions Card -->
                <div class="card-modern">
                    <div class="card-header">
                        <h3 class="card-title-modern"><i class="fas fa-layer-group text-info"></i> Các phiên bản & Lịch khởi hành</h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($versions)): ?>
                            <div class="table-responsive">
                                <table class="table table-modern table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tên phiên bản</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                            <th class="text-end">Giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($versions as $version): ?>
                                            <tr>
                                                <td class="fw-medium"><?= htmlspecialchars($version['name']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($version['start_date'])) ?></td>
                                                <td><?= date('d/m/Y', strtotime($version['end_date'])) ?></td>
                                                <td class="text-end fw-bold text-primary"><?= formatPrice($version['price'] ?? $tour['base_price']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state p-4">
                                <div class="empty-state-icon"><i class="fas fa-layer-group"></i></div>
                                <div class="empty-state-title">Chưa có phiên bản</div>
                                <p class="empty-state-description">Hiện chưa có phiên bản hoặc lịch khởi hành nào cho tour này.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Gallery Card -->
                <div class="card-modern">
                    <div class="card-header">
                        <h3 class="card-title-modern"><i class="fas fa-images text-danger"></i> Thư viện ảnh</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($galleryUrls)): ?>
                            <div class="row g-3" id="tour-gallery" data-gallery='<?= json_encode($galleryUrls) ?>'>
                                <?php foreach ($galleryUrls as $index => $url): ?>
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="ratio ratio-1x1 rounded overflow-hidden shadow-sm cursor-pointer gallery-item" onclick="openLightbox(<?= $index ?>)">
                                            <img src="<?= $url ?>" class="w-100 h-100 object-fit-cover transition-transform hover-scale" alt="Tour Gallery Image <?= $index + 1 ?>">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="fas fa-images"></i></div>
                                <div class="empty-state-title">Thư viện trống</div>
                                <p class="empty-state-description">Chưa có hình ảnh nào trong thư viện.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right) -->
            <div class="col-lg-4">
                <div class="sidebar-widget">
                    <div class="widget-title">Ảnh đại diện</div>
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm">
                        <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($tour['name'] ?? '') ?>" class="w-100 h-100 object-fit-cover">
                    </div>
                </div>

                <?php if (!empty($partnerServices)): ?>
                    <div class="sidebar-widget">
                        <div class="widget-title">Đối tác dịch vụ</div>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($partnerServices as $partner): ?>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="partner-icon">
                                        <?php
                                        $icon = match ($partner['service_type'] ?? '') {
                                            'hotel' => 'fa-hotel',
                                            'transport' => 'fa-bus',
                                            'restaurant' => 'fa-utensils',
                                            'guide' => 'fa-user-tie',
                                            default => 'fa-handshake'
                                        };
                                        ?>
                                        <i class="fas <?= $icon ?>"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small"><?= htmlspecialchars($partner['name']) ?></div>
                                        <div class="text-muted small" style="font-size: 0.75rem;"><?= htmlspecialchars($partner['contact']) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>


<style>
    :root {
        --card-modern-bg: #fff;
        --card-modern-border-color: #e9ecef;
        --card-modern-header-bg: #f8f9fa;
        --text-muted-light: #86909c;
    }

    .card-modern {
        background-color: var(--card-modern-bg);
        border: 1px solid var(--card-modern-border-color);
        border-radius: .75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .07), 0 2px 4px -2px rgba(0, 0, 0, .07);
        margin-bottom: 2rem;
    }

    .card-modern .card-header {
        background-color: var(--card-modern-header-bg);
        border-bottom: 1px solid var(--card-modern-border-color);
        padding: 1rem 1.5rem;
        border-top-left-radius: .75rem;
        border-top-right-radius: .75rem;
    }

    .card-title-modern {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .card-title-modern .fas {
        font-size: 1rem;
    }

    .card-modern .card-body {
        padding: 1.5rem;
    }

    .table-modern {
        margin-bottom: 0;
    }

    .table-modern thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: .8rem;
        letter-spacing: .5px;
        padding: 1rem;
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    .table-modern.table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-modern td {
        vertical-align: middle;
        padding: 1rem;
    }

    .stat-card {
        background-color: var(--card-modern-bg);
        border: 1px solid var(--card-modern-border-color);
        border-radius: .75rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .07), 0 2px 4px -2px rgba(0, 0, 0, .07);
    }

    .stat-icon {
        font-size: 2rem;
    }

    .stat-label {
        font-size: .9rem;
        color: var(--text-muted-light);
        margin-bottom: .25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .itinerary-timeline {
        position: relative;
        padding-left: 2rem;
        border-left: 3px solid #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-day {
        position: absolute;
        left: -2rem;
        top: -5px;
        transform: translateX(-50%);
        width: 2rem;
        height: 2rem;
        background-color: var(--bs-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        text-align: center;
        font-size: 0.8rem;
        line-height: 1.2;
        border: 3px solid #fff;
        z-index: 1;
    }

    .timeline-day span {
        max-width: 90%;
    }

    .timeline-content {
        padding-left: 3rem;
    }

    .timeline-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: .5rem;
    }

    .timeline-description {
        margin-bottom: .5rem;
    }

    .sidebar-widget {
        background-color: var(--card-modern-bg);
        border: 1px solid var(--card-modern-border-color);
        border-radius: .75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .07), 0 2px 4px -2px rgba(0, 0, 0, .07);
        margin-bottom: 2rem;
        padding: 1.5rem;
    }

    .sidebar-widget .widget-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: .5rem;
        border-bottom: 1px solid var(--card-modern-border-color);
    }

    .hover-scale {
        transition: transform 0.3s ease;
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }

    .policy-item-sidebar {
        margin-bottom: 1rem;
    }

    .policy-item-sidebar:last-child {
        margin-bottom: 0;
    }

    .policy-item-sidebar .policy-name {
        font-weight: 600;
        font-size: .9rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .policy-item-sidebar .policy-description {
        color: var(--text-muted-light);
        padding-left: 1.4rem;
    }

    .partner-icon {
        font-size: 1.2rem;
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #e9ecef;
        color: var(--bs-primary);
    }
</style>

<script>
    // Simple lightbox function (reusing existing logic if available, or simple implementation)
    function openLightbox(index) {
        // Trigger the existing lightbox logic if present in tours.js
        // Or implement a simple one here if needed
        const gallery = document.getElementById('tour-gallery');
        if (gallery && window.tourLightbox) {
            const galleryData = JSON.parse(gallery.dataset.gallery || '[]');
            window.tourLightbox.open(galleryData, index);
        }
    }
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
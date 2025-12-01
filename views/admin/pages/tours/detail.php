<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$tour = $tour ?? null;
$allImages = $allImages ?? [];
$pricingOptions = $pricingOptions ?? [];
$itinerarySchedule = $itinerarySchedule ?? [];
$partnerServices = $partnerServices ?? [];
$versions = $versions ?? [];
$policies = $policies ?? [];

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

// Find main image from $allImages
$mainImage = BASE_URL . 'assets/admin/image/no-image.png';
foreach ($allImages as $img) {
    if (!empty($img['main'])) {
        $mainImage = $img['url'] ?? BASE_URL . 'assets/admin/image/no-image.png';
        break;
    }
}

// Prepare gallery URLs for lightbox
$galleryUrls = [];
foreach ($allImages as $img) {
    $url = $img['url'] ?? '';
    if ($url) {
        $galleryUrls[] = $url;
    }
}
if (empty($galleryUrls)) {
    $galleryUrls[] = $mainImage;
}
?>

<main class="dashboard tour-detail-page">
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
                        <a href="<?= BASE_URL_ADMIN ?>&action=tours" class="breadcrumb-link">
                            <i class="fas fa-route"></i>
                            <span>Quản lý Tour</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current">Chi tiết Tour</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-route title-icon"></i>
                            <?= htmlspecialchars($tour['name'] ?? 'Tên Tour') ?>
                        </h1>
                        <p class="page-subtitle"><?= htmlspecialchars($tour['category_name'] ?? 'Chưa có danh mục') ?></p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= BASE_URL_ADMIN ?>&action=tours/edit&id=<?= $tour['id'] ?>" class="btn btn-modern btn-secondary">
                        <i class="fas fa-edit me-2"></i>
                        Chỉnh sửa
                    </a>
                    <a href="<?= BASE_URL_ADMIN ?>&action=bookings/create&tour_id=<?= $tour['id'] ?>" class="btn btn-modern btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Tạo Booking
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
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= formatPrice($tour['base_price'] ?? 0) ?></div>
                        <div class="stat-label">Giá gốc</div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= count($versions) ?></div>
                        <div class="stat-label">Lịch khởi hành</div>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= count($itinerarySchedule) ?></div>
                        <div class="stat-label">Số ngày</div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">4.5</div>
                        <div class="stat-label">Đánh giá</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="row">
            <!-- Main Column (Left) -->
            <div class="col-lg-8">
                <!-- Description Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt text-primary me-2"></i>
                            Mô tả chi tiết
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($tour['description'])): ?>
                            <div class="description-content">
                                <?= nl2br(htmlspecialchars($tour['description'])) ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                <h6>Chưa có mô tả</h6>
                                <p class="mb-0">Mô tả chi tiết cho tour này chưa được cập nhật.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Itinerary Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-signs text-success me-2"></i>
                            Lịch trình Tour
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($itinerarySchedule)): ?>
                            <div class="itinerary-timeline">
                                <?php foreach ($itinerarySchedule as $index => $item): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-day">
                                            <?= htmlspecialchars($item['day_label'] ?? 'N' . ($index + 1)) ?>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                <?= htmlspecialchars($item['title'] ?? 'Lịch trình ngày ' . ($index + 1)) ?>
                                            </h6>
                                            <p class="timeline-description">
                                                <?= htmlspecialchars($item['description'] ?? '') ?>
                                            </p>
                                            <?php if (!empty($item['time_start'])): ?>
                                                <div class="timeline-time">
                                                    <i class="fas fa-clock"></i>
                                                    <?= date('H:i', strtotime($item['time_start'])) ?> -
                                                    <?= date('H:i', strtotime($item['time_end'])) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                                <h6>Chưa có lịch trình</h6>
                                <p class="mb-0">Lịch trình chi tiết cho tour này chưa được cập nhật.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Versions Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt text-info me-2"></i>
                            Lịch khởi hành
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($versions)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ngày khởi hành</th>
                                            <th>Số chỗ</th>
                                            <th>Đã đặt</th>
                                            <th>Giá người lớn</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($versions as $version): ?>
                                            <tr>
                                                <td class="fw-medium">
                                                    <?= date('d/m/Y', strtotime($version['departure_date'])) ?>
                                                </td>
                                                <td>
                                                    <?= $version['max_seats'] ?? 'N/A' ?>
                                                </td>
                                                <td>
                                                    <?= $version['booked_seats'] ?? 0 ?>
                                                </td>
                                                <td class="text-end fw-bold text-primary">
                                                    <?= formatPrice($version['price_adult'] ?? $tour['base_price']) ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $version['status'] === 'open' ? 'success' : ($version['status'] === 'full' ? 'danger' : 'warning') ?>">
                                                        <?= ucfirst($version['status'] ?? 'unknown') ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                <h6>Chưa có lịch khởi hành</h6>
                                <p class="mb-0">Hiện chưa có lịch khởi hành nào cho tour này.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tour Policies Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            Chính sách áp dụng
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($policies)): ?>
                            <div class="row">
                                <?php foreach ($policies as $policy): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3 h-100">
                                            <h6 class="mb-2 text-primary">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <?= htmlspecialchars($policy['name']) ?>
                                            </h6>
                                            <?php if (!empty($policy['description'])): ?>
                                                <p class="mb-0 small text-muted"><?= htmlspecialchars($policy['description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                <h6>Chưa có chính sách</h6>
                                <p class="mb-0 small">Tour này chưa được gán chính sách nào.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Gallery Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-images text-primary me-2"></i>
                            Thư viện ảnh
                            <span class="badge bg-secondary ms-2"><?= count($galleryUrls) ?> ảnh</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($galleryUrls)): ?>
                            <div class="row g-3" id="tour-gallery" data-gallery='<?= json_encode($galleryUrls) ?>'>
                                <?php foreach ($galleryUrls as $index => $url): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="gallery-item-wrapper" onclick="openLightbox(<?= $index ?>)">
                                            <div class="gallery-item">
                                                <img src="<?= $url ?>" alt="Tour Gallery Image <?= $index + 1 ?>" class="img-fluid">
                                                <div class="gallery-overlay">
                                                    <div class="gallery-overlay-content">
                                                        <i class="fas fa-search-plus"></i>
                                                        <span>Xem ảnh</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gallery-caption">
                                                <small class="text-muted">Ảnh #<?= $index + 1 ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (count($galleryUrls) > 6): ?>
                                <div class="text-center mt-3">
                                    <button class="btn btn-outline-primary btn-sm" onclick="showAllImages()">
                                        <i class="fas fa-images me-2"></i>
                                        Xem tất cả <?= count($galleryUrls) ?> ảnh
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <div class="gallery-empty-state">
                                    <i class="fas fa-images fa-4x mb-3 text-muted"></i>
                                    <h6 class="text-muted">Thư viện trống</h6>
                                    <p class="text-muted mb-3">Chưa có hình ảnh nào trong thư viện.</p>
                                    <a href="<?= BASE_URL_ADMIN ?>&action=tours/edit&id=<?= $tour['id'] ?>#gallery" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-2"></i>
                                        Thêm hình ảnh
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Sidebar (Right) -->
            <div class="col-lg-4">
                <!-- Main Image Widget -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image text-primary me-2"></i>
                            Ảnh đại diện
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="main-image-container">
                            <img src="<?= $mainImage ?>" alt="Tour Main Image" class="img-fluid rounded" style="width: 100%; height: auto; object-fit: cover;">
                        </div>
                    </div>
                </div>
                <!-- Tour Version Info Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tags text-warning me-2"></i>
                            Phiên bản Tour
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($tour['version_name'])): ?>
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-info-circle me-3"></i>
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($tour['version_name']) ?></h6>
                                    <?php if (!empty($tour['version_description'])): ?>
                                        <p class="mb-0 small text-muted"><?= htmlspecialchars($tour['version_description']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                <h6>Chưa gán phiên bản</h6>
                                <p class="mb-0 small">Tour này chưa được gán phiên bản cụ thể.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <script>
                    function openLightbox(index) {
                        // Create lightbox overlay
                        const lightbox = document.createElement('div');
                        lightbox.className = 'lightbox-overlay';
                        lightbox.innerHTML = `
                        <div class="lightbox-content">
                            <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
                            <img src="${galleryData[index]}" alt="Gallery Image ${index + 1}" class="lightbox-image">
                            <div class="lightbox-controls">
                                <button class="lightbox-btn" onclick="navigateLightbox(-1)" ${index===0 ? 'disabled' : '' }>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="lightbox-counter">${index + 1} / ${galleryData.length}</span>
                                <button class="lightbox-btn" onclick="navigateLightbox(1)" ${index===galleryData.length - 1 ? 'disabled' : '' }>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        `;

                        document.body.appendChild(lightbox);
                        document.body.style.overflow = 'hidden';

                        // Store current index
                        window.currentLightboxIndex = index;
                        window.galleryData = galleryData;

                        // Close on escape key
                        document.addEventListener('keydown', handleLightboxKeydown);

                        // Close on background click
                        lightbox.addEventListener('click', function(e) {
                            if (e.target === lightbox) {
                                closeLightbox();
                            }
                        });
                    }

                    function closeLightbox() {
                        const lightbox = document.querySelector('.lightbox-overlay');
                        if (lightbox) {
                            lightbox.remove();
                            document.body.style.overflow = '';
                            document.removeEventListener('keydown', handleLightboxKeydown);
                        }
                    }

                    function navigateLightbox(direction) {
                        const newIndex = window.currentLightboxIndex + direction;
                        if (newIndex >= 0 && newIndex < window.galleryData.length) {
                            window.currentLightboxIndex = newIndex;
                            const img = document.querySelector('.lightbox-image');
                            const counter = document.querySelector('.lightbox-counter');
                            const prevBtn = document.querySelector('.lightbox-btn:first-child');
                            const nextBtn = document.querySelector('.lightbox-btn:last-child');

                            img.src = window.galleryData[newIndex];
                            counter.textContent = `${newIndex + 1} / ${window.galleryData.length}`;

                            prevBtn.disabled = newIndex === 0;
                            nextBtn.disabled = newIndex === window.galleryData.length - 1;
                        }
                    }

                    function handleLightboxKeydown(e) {
                        if (e.key === 'Escape') {
                            closeLightbox();
                        } else if (e.key === 'ArrowLeft') {
                            navigateLightbox(-1);
                        } else if (e.key === 'ArrowRight') {
                            navigateLightbox(1);
                        }
                    }
                </script>

                <style>
                    /* Gallery Styles */
                    .gallery-item-wrapper {
                        cursor: pointer;
                        transition: transform 0.3s ease;
                    }

                    .gallery-item-wrapper:hover {
                        transform: translateY(-2px);
                    }

                    .gallery-item {
                        position: relative;
                        border-radius: 8px;
                        overflow: hidden;
                        aspect-ratio: 16/9;
                        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    }

                    .gallery-item img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        transition: transform 0.3s ease;
                    }

                    .gallery-overlay {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(0, 0, 0, 0.7);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    }

                    .gallery-item-wrapper:hover .gallery-overlay {
                        opacity: 1;
                    }

                    .gallery-item-wrapper:hover img {
                        transform: scale(1.05);
                    }

                    .gallery-overlay-content {
                        text-align: center;
                        color: white;
                    }

                    .gallery-overlay-content i {
                        font-size: 24px;
                        margin-bottom: 8px;
                        display: block;
                    }

                    .gallery-overlay-content span {
                        font-size: 14px;
                        font-weight: 500;
                    }

                    .gallery-caption {
                        text-align: center;
                        margin-top: 8px;
                    }

                    .gallery-empty-state {
                        padding: 40px 20px;
                    }

                    /* Responsive Gallery */
                    @media (max-width: 768px) {
                        .gallery-item {
                            aspect-ratio: 4/3;
                        }

                        .gallery-overlay-content i {
                            font-size: 20px;
                        }

                        .gallery-overlay-content span {
                            font-size: 12px;
                        }
                    }

                    /* Lightbox Styles */
                    .lightbox-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(0, 0, 0, 0.9);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                        padding: 20px;
                    }

                    .lightbox-content {
                        position: relative;
                        max-width: 90vw;
                        max-height: 90vh;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                    }

                    .lightbox-image {
                        max-width: 100%;
                        max-height: 80vh;
                        object-fit: contain;
                        border-radius: 8px;
                    }

                    .lightbox-close {
                        position: absolute;
                        top: -40px;
                        right: 0;
                        background: none;
                        border: none;
                        color: white;
                        font-size: 32px;
                        cursor: pointer;
                        padding: 0;
                        width: 40px;
                        height: 40px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .lightbox-close:hover {
                        opacity: 0.8;
                    }

                    .lightbox-controls {
                        display: flex;
                        align-items: center;
                        gap: 20px;
                        margin-top: 20px;
                    }

                    .lightbox-btn {
                        background: rgba(255, 255, 255, 0.2);
                        border: 1px solid rgba(255, 255, 255, 0.3);
                        color: white;
                        padding: 10px 15px;
                        border-radius: 6px;
                        cursor: pointer;
                        transition: background 0.3s ease;
                    }

                    .lightbox-btn:hover:not(:disabled) {
                        background: rgba(255, 255, 255, 0.3);
                    }

                    .lightbox-btn:disabled {
                        opacity: 0.3;
                        cursor: not-allowed;
                    }

                    .lightbox-counter {
                        color: white;
                        font-size: 14px;
                        font-weight: 500;
                    }

                    @media (max-width: 768px) {
                        .lightbox-controls {
                            gap: 15px;
                        }

                        .lightbox-btn {
                            padding: 8px 12px;
                            font-size: 12px;
                        }

                        .lightbox-counter {
                            font-size: 12px;
                        }
                    }
                </style>

                <script>
                    // Initialize gallery data
                    document.addEventListener('DOMContentLoaded', function() {
                        const galleryElement = document.getElementById('tour-gallery');
                        if (galleryElement) {
                            window.galleryData = JSON.parse(galleryElement.getAttribute('data-gallery'));
                        }
                    });

                    function showAllImages() {
                        // This function can be implemented to show all images in a modal or expand the gallery
                        const galleryItems = document.querySelectorAll('.gallery-item-wrapper');
                        galleryItems.forEach(item => item.style.display = 'block');
                    }
                </script>

                <?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
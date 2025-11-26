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
function formatPrice($price) {
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

        <!-- Hero Section -->
        <div class="tour-hero mb-4 position-relative rounded-xl overflow-hidden shadow-sm" style="height: 400px;">
            <img src="<?= $mainImage ?>" alt="<?= htmlspecialchars($tour['name'] ?? '') ?>" class="w-100 h-100 object-fit-cover">
                                            <thead>
                                                <tr>
                                                    <th>Phiên bản</th>
                                                    <th>Thời gian</th>
                                                    <th class="text-end">Giá riêng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($versions as $ver): ?>
                                                    <tr>
                                                        <td class="fw-bold"><?= htmlspecialchars($ver['name']) ?></td>
                                                        <td class="small">
                                                            <?= date('d/m/Y', strtotime($ver['start_date'])) ?> - 
                                                            <?= date('d/m/Y', strtotime($ver['end_date'])) ?>
                                                        </td>
                                                        <td class="text-end">
                                                            <?php if (!empty($ver['price'])): ?>
                                                                <span class="fw-bold text-primary"><?= number_format($ver['price']) ?>đ</span>
                                                            <?php else: ?>
                                                                <span class="text-muted">Theo giá gốc</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Policies Tab -->
                                    <div class="empty-state py-5">
                                        <div class="empty-state-icon"><i class="fas fa-shield-alt"></i></div>
                                        <div class="empty-state-title">Chưa có chính sách</div>
                                        <div class="empty-state-description">Chưa có thông tin về chính sách và điều khoản.</div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Gallery Tab -->
                            <div class="tab-pane fade" id="gallery" role="tabpanel">
                                <?php if (!empty($galleryUrls)): ?>
                                    <div class="row g-3" id="tour-gallery" data-gallery='<?= json_encode($galleryUrls) ?>'>
                                        <?php foreach ($galleryUrls as $index => $url): ?>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="ratio ratio-1x1 rounded overflow-hidden shadow-sm cursor-pointer gallery-item" onclick="openLightbox(<?= $index ?>)">
                                                    <img src="<?= $url ?>" class="w-100 h-100 object-fit-cover transition-transform hover-scale">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state py-5">
                                        <div class="empty-state-icon"><i class="fas fa-images"></i></div>
                                        <div class="empty-state-title">Thư viện trống</div>
                                        <div class="empty-state-description">Chưa có hình ảnh nào trong thư viện.</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right) -->
            <div class="col-lg-4">
                <div class="sidebar-widget">
                    <div class="widget-title">Thao tác nhanh</div>
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL_ADMIN ?>&action=bookings/create&tour_id=<?= $tour['id'] ?>" class="btn-modern btn-primary-gradient">
                            <i class="fas fa-calendar-plus"></i> Tạo đặt chỗ mới
                        </a>
                        <a href="#" class="btn-modern btn-outline-secondary">
                            <i class="fas fa-share-alt"></i> Chia sẻ tour
                        </a>
                        <a href="#" class="btn-modern btn-outline-secondary">
                            <i class="fas fa-print"></i> In thông tin
                        </a>
                    </div>
                </div>

                <?php if (!empty($partnerServices)): ?>
                    <div class="sidebar-widget">
                        <div class="widget-title">Đối tác dịch vụ</div>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($partnerServices as $partner): ?>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-light p-2 text-primary">
                                        <?php
                                        $icon = match($partner['service_type'] ?? '') {
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

<script>
    // Simple lightbox function (reusing existing logic if available, or simple implementation)
    function openLightbox(index) {
        // Trigger the existing lightbox logic if present in tours.js
        // Or implement a simple one here if needed
        const gallery = document.getElementById('tour-gallery');
        if (gallery && window.createLightbox) {
            // Assuming createLightbox is global or accessible
            // This part depends on how tours.js is structured
        }
    }
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
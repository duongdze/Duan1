<?php
$tourId = $_GET['id'] ?? null;
?>
<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="wrapper">
    <div class="main-content">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-modern mb-4" aria-label="breadcrumb">
            <a href="<?= BASE_URL_ADMIN ?>&action=dashboard">Dashboard</a>
            <span class="separator">/</span>
            <a href="<?= BASE_URL_ADMIN ?>&action=tours">Quản lý Tour</a>
            <span class="separator">/</span>
            <span class="active">Chỉnh sửa Tour</span>
        </nav>

        <!-- Page Header -->
        <div class="page-header-modern">
            <div>
                <h1>Chỉnh sửa Tour</h1>
                <p class="text-muted">Cập nhật thông tin cho tour du lịch</p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL_ADMIN ?>&action=tours" class="btn-modern btn-secondary">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
                <a href="<?= BASE_URL_ADMIN ?>&action=tours/detail&id=<?= $tourId ?>" class="btn-modern btn-info-gradient text-white">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </a>
                <button type="submit" form="tour-form" class="btn-modern btn-primary-gradient">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </div>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-modern alert-error mb-4" role="alert">
                <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
                <div class="alert-content">
                    <div class="alert-title">Đã xảy ra lỗi</div>
                    <div><?= htmlspecialchars($_SESSION['error']) ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-modern alert-success mb-4" role="alert">
                <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
                <div class="alert-content">
                    <div class="alert-title">Thành công</div>
                    <div><?= htmlspecialchars($_SESSION['success']) ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=tours/update&id=<?= $tourId ?>" enctype="multipart/form-data" class="tour-form" id="tour-form">
            <!-- Hidden inputs for serialized data -->
            <input type="hidden" name="tour_pricing_options" id="tour_pricing_options">
            <input type="hidden" name="tour_dynamic_pricing" id="tour_dynamic_pricing">
            <input type="hidden" name="tour_itinerary" id="tour_itinerary">
            <input type="hidden" name="tour_partners" id="tour_partners">
            <input type="hidden" name="tour_versions" id="tour_versions">

            <div class="row g-4">
                <!-- Main Column (Left) -->
                <div class="col-lg-8">
                    <!-- 1. Thông tin cơ bản -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-info"></i></div>
                            <div class="section-title">
                                <h3>Thông tin cơ bản</h3>
                                <p>Tên tour, danh mục và mô tả tổng quan</p>
                            </div>
                        </div>

                        <div class="card-modern mb-4">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-floating-modern">
                                            <input type="text" name="name" id="name" class="form-control" required placeholder=" " value="<?= htmlspecialchars($tour['name'] ?? '') ?>">
                                            <label for="name">Tên Tour <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating-modern">
                                            <select name="category_id" id="category_id" class="form-select form-control" required>
                                                <option value="">-- Chọn danh mục --</option>
                                                <?php if (!empty($categories)): ?>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == ($tour['category_id'] ?? '')) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating-modern">
                                            <input type="number" name="base_price" id="base_price" class="form-control" required min="0" step="1000" placeholder=" " value="<?= $tour['base_price'] ?? 0 ?>">
                                            <label for="base_price">Giá cơ bản (VNĐ) <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating-modern">
                                            <textarea name="description" id="description" class="form-control" style="height: 200px" placeholder=" "><?= htmlspecialchars($tour['description'] ?? '') ?></textarea>
                                            <label for="description">Mô tả chi tiết</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Cấu hình giá -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-tags"></i></div>
                            <div class="section-title">
                                <h3>Cấu hình giá</h3>
                                <p>Các tùy chọn giá và giá theo thời điểm</p>
                            </div>
                        </div>

                        <div class="card-modern mb-4">
                            <div class="card-body">
                                <div class="dynamic-section-header">
                                    <div class="dynamic-section-title">Gói dịch vụ cơ bản</div>
                                    <button type="button" class="add-item-btn" id="add-pricing-option">
                                        <i class="fas fa-plus"></i> Thêm gói
                                    </button>
                                </div>
                                <div id="pricing-options-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($pricingOptions ?? []) ?>'></div>
                            </div>
                        </div>

                        <div class="card-modern mb-4">
                            <div class="card-body">
                                <div class="dynamic-section-header">
                                    <div class="dynamic-section-title">Điều chỉnh giá theo thời gian</div>
                                    <button type="button" class="add-item-btn" id="add-dynamic-pricing">
                                        <i class="fas fa-plus"></i> Thêm điều chỉnh
                                    </button>
                                </div>
                                <div id="dynamic-pricing-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($dynamicPricing ?? []) ?>'></div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Lịch trình -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-map-marked-alt"></i></div>
                            <div class="section-title">
                                <h3>Lịch trình Tour</h3>
                                <p>Chi tiết hoạt động từng ngày</p>
                            </div>
                        </div>

                        <div class="card-modern mb-4">
                            <div class="card-body">
                                <div class="dynamic-section-header">
                                    <div class="dynamic-section-title">Danh sách ngày</div>
                                    <button type="button" class="add-item-btn" id="add-itinerary-item">
                                        <i class="fas fa-plus"></i> Thêm ngày
                                    </button>
                                </div>
                                <div id="itinerary-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($itinerarySchedule ?? []) ?>'></div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Hình ảnh -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-images"></i></div>
                            <div class="section-title">
                                <h3>Thư viện ảnh</h3>
                                <p>Hình ảnh quảng bá cho tour</p>
                            </div>
                        </div>

                        <div class="card-modern mb-4">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ảnh chính hiện tại</label>
                                    <?php if (!empty($tour['main_image'])): ?>
                                        <div class="mb-2">
                                            <img src="<?= (strpos($tour['main_image'], 'http') === 0) ? $tour['main_image'] : BASE_ASSETS_UPLOADS . $tour['main_image'] ?>" alt="Main Image" class="img-thumbnail" style="max-height: 200px;">
                                        </div>
                                    <?php else: ?>
                                        <div class="text-muted fst-italic">Chưa có ảnh chính</div>
                                    <?php endif; ?>
                                </div>

                                <div class="image-upload-zone">
                                    <div class="upload-area" id="dropZone" onclick="document.getElementById('gallery_images').click()">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                                        <p class="mb-1">Kéo thả hình ảnh vào đây hoặc click để chọn</p>
                                        <span class="badge-modern badge-info">Hỗ trợ JPG, PNG, WEBP. Tối đa 5MB/file</span>
                                        <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*">
                                    </div>
                                    <div class="image-preview-grid" id="imagePreview">
                                        <!-- Previews will appear here -->
                                    </div>
                                </div>
                                
                                <?php if (!empty($allImages)): ?>
                                    <div class="mt-4">
                                        <h6 class="fw-bold mb-3">Thư viện ảnh hiện tại</h6>
                                        <div class="image-preview-grid">
                                            <?php foreach ($allImages as $img): ?>
                                                <div class="image-preview-card">
                                                    <img src="<?= (strpos($img['url'], 'http') === 0) ? $img['url'] : BASE_ASSETS_UPLOADS . $img['url'] ?>" alt="Gallery Image">
                                                    <div class="image-preview-overlay">
                                                        <a href="<?= BASE_URL_ADMIN ?>&action=tours/delete-image&id=<?= $img['id'] ?>&tour_id=<?= $tourId ?>" class="image-preview-action delete" title="Xóa ảnh" onclick="return confirm('Bạn có chắc muốn xóa ảnh này?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                    <div class="form-check mt-2 text-center">
                                                        <input class="form-check-input float-none" type="radio" name="primary_image_selection" value="existing_<?= $img['id'] ?>" <?= !empty($img['main']) ? 'checked' : '' ?>>
                                                        <label class="form-check-label small d-block text-muted">Ảnh đại diện</label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Chính sách & Đối tác -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-handshake"></i></div>
                            <div class="section-title">
                                <h3>Chính sách & Đối tác</h3>
                                <p>Thông tin bổ sung và quy định</p>
                            </div>
                        </div>

                        <div class="card-modern mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Chính sách áp dụng</h5>
                                <?php 
                                $assignedPolicyIds = array_map(function($p) { return $p['policy_id']; }, $assignedPolicies ?? []);
                                ?>
                                <?php if (!empty($policies)): ?>
                                    <div class="row g-3">
                                        <?php foreach ($policies as $policy): ?>
                                            <div class="col-md-6">
                                                <div class="form-check p-3 border rounded bg-light">
                                                    <input class="form-check-input" type="checkbox" name="policies[]" value="<?= $policy['id'] ?>" id="policy_<?= $policy['id'] ?>" <?= in_array($policy['id'], $assignedPolicyIds) ? 'checked' : '' ?>>
                                                    <label class="form-check-label fw-medium" for="policy_<?= $policy['id'] ?>">
                                                        <?= htmlspecialchars($policy['name']) ?>
                                                    </label>
                                                    <div class="small text-muted mt-1"><?= htmlspecialchars($policy['description']) ?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted">Chưa có chính sách nào.</div>
                                <?php endif; ?>

                                <hr class="my-4">

                                <div class="dynamic-section-header">
                                    <div class="dynamic-section-title">Đối tác dịch vụ</div>
                                    <button type="button" class="add-item-btn" id="add-partner-item">
                                        <i class="fas fa-plus"></i> Thêm đối tác
                                    </button>
                                </div>
                                <div id="partners-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($partnerServices ?? []) ?>'></div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Phiên bản Tour -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-layer-group"></i></div>
                            <div class="section-title">
                                <h3>Phiên bản Tour</h3>
                                <p>Quản lý các phiên bản/lịch khởi hành khác nhau</p>
                            </div>
                        </div>

                        <div class="card-modern mb-4">
                            <div class="card-body">
                                <div class="dynamic-section-header">
                                    <div class="dynamic-section-title">Danh sách phiên bản</div>
                                    <button type="button" class="add-item-btn" id="add-version-item">
                                        <i class="fas fa-plus"></i> Thêm phiên bản
                                    </button>
                                </div>
                                <div id="versions-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($versions ?? []) ?>'></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Right) -->
                <div class="col-lg-4">
                    <div class="sidebar-widget">
                        <div class="widget-title">Thao tác</div>
                        <div class="widget-actions">
                            <button type="submit" class="btn-modern btn-primary-gradient w-100 mb-2">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                            <button type="button" class="btn-modern btn-secondary w-100" onclick="history.back()">
                                <i class="fas fa-times"></i> Hủy bỏ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<!-- Auto-save Indicator -->
<div class="auto-save-indicator" id="autoSaveIndicator">
    <div class="auto-save-spinner"></div>
    <span>Đang lưu nháp...</span>
</div>

<!-- Templates -->
<template id="pricing-option-template">
    <div class="dynamic-item">
        <button type="button" class="remove-item-btn remove-pricing-option"><i class="fas fa-times"></i></button>
        <div class="row g-3">
            <div class="col-md-12">
                <div class="form-floating-modern">
                    <select data-field="label" class="form-select form-control">
                        <option value="Người lớn">Người lớn</option>
                        <option value="Trẻ em">Trẻ em</option>
                        <option value="Em bé">Em bé</option>
                    </select>
                    <label>Tên gói</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating-modern">
                    <input type="text" data-field="description" class="form-control" placeholder=" ">
                    <label>Mô tả ngắn</label>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="dynamic-pricing-template">
    <div class="dynamic-item">
        <button type="button" class="remove-item-btn remove-dynamic-pricing"><i class="fas fa-times"></i></button>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <select data-field="option_label" class="form-select form-control">
                        <option value="">-- Chọn loại giá --</option>
                    </select>
                    <label>Áp dụng cho gói</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <input type="number" data-field="price" class="form-control" placeholder=" ">
                    <label>Giá điều chỉnh (VNĐ)</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <input type="date" data-field="start_date" class="form-control" placeholder=" ">
                    <label>Từ ngày</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <input type="date" data-field="end_date" class="form-control" placeholder=" ">
                    <label>Đến ngày</label>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="itinerary-item-template">
    <div class="dynamic-item">
        <button type="button" class="remove-item-btn remove-itinerary-item"><i class="fas fa-times"></i></button>
        <div class="row g-3">
            <div class="col-md-2">
                <div class="form-floating-modern">
                    <input type="text" data-field="day" class="form-control" placeholder=" ">
                    <label>Ngày (VD: Ngày 1)</label>
                </div>
            </div>
            <div class="col-md-10">
                <div class="form-floating-modern">
                    <input type="text" data-field="title" class="form-control" placeholder=" ">
                    <label>Tiêu đề hoạt động</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <input type="time" data-field="time_start" class="form-control" placeholder=" ">
                    <label>Giờ bắt đầu</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <input type="time" data-field="time_end" class="form-control" placeholder=" ">
                    <label>Giờ kết thúc</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating-modern">
                    <textarea data-field="description" class="form-control" style="height: 100px" placeholder=" "></textarea>
                    <label>Mô tả chi tiết</label>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="partner-item-template">
    <div class="dynamic-item">
        <button type="button" class="remove-item-btn remove-partner-item"><i class="fas fa-times"></i></button>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <select data-field="service_type" class="form-select form-control">
                        <option value="hotel">Khách sạn</option>
                        <option value="transport">Vận chuyển</option>
                        <option value="restaurant">Nhà hàng</option>
                        <option value="guide">Hướng dẫn viên</option>
                        <option value="other">Khác</option>
                    </select>
                    <label>Loại dịch vụ</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <input type="text" data-field="name" class="form-control" placeholder=" ">
                    <label>Tên đối tác</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating-modern">
                    <input type="text" data-field="contact" class="form-control" placeholder=" ">
                    <label>Thông tin liên hệ</label>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="version-item-template">
    <div class="dynamic-item">
        <button type="button" class="remove-item-btn remove-version-item"><i class="fas fa-times"></i></button>
        <div class="row g-3">
            <div class="col-12">
                <div class="form-floating-modern">
                    <select data-field="name" class="form-select form-control">
                        <option value="Cơ bản">Cơ bản</option>
                        <option value="Cao cấp">Cao cấp</option>
                        <option value="Mùa hè">Mùa hè</option>
                        <option value="Mùa đông">Mùa đông</option>
                        <option value="Lễ / Tết">Lễ / Tết</option>
                    </select>
                    <label>Tên phiên bản</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <input type="date" data-field="start_date" class="form-control" placeholder=" ">
                    <label>Ngày bắt đầu</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating-modern">
                    <input type="date" data-field="end_date" class="form-control" placeholder=" ">
                    <label>Ngày kết thúc</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating-modern">
                    <input type="number" data-field="price" class="form-control" min="0" step="1000" placeholder=" ">
                    <label>Giá riêng (VNĐ) - Để trống nếu dùng giá gốc</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating-modern">
                    <textarea data-field="notes" class="form-control" style="height: 80px" placeholder=" "></textarea>
                    <label>Ghi chú</label>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Image Preview Template (JS will use this) -->
<template id="image-preview-template">
    <div class="image-preview-card">
        <img src="" alt="Preview">
        <div class="image-preview-overlay">
            <button type="button" class="image-preview-action delete" title="Xóa ảnh">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="form-check mt-2 text-center">
            <input class="form-check-input float-none" type="radio" name="main_image_index">
            <label class="form-check-label small d-block text-muted">Ảnh đại diện</label>
        </div>
    </div>
</template>

<script src="<?= BASE_ASSETS_ADMIN ?>js/tours.js"></script>
<script>
    // Additional JS for modern features
    document.addEventListener('DOMContentLoaded', function() {
        // Image upload preview
        const fileInput = document.getElementById('gallery_images');
        const previewGrid = document.getElementById('imagePreview');
        const dropZone = document.getElementById('dropZone');

        if (fileInput && previewGrid) {
            fileInput.addEventListener('change', function(e) {
                handleFiles(this.files);
            });

            // Drag & Drop
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                const files = e.dataTransfer.files;
                fileInput.files = files;
                handleFiles(files);
            });
        }

        // Auto-save functionality
        const form = document.getElementById('tour-form');
        const indicator = document.getElementById('autoSaveIndicator');
        let timeout;

        if (form && indicator) {
            form.addEventListener('input', function() {
                clearTimeout(timeout);
                indicator.className = 'auto-save-indicator show saving';
                indicator.innerHTML = '<div class="auto-save-spinner"></div><span>Đang lưu nháp...</span>';

                timeout = setTimeout(function() {
                    indicator.className = 'auto-save-indicator show saved';
                    indicator.innerHTML = '<i class="fas fa-check-circle"></i><span>Đã lưu nháp</span>';

                    setTimeout(() => {
                        indicator.classList.remove('show');
                    }, 2000);
                }, 1000);
            });
        }

        // Pricing option price display functionality
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('pricing-option-select')) {
                const selectedOption = e.target.selectedOptions[0];
                const price = selectedOption.getAttribute('data-price');
                const priceInput = e.target.closest('.dynamic-item').querySelector('.pricing-option-price');

                if (priceInput && price) {
                    priceInput.value = price;
                }
            }
        });

        // Update pricing options for dynamic pricing dropdown when pricing options change
        function updateDynamicPricingOptions() {
            const pricingOptions = document.querySelectorAll('#pricing-options-list .pricing-option-select');
            const dynamicSelects = document.querySelectorAll('#dynamic-pricing-list .dynamic-item select[data-field="option_label"]');

            dynamicSelects.forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">-- Chọn loại giá --</option>';

                pricingOptions.forEach(option => {
                    const selectedOption = option.selectedOptions[0];
                    if (selectedOption && selectedOption.value) {
                        const optionElement = document.createElement('option');
                        optionElement.value = selectedOption.value;
                        optionElement.textContent = selectedOption.textContent.split(' - ')[0]; // Remove price from display
                        select.appendChild(optionElement);
                    }
                });

                // Restore previous selection if it still exists
                if (currentValue && select.querySelector(`option[value="${currentValue}"]`)) {
                    select.value = currentValue;
                }
            });
        }

        // Listen for changes in pricing options to update dynamic pricing
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('pricing-option-select')) {
                setTimeout(updateDynamicPricingOptions, 100); // Small delay to ensure DOM updates
            }
        });

        // Initial update on page load
        setTimeout(updateDynamicPricingOptions, 500);
    });
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
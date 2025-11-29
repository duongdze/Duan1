<?php
$tourId = $_GET['id'] ?? null;
?>
<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="tours-dashboard tour-edit-page">
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
                        <span class="breadcrumb-current">Chỉnh sửa Tour</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-edit title-icon"></i>
                            Chỉnh sửa Tour
                        </h1>
                        <p class="page-subtitle">Cập nhật thông tin chi tiết cho tour du lịch</p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= BASE_URL_ADMIN ?>&action=tours" class="btn btn-modern btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Hủy bỏ
                    </a>
                    <a href="<?= BASE_URL_ADMIN ?>&action=tours/detail&id=<?= $tourId ?>" class="btn btn-modern btn-info">
                        <i class="fas fa-eye me-2"></i>
                        Xem chi tiết
                    </a>
                    <button type="submit" form="tour-edit-form" class="btn btn-modern btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </div>
        </header>

        <!-- Alert Messages -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert-modern alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?= htmlspecialchars($_SESSION['error']) ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert-modern alert-success alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span><?= htmlspecialchars($_SESSION['success']) ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Progress Steps -->
        <div class="progress-steps-wrapper mb-4">
            <div class="progress-steps">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Thông tin cơ bản</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Hình ảnh</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Lịch trình</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Giá & Khởi hành</div>
                </div>
                <div class="step" data-step="5">
                    <div class="step-number">5</div>
                    <div class="step-label">Chính sách & Đối tác</div>
                </div>
            </div>
        </div>

        <!-- Tour Form -->
        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=tours/update&id=<?= $tourId ?>" enctype="multipart/form-data" id="tour-edit-form">
            <!-- Hidden inputs for dynamic data -->
            <input type="hidden" name="tour_itinerary" id="tour_itinerary">
            <input type="hidden" name="tour_pricing_options" id="tour_pricing_options">
            <input type="hidden" name="tour_departures" id="tour_departures">
            <input type="hidden" name="tour_partners" id="tour_partners">
            <input type="hidden" name="tour_versions" id="tour_versions">

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" id="step-1">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Thông tin cơ bản
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" name="name" id="tour-name" class="form-control" required placeholder=" " value="<?= htmlspecialchars($tour['name'] ?? '') ?>">
                                            <label for="tour-name">Tên Tour <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="category_id" id="category_id" class="form-select" required>
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
                                        <div class="form-floating">
                                            <input type="number" name="base_price" id="base_price" class="form-control" required min="0" step="1000" placeholder=" " value="<?= $tour['base_price'] ?? 0 ?>">
                                            <label for="base_price">Giá cơ bản (VNĐ) <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="duration" id="duration" class="form-control" placeholder=" " value="<?= htmlspecialchars($tour['duration'] ?? '') ?>">
                                            <label for="duration">Thời lượng</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="difficulty" id="difficulty" class="form-select">
                                                <option value="">-- Chọn độ khó --</option>
                                                <option value="easy" <?= ($tour['difficulty'] ?? '') === 'easy' ? 'selected' : '' ?>>Dễ</option>
                                                <option value="medium" <?= ($tour['difficulty'] ?? '') === 'medium' ? 'selected' : '' ?>>Trung bình</option>
                                                <option value="hard" <?= ($tour['difficulty'] ?? '') === 'hard' ? 'selected' : '' ?>>Khó</option>
                                            </select>
                                            <label for="difficulty">Độ khó</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea name="description" id="description" class="form-control" style="height: 150px" placeholder=" "><?= htmlspecialchars($tour['description'] ?? '') ?></textarea>
                                            <label for="description">Mô tả chi tiết</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="departure_location" id="departure_location" class="form-control" placeholder=" " value="<?= htmlspecialchars($tour['departure_location'] ?? '') ?>">
                                            <label for="departure_location">Nơi khởi hành</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="destination" id="destination" class="form-control" placeholder=" " value="<?= htmlspecialchars($tour['destination'] ?? '') ?>">
                                            <label for="destination">Điểm đến</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Images -->
                    <div class="form-step" id="step-2">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-images text-primary me-2"></i>
                                    Hình ảnh Tour
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Main Image -->
                                <div class="mb-4">
                                    <label class="form-label">Ảnh đại diện</label>
                                    <div class="main-image-preview">
                                        <?php if (!empty($mainImage)): ?>
                                            <img src="<?= BASE_ASSETS_UPLOADS . $mainImage['image_url'] ?>" alt="Main Image" style="max-width: 300px; height: 200px; object-fit: cover; border-radius: 8px;">
                                            <input type="hidden" name="existing_main_image" value="<?= $mainImage['id'] ?>">
                                        <?php else: ?>
                                            <div class="upload-area" onclick="document.getElementById('main_image').click()">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Click để tải lên ảnh đại diện</p>
                                                <small class="text-muted">JPG, PNG, WebP (Tối đa 5MB)</small>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="main_image" id="main_image" class="d-none" accept="image/*">
                                    </div>
                                </div>

                                <!-- Gallery Images -->
                                <div class="mb-4">
                                    <label class="form-label">Thư viện ảnh</label>
                                    <div class="upload-area" onclick="document.getElementById('gallery_images').click()">
                                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Click để tải lên nhiều ảnh</p>
                                        <small class="text-muted">JPG, PNG, WebP (Tối đa 5MB mỗi ảnh)</small>
                                    </div>
                                    <input type="file" name="gallery_images[]" id="gallery_images" class="d-none" accept="image/*" multiple>

                                    <!-- Existing Gallery -->
                                    <?php if (!empty($galleryImages)): ?>
                                        <div class="gallery-preview-grid mt-3">
                                            <?php foreach ($galleryImages as $img): ?>
                                                <div class="gallery-preview-item">
                                                    <img src="<?= BASE_ASSETS_UPLOADS . $img['image_url'] ?>" alt="Gallery Image">
                                                    <div class="gallery-item-actions">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeExistingImage(<?= $img['id'] ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="existing_gallery[]" value="<?= $img['id'] ?>">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div id="gallery-preview" class="gallery-preview-grid mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Itinerary -->
                    <div class="form-step" id="step-3">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-route me-2"></i>Lịch trình</h5>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addItineraryItem()">
                                    <i class="fas fa-plus me-1"></i>Thêm ngày
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="itinerary-list" class="itinerary-list">
                                    <?php if (!empty($itineraries)): ?>
                                        <?php foreach ($itineraries as $index => $itinerary): ?>
                                            <div class="itinerary-item" data-index="<?= $index ?>">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6>Ngày <?= $itinerary['day_number'] ?>: <?= htmlspecialchars($itinerary['day_label']) ?></h6>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItineraryItem(<?= $index ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="text" name="itinerary_day_<?= $index ?>" class="form-control" value="<?= htmlspecialchars($itinerary['day_label']) ?>" placeholder="Ngày 1">
                                                            <label for="itinerary_day_<?= $index ?>">Ngày</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-floating">
                                                            <input type="time" name="itinerary_time_<?= $index ?>" class="form-control" value="<?= $itinerary['time_start'] ?>">
                                                            <label for="itinerary_time_<?= $index ?>">Giờ</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-floating">
                                                            <input type="text" name="itinerary_title_<?= $index ?>" class="form-control" value="<?= htmlspecialchars($itinerary['title'] ?? '') ?>" placeholder="Tiêu đề hoạt động">
                                                            <label for="itinerary_title_<?= $index ?>">Tiêu đề</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-floating">
                                                            <textarea name="itinerary_activities_<?= $index ?>" class="form-control" rows="3" placeholder="Chi tiết hoạt động"><?= htmlspecialchars($itinerary['activities']) ?></textarea>
                                                            <label for="itinerary_activities_<?= $index ?>">Hoạt động</label>
                                                        </div>
                                                    </div>
                                                    <?php if (!empty($itinerary['image_url'])): ?>
                                                        <div class="col-12">
                                                            <img src="<?= BASE_ASSETS_UPLOADS . $itinerary['image_url'] ?>" alt="Itinerary" style="max-width: 200px; height: 150px; object-fit: cover; border-radius: 8px;">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div id="itinerary-empty" class="text-center text-muted py-5" style="<?= !empty($itineraries) ? 'display: none;' : '' ?>">
                                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                    <p>Chưa có lịch trình nào</p>
                                    <button type="button" class="btn btn-primary" onclick="addItineraryItem()">Thêm ngày đầu tiên</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Pricing & Departures -->
                    <div class="form-step" id="step-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Gói dịch vụ</h5>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addPricingOption()">
                                            <i class="fas fa-plus me-1"></i>Thêm gói
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div id="pricing-options-list">
                                            <?php if (!empty($pricingOptions)): ?>
                                                <?php foreach ($pricingOptions as $index => $option): ?>
                                                    <div class="pricing-item mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <div class="form-floating">
                                                                <input type="text" name="pricing_label_<?= $index ?>" class="form-control" value="<?= htmlspecialchars($option['label']) ?>" placeholder="Tên gói">
                                                                <label for="pricing_label_<?= $index ?>">Tên gói</label>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePricingOption(<?= $index ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <div class="form-floating">
                                                            <textarea name="pricing_description_<?= $index ?>" class="form-control" rows="2" placeholder="Mô tả gói"><?= htmlspecialchars($option['description'] ?? '') ?></textarea>
                                                            <label for="pricing_description_<?= $index ?>">Mô tả</label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div id="pricing-empty" class="text-center text-muted py-4" style="<?= !empty($pricingOptions) ? 'display: none;' : '' ?>">
                                            <i class="fas fa-box fa-2x mb-2"></i>
                                            <p>Chưa có gói dịch vụ nào</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Lịch khởi hành</h5>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addDeparture()">
                                            <i class="fas fa-plus me-1"></i>Thêm lịch
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div id="departures-list">
                                            <?php if (!empty($departures)): ?>
                                                <?php foreach ($departures as $index => $departure): ?>
                                                    <div class="departure-item mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <input type="date" name="departure_date_<?= $index ?>" class="form-control" value="<?= $departure['departure_date'] ?>">
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDeparture(<?= $index ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <div class="row g-2">
                                                            <div class="col-4">
                                                                <input type="number" name="max_seats_<?= $index ?>" class="form-control" value="<?= $departure['max_seats'] ?>" placeholder="Số chỗ">
                                                            </div>
                                                            <div class="col-4">
                                                                <input type="number" name="price_adult_<?= $index ?>" class="form-control" value="<?= $departure['price_adult'] ?>" placeholder="Giá người lớn">
                                                            </div>
                                                            <div class="col-4">
                                                                <select name="departure_status_<?= $index ?>" class="form-select">
                                                                    <option value="open" <?= ($departure['status'] ?? 'open') === 'open' ? 'selected' : '' ?>>Mở</option>
                                                                    <option value="full" <?= ($departure['status'] ?? '') === 'full' ? 'selected' : '' ?>>Đầy</option>
                                                                    <option value="cancelled" <?= ($departure['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Hủy</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div id="departures-empty" class="text-center text-muted py-4" style="<?= !empty($departures) ? 'display: none;' : '' ?>">
                                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                            <p>Chưa có lịch khởi hành nào</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Policies & Partners -->
                    <div class="form-step" id="step-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Chính sách tour</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($policies)): ?>
                                            <?php foreach ($policies as $policy): ?>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="policies[]" value="<?= $policy['id'] ?>"
                                                        <?= in_array($policy['id'], array_column($assignedPolicies ?? [], 'policy_id')) ? 'checked' : '' ?>>
                                                    <label class="form-check-label">
                                                        <?= htmlspecialchars($policy['name']) ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted">Chưa có chính sách nào</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-handshake me-2"></i>Đối tác dịch vụ</h5>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addPartner()">
                                            <i class="fas fa-plus me-1"></i>Thêm đối tác
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div id="partners-list">
                                            <?php if (!empty($partners)): ?>
                                                <?php foreach ($partners as $index => $partner): ?>
                                                    <div class="partner-item mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <select name="partner_service_<?= $index ?>" class="form-select">
                                                                <option value="hotel" <?= ($partner['service_type'] ?? '') === 'hotel' ? 'selected' : '' ?>>Khách sạn</option>
                                                                <option value="restaurant" <?= ($partner['service_type'] ?? '') === 'restaurant' ? 'selected' : '' ?>>Nhà hàng</option>
                                                                <option value="transport" <?= ($partner['service_type'] ?? '') === 'transport' ? 'selected' : '' ?>>Vận chuyển</option>
                                                                <option value="guide" <?= ($partner['service_type'] ?? '') === 'guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                                                                <option value="other" <?= ($partner['service_type'] ?? '') === 'other' ? 'selected' : '' ?>>Khác</option>
                                                            </select>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePartner(<?= $index ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text" name="partner_name_<?= $index ?>" class="form-control mb-2" value="<?= htmlspecialchars($partner['partner_name']) ?>" placeholder="Tên đối tác">
                                                        <input type="text" name="partner_contact_<?= $index ?>" class="form-control mb-2" value="<?= htmlspecialchars($partner['contact'] ?? '') ?>" placeholder="Thông tin liên hệ">
                                                        <textarea name="partner_notes_<?= $index ?>" class="form-control" rows="2" placeholder="Ghi chú"><?= htmlspecialchars($partner['notes'] ?? '') ?></textarea>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div id="partners-empty" class="text-center text-muted py-4" style="<?= !empty($partners) ? 'display: none;' : '' ?>">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <p>Chưa có đối tác nào</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-secondary" onclick="previousStep()" id="prev-btn">
                                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-primary" onclick="nextStep()" id="next-btn">
                                        Tiếp theo<i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
        </form>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 5;

        // Step navigation
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));

            // Show current step
            document.getElementById(`step-${step}`).classList.add('active');
            document.querySelector(`.step[data-step="${step}"]`).classList.add('active');

            // Update buttons
            document.getElementById('prev-btn').style.display = step === 1 ? 'none' : 'inline-block';
            document.getElementById('next-btn').style.display = step === totalSteps ? 'none' : 'inline-block';
            document.getElementById('submit-btn').style.display = step === totalSteps ? 'inline-block' : 'none';

            currentStep = step;
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        // Click on step indicators
        document.querySelectorAll('.step').forEach(stepEl => {
            stepEl.addEventListener('click', function() {
                const step = parseInt(this.dataset.step);
                showStep(step);
            });
        });

        // Dynamic item management
        function addItineraryItem() {
            const container = document.getElementById('itinerary-list');
            const index = container.children.length;

            const item = document.createElement('div');
            item.className = 'itinerary-item';
            item.dataset.index = index;
            item.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6>Ngày ${index + 1}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItineraryItem(${index})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" name="itinerary_day_${index}" class="form-control" placeholder="Ngày ${index + 1}">
            </div>
            <div class="col-md-3">
                <input type="time" name="itinerary_time_${index}" class="form-control">
            </div>
            <div class="col-md-6">
                <input type="text" name="itinerary_title_${index}" class="form-control" placeholder="Tiêu đề hoạt động">
            </div>
            <div class="col-12">
                <textarea name="itinerary_activities_${index}" class="form-control" rows="3" placeholder="Chi tiết hoạt động"></textarea>
            </div>
        </div>
    `;

            container.appendChild(item);
            document.getElementById('itinerary-empty').style.display = 'none';
        }

        function removeItineraryItem(index) {
            const item = document.querySelector(`.itinerary-item[data-index="${index}"]`);
            if (item) {
                item.remove();
                if (document.getElementById('itinerary-list').children.length === 0) {
                    document.getElementById('itinerary-empty').style.display = 'block';
                }
            }
        }

        function addPricingOption() {
            const container = document.getElementById('pricing-options-list');
            const index = container.children.length;

            const item = document.createElement('div');
            item.className = 'pricing-item mb-3';
            item.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="text" name="pricing_label_${index}" class="form-control" placeholder="Tên gói">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePricingOption(${index})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <textarea name="pricing_description_${index}" class="form-control" rows="2" placeholder="Mô tả gói"></textarea>
    `;

            container.appendChild(item);
            document.getElementById('pricing-empty').style.display = 'none';
        }

        function removePricingOption(index) {
            const item = document.querySelectorAll('.pricing-item')[index];
            if (item) {
                item.remove();
                if (document.getElementById('pricing-options-list').children.length === 0) {
                    document.getElementById('pricing-empty').style.display = 'block';
                }
            }
        }

        function addDeparture() {
            const container = document.getElementById('departures-list');
            const index = container.children.length;

            const item = document.createElement('div');
            item.className = 'departure-item mb-3';
            item.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <input type="date" name="departure_date_${index}" class="form-control">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDeparture(${index})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-4">
                <input type="number" name="max_seats_${index}" class="form-control" placeholder="Số chỗ">
            </div>
            <div class="col-4">
                <input type="number" name="price_adult_${index}" class="form-control" placeholder="Giá người lớn">
            </div>
            <div class="col-4">
                <select name="departure_status_${index}" class="form-select">
                    <option value="open">Mở</option>
                    <option value="full">Đầy</option>
                    <option value="cancelled">Hủy</option>
                </select>
            </div>
        </div>
    `;

            container.appendChild(item);
            document.getElementById('departures-empty').style.display = 'none';
        }

        function removeDeparture(index) {
            const item = document.querySelectorAll('.departure-item')[index];
            if (item) {
                item.remove();
                if (document.getElementById('departures-list').children.length === 0) {
                    document.getElementById('departures-empty').style.display = 'block';
                }
            }
        }

        function addPartner() {
            const container = document.getElementById('partners-list');
            const index = container.children.length;

            const item = document.createElement('div');
            item.className = 'partner-item mb-3';
            item.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <select name="partner_service_${index}" class="form-select">
                <option value="hotel">Khách sạn</option>
                <option value="restaurant">Nhà hàng</option>
                <option value="transport">Vận chuyển</option>
                <option value="guide">Hướng dẫn viên</option>
                <option value="other">Khác</option>
            </select>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePartner(${index})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <input type="text" name="partner_name_${index}" class="form-control mb-2" placeholder="Tên đối tác">
        <input type="text" name="partner_contact_${index}" class="form-control mb-2" placeholder="Thông tin liên hệ">
        <textarea name="partner_notes_${index}" class="form-control" rows="2" placeholder="Ghi chú"></textarea>
    `;

            container.appendChild(item);
            document.getElementById('partners-empty').style.display = 'none';
        }

        function removePartner(index) {
            const item = document.querySelectorAll('.partner-item')[index];
            if (item) {
                item.remove();
                if (document.getElementById('partners-list').children.length === 0) {
                    document.getElementById('partners-empty').style.display = 'block';
                }
            }
        }

        // Image management
        function removeExistingImage(imageId) {
            if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
                // Add to hidden field for deletion
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = imageId;
                document.getElementById('tour-edit-form').appendChild(input);

                // Remove from UI
                event.target.closest('.gallery-preview-item').remove();
            }
        }

        // Gallery preview
        document.getElementById('gallery_images')?.addEventListener('change', function(e) {
            const preview = document.getElementById('gallery-preview');
            preview.innerHTML = '';

            Array.from(e.target.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const item = document.createElement('div');
                        item.className = 'gallery-preview-item';
                        item.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <div class="gallery-item-actions">
                        <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                        preview.appendChild(item);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Form submission
        document.getElementById('tour-edit-form').addEventListener('submit', function(e) {
            // Serialize dynamic data
            const pricingOptions = [];
            document.querySelectorAll('.pricing-item').forEach((item, index) => {
                pricingOptions.push({
                    label: item.querySelector(`input[name="pricing_label_${index}"]`)?.value || '',
                    description: item.querySelector(`textarea[name="pricing_description_${index}"]`)?.value || ''
                });
            });
            document.getElementById('tour_pricing_options').value = JSON.stringify(pricingOptions);

            const itineraries = [];
            document.querySelectorAll('.itinerary-item').forEach((item, index) => {
                itineraries.push({
                    day_label: item.querySelector(`input[name="itinerary_day_${index}"]`)?.value || '',
                    time_start: item.querySelector(`input[name="itinerary_time_${index}"]`)?.value || '',
                    title: item.querySelector(`input[name="itinerary_title_${index}"]`)?.value || '',
                    activities: item.querySelector(`textarea[name="itinerary_activities_${index}"]`)?.value || ''
                });
            });
            document.getElementById('tour_itinerary').value = JSON.stringify(itineraries);

            const departures = [];
            document.querySelectorAll('.departure-item').forEach((item, index) => {
                departures.push({
                    departure_date: item.querySelector(`input[name="departure_date_${index}"]`)?.value || '',
                    max_seats: item.querySelector(`input[name="max_seats_${index}"]`)?.value || '',
                    price_adult: item.querySelector(`input[name="price_adult_${index}"]`)?.value || '',
                    status: item.querySelector(`select[name="departure_status_${index}"]`)?.value || 'open'
                });
            });
            document.getElementById('tour_versions').value = JSON.stringify(departures);

            const partners = [];
            document.querySelectorAll('.partner-item').forEach((item, index) => {
                partners.push({
                    service_type: item.querySelector(`select[name="partner_service_${index}"]`)?.value || 'other',
                    name: item.querySelector(`input[name="partner_name_${index}"]`)?.value || '',
                    contact: item.querySelector(`input[name="partner_contact_${index}"]`)?.value || '',
                    notes: item.querySelector(`textarea[name="partner_notes_${index}"]`)?.value || ''
                });
            });
            document.getElementById('tour_partners').value = JSON.stringify(partners);
        });

        // Initialize
        showStep(1);
    </script>

    <?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
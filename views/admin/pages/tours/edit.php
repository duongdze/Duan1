<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$pricingOptions = !empty($tour['pricing_options']) ? json_decode($tour['pricing_options'], true) : [];
$itinerarySchedule = !empty($tour['itinerary_schedule']) ? json_decode($tour['itinerary_schedule'], true) : [];
$partnerServices = !empty($tour['partner_services']) ? json_decode($tour['partner_services'], true) : [];
$galleryImages = !empty($tour['gallery_images']) ? json_decode($tour['gallery_images'], true) : [];

if (empty($pricingOptions)) {
    $pricingOptions = [[]];
}

if (empty($itinerarySchedule)) {
    $itinerarySchedule = [[]];
}

if (empty($partnerServices)) {
    $partnerServices = [[]];
}
?>

<style>
    .cke {
        width: 100% !important;
    }

    .cke_top {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .cke_bottom {
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
</style>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Chỉnh sửa Tour</h1>
            <p class="text-muted">Cập nhật thông tin tour hiện có</p>
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

        <form method="POST" action="?action=tours/update" enctype="multipart/form-data" class="tour-form">
            <input type="hidden" name="id" value="<?= htmlspecialchars($tour['id']) ?>">
            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-lg-6">
                    <!-- Thông tin cơ bản -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i> Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-500">Tên tour</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($tour['name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label fw-500">Danh mục tour</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Chọn loại tour</option>
                                    <option value="trong_nuoc" <?= $tour['type'] === 'trong_nuoc' ? 'selected' : '' ?>>Tour trong nước</option>
                                    <option value="quoc_te" <?= $tour['type'] === 'quoc_te' ? 'selected' : '' ?>>Tour quốc tế</option>
                                    <option value="theo_yeu_cau" <?= $tour['type'] === 'theo_yeu_cau' ? 'selected' : '' ?>>Tour theo yêu cầu</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="supplier_id" class="form-label fw-500">Nhà cung cấp</label>
                                <select class="form-select" id="supplier_id" name="supplier_id">
                                    <option value="">Chọn nhà cung cấp</option>
                                    <?php if (!empty($suppliers)): ?>
                                        <?php foreach ($suppliers as $s): ?>
                                            <option value="<?= htmlspecialchars($s['id']) ?>" <?= $tour['supplier_id'] == $s['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($s['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="base_price" class="form-label fw-500">Giá cơ bản</label>
                                <input type="number" class="form-control" id="base_price" name="base_price" value="<?= htmlspecialchars($tour['base_price']) ?>" min="0" step="50000" required>
                                <small class="text-muted">Đơn giá mặc định áp dụng khi không có gói riêng.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-align-left"></i> Mô tả
                            </h5>
                        </div>
                        <div class="card-body">
                            <label for="description" class="form-label fw-500">Nhập mô tả</label>
                            <input type="hidden" id="input-description" name="description" value='<?= htmlspecialchars($tour['description'] ?? '', ENT_QUOTES) ?>'>
                            <div id="editor-description"></div>
                        </div>
                    </div>

                    <!-- Gói giá & dịch vụ -->
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-tags"></i> Giá theo đối tượng / gói dịch vụ
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-pricing-tier">
                                <i class="fas fa-plus"></i> Thêm gói
                            </button>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Ghi rõ tên nhóm khách (VD: Người lớn, Trẻ em, Dịp lễ), giá áp dụng và ghi chú kèm dịch vụ.</p>
                            <div id="pricing-tier-list" class="d-flex flex-column gap-3">
                                <?php foreach ($pricingOptions as $option): ?>
                                    <div class="pricing-tier-item border rounded p-3 bg-light-subtle position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-pricing-tier" aria-label="Xóa"></button>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label fw-500">Nhóm khách / Gói</label>
                                                <input type="text" class="form-control" name="pricing_label[]" data-field="label" value="<?= htmlspecialchars($option['label'] ?? '') ?>" placeholder="Người lớn">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-500">Giá áp dụng</label>
                                                <input type="number" class="form-control" name="pricing_price[]" data-field="price" value="<?= htmlspecialchars($option['price'] ?? '') ?>" placeholder="1500000" min="0">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-500">Ghi chú dịch vụ</label>
                                                <textarea class="form-control" rows="2" name="pricing_description[]" data-field="description" placeholder="Bao gồm ăn sáng, xe đưa đón sân bay"><?= htmlspecialchars($option['description'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <template id="pricing-tier-template">
                                <div class="pricing-tier-item border rounded p-3 bg-light-subtle position-relative">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-pricing-tier" aria-label="Xóa"></button>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label fw-500">Nhóm khách / Gói</label>
                                            <input type="text" class="form-control" name="pricing_label[]" data-field="label" placeholder="Người lớn">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-500">Giá áp dụng</label>
                                            <input type="number" class="form-control" name="pricing_price[]" data-field="price" placeholder="1500000" min="0">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-500">Ghi chú dịch vụ</label>
                                            <textarea class="form-control" rows="2" name="pricing_description[]" data-field="description" placeholder="Bao gồm ăn sáng, xe đưa đón sân bay"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6">
                    <!-- Lịch trình -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar"></i> Lịch trình
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Thêm từng ngày/hoạt động cụ thể để khách dễ theo dõi.</p>
                            <div id="itinerary-list" class="d-flex flex-column gap-3">
                                <?php foreach ($itinerarySchedule as $item): ?>
                                    <div class="itinerary-item border rounded p-3 position-relative bg-light-subtle">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-itinerary-item" aria-label="Xóa"></button>
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="form-label fw-500">Ngày / Chặng</label>
                                                <input type="text" name="itinerary_day[]" data-field="day" class="form-control" value="<?= htmlspecialchars($item['day'] ?? '') ?>" placeholder="Ngày 1">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-500">Giờ bắt đầu</label>
                                                <input type="time" name="itinerary_time_start[]" data-field="time_start" class="form-control" value="<?= htmlspecialchars($item['time_start'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-500">Giờ kết thúc</label>
                                                <input type="time" name="itinerary_time_end[]" data-field="time_end" class="form-control" value="<?= htmlspecialchars($item['time_end'] ?? '') ?>">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-500">Tiêu đề hoạt động</label>
                                                <input type="text" name="itinerary_title[]" data-field="title" class="form-control" value="<?= htmlspecialchars($item['title'] ?? '') ?>" placeholder="Khởi hành từ Hà Nội">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-500">Chi tiết</label>
                                                <textarea name="itinerary_description[]" data-field="description" class="form-control" rows="3" placeholder="Tham quan, ăn uống, trải nghiệm..."><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-primary w-100 mt-3" id="add-itinerary-item">
                                <i class="fas fa-plus"></i> Thêm ngày / hoạt động
                            </button>
                            <template id="itinerary-item-template">
                                <div class="itinerary-item border rounded p-3 position-relative bg-light-subtle">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-itinerary-item" aria-label="Xóa"></button>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Ngày / Chặng</label>
                                            <input type="text" name="itinerary_day[]" data-field="day" class="form-control" placeholder="Ngày 1">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Giờ bắt đầu</label>
                                            <input type="time" name="itinerary_time_start[]" data-field="time_start" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Giờ kết thúc</label>
                                            <input type="time" name="itinerary_time_end[]" data-field="time_end" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-500">Tiêu đề hoạt động</label>
                                            <input type="text" name="itinerary_title[]" data-field="title" class="form-control" placeholder="Khởi hành từ Hà Nội">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-500">Chi tiết</label>
                                            <textarea name="itinerary_description[]" data-field="description" class="form-control" rows="3" placeholder="Tham quan, ăn uống, trải nghiệm..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-image"></i> Hình ảnh
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <label class="form-label fw-500 d-flex align-items-center justify-content-between">
                                    Ảnh đại diện
                                    <small class="text-muted">Tỷ lệ khuyến nghị 4:3</small>
                                </label>
                                <div class="p-4 bg-light rounded border border-dashed" id="image-preview">
                                    <?php if (!empty($tour['image'])): ?>
                                        <img src="<?= htmlspecialchars($tour['image']) ?>" alt="Ảnh tour" class="form-image-preview">
                                    <?php else: ?>
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <p class="text-muted small mt-2 mb-0">Chưa có hình ảnh</p>
                                    <?php endif; ?>
                                </div>
                                <input type="file" class="form-control mt-2" id="image" name="image" accept="image/*">
                            </div>

                            <div>
                                <label class="form-label fw-500">Bộ sưu tập hình ảnh</label>
                                <div class="p-3 border rounded bg-light mb-2">
                                    <input type="file" class="form-control" id="gallery" name="gallery_images[]" accept="image/*" multiple>
                                    <small class="text-muted d-block mt-2">Tối đa 10 ảnh, hỗ trợ jpg, png, webp.</small>
                                </div>
                                <div id="gallery-preview" class="row g-2">
                                    <?php if (!empty($galleryImages)): ?>
                                        <?php foreach ($galleryImages as $image): ?>
                                            <div class="col-4">
                                                <div class="ratio ratio-4x3 rounded overflow-hidden border">
                                                    <img src="<?= htmlspecialchars($image) ?>" class="w-100 h-100 object-fit-cover" alt="Gallery">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Đối tác cung ứng -->
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-handshake"></i> Nhà cung cấp dịch vụ kèm theo
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-partner-item">
                                <i class="fas fa-plus"></i> Thêm đối tác
                            </button>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Liệt kê các dịch vụ: Khách sạn, xe, nhà hàng, vé tham quan,... để đội vận hành dễ theo dõi.</p>
                            <div id="partner-list" class="d-flex flex-column gap-3">
                                <?php foreach ($partnerServices as $partner): ?>
                                    <div class="partner-item border rounded p-3 bg-light-subtle position-relative">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-partner-item" aria-label="Xóa"></button>
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="form-label fw-500">Loại dịch vụ</label>
                                                <select class="form-select" name="partner_service[]" data-field="service_type">
                                                    <option value="hotel" <?= ($partner['service_type'] ?? '') === 'hotel' ? 'selected' : '' ?>>Khách sạn</option>
                                                    <option value="transport" <?= ($partner['service_type'] ?? '') === 'transport' ? 'selected' : '' ?>>Vận chuyển</option>
                                                    <option value="restaurant" <?= ($partner['service_type'] ?? '') === 'restaurant' ? 'selected' : '' ?>>Nhà hàng</option>
                                                    <option value="ticket" <?= ($partner['service_type'] ?? '') === 'ticket' ? 'selected' : '' ?>>Vé tham quan</option>
                                                    <option value="other" <?= ($partner['service_type'] ?? '') === 'other' ? 'selected' : '' ?>>Khác</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-500">Đối tác</label>
                                                <input type="text" class="form-control" name="partner_name[]" data-field="name" value="<?= htmlspecialchars($partner['name'] ?? '') ?>" placeholder="The Cliff Resort">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-500">Liên hệ</label>
                                                <input type="text" class="form-control" name="partner_contact[]" data-field="contact" value="<?= htmlspecialchars($partner['contact'] ?? '') ?>" placeholder="Mr A - 098xxx">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-500">Ghi chú</label>
                                                <textarea class="form-control" rows="2" name="partner_notes[]" data-field="notes" placeholder="Yêu cầu đặt trước 3 ngày..."><?= htmlspecialchars($partner['notes'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <template id="partner-item-template">
                                <div class="partner-item border rounded p-3 bg-light-subtle position-relative">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-partner-item" aria-label="Xóa"></button>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Loại dịch vụ</label>
                                            <select class="form-select" name="partner_service[]" data-field="service_type">
                                                <option value="hotel">Khách sạn</option>
                                                <option value="transport">Vận chuyển</option>
                                                <option value="restaurant">Nhà hàng</option>
                                                <option value="ticket">Vé tham quan</option>
                                                <option value="other">Khác</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Đối tác</label>
                                            <input type="text" class="form-control" name="partner_name[]" data-field="name" placeholder="The Cliff Resort">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Liên hệ</label>
                                            <input type="text" class="form-control" name="partner_contact[]" data-field="contact" placeholder="Mr A - 098xxx">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-500">Ghi chú</label>
                                            <textarea class="form-control" rows="2" name="partner_notes[]" data-field="notes" placeholder="Yêu cầu đặt trước 3 ngày..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chính sách & Actions -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt"></i> Chính sách
                    </h5>
                </div>
                <div class="card-body">
                    <label for="policy" class="form-label fw-500">Nhập chính sách</label>
                    <input type="hidden" id="input-policy" name="policy" value='<?= htmlspecialchars($tour['policy'] ?? '', ENT_QUOTES) ?>'>
                    <div id="editor-policy"></div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-3 d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="?action=tours" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</main>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
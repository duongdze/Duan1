<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Thêm Tour Mới</h1>
            <p class="text-muted">Tạo tour mới cho hệ thống</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=tours/store" enctype="multipart/form-data" class="tour-form">
            <div class="row g-3">
                <!-- Main Column (Left) -->
                <div class="col-lg-8">
                    <!-- 1. Thông tin cơ bản -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin cơ bản</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên Tour <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Nhập tên tour...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Mô tả chi tiết về tour..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Cấu hình giá -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-tags"></i> Cấu hình giá</h5>
                        </div>
                        <div class="card-body">
                            <!-- Pricing Options -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold">Các loại giá (VD: Người lớn, Trẻ em)</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-pricing-option">
                                        <i class="fas fa-plus"></i> Thêm loại
                                    </button>
                                </div>
                                <div id="pricing-options-list" class="d-flex flex-column gap-3" data-initial="[]"></div>
                            </div>

                            <hr>

                            <!-- Dynamic Pricing -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold">Bảng giá theo thời điểm</h6>
                                    <button type="button" class="btn btn-sm btn-outline-success" id="add-dynamic-price">
                                        <i class="fas fa-plus"></i> Thêm giá
                                    </button>
                                </div>
                                <p class="text-muted small mb-2">Áp dụng giá cụ thể cho từng loại giá ở trên theo các khoảng thời gian khác nhau.</p>
                                <div id="dynamic-pricing-list" class="d-flex flex-column gap-3" data-initial="[]"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Lịch trình -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Lịch trình</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Thêm từng ngày/hoạt động cụ thể để khách dễ theo dõi.</p>
                            <div id="itinerary-list" class="d-flex flex-column gap-3 mb-3" data-initial="[]"></div>
                            <button type="button" class="btn btn-outline-primary w-100" id="add-itinerary-item">
                                <i class="fas fa-plus"></i> Thêm ngày / hoạt động
                            </button>
                        </div>
                    </div>

                    <!-- 4. Chính sách -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-file-contract"></i> Chính sách Tour</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($policies)): ?>
                                <div class="row g-3">
                                    <?php foreach ($policies as $policy): ?>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="policies[]" value="<?= $policy['id'] ?>" id="policy_<?= $policy['id'] ?>">
                                                <label class="form-check-label" for="policy_<?= $policy['id'] ?>">
                                                    <strong><?= htmlspecialchars($policy['name']) ?></strong>
                                                    <div class="text-muted small"><?= htmlspecialchars($policy['description']) ?></div>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-muted">Chưa có chính sách nào được định nghĩa.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Right) -->
                <div class="col-lg-4">
                    <!-- Actions -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Tạo Tour Mới
                                </button>
                                <a href="?action=tours" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Category & Price -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Phân loại & Giá</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá khởi điểm (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="base_price" class="form-control" required min="0" placeholder="0">
                                <div class="form-text">Giá hiển thị trên danh sách tour.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-images"></i> Thư viện ảnh</h5>
                        </div>
                        <div class="card-body">
                            <div id="image-drop-zone" class="p-4 bg-light rounded border-dashed text-center mb-3" style="cursor: pointer;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                                <p class="text-muted small mt-2 mb-0">Kéo thả hoặc nhấp để chọn ảnh</p>
                                <p class="text-muted small">Ảnh đầu tiên sẽ là ảnh đại diện.</p>
                            </div>
                            
                            <input type="file" id="file-input-handler" class="d-none" multiple accept="image/*">
                            <input type="file" name="image_url[]" id="gallery-images-input" class="d-none" multiple>

                            <div id="image-preview-container" class="row g-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<!-- Templates -->
<template id="pricing-option-template">
    <div class="pricing-option-item border rounded p-3 bg-light-subtle position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-pricing-option" aria-label="Xóa"></button>
        <div class="row g-2">
            <div class="col-12">
                <label class="form-label fw-500">Tên loại giá</label>
                <input type="text" class="form-control" data-field="label" placeholder="Ví dụ: Người lớn">
            </div>
            <div class="col-12">
                <label class="form-label fw-500">Mô tả (tùy chọn)</label>
                <textarea class="form-control" rows="2" data-field="description" placeholder="Mô tả chi tiết về loại giá này"></textarea>
            </div>
        </div>
    </div>
</template>

<template id="dynamic-pricing-template">
    <div class="dynamic-pricing-item border rounded p-3 bg-light-subtle position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-dynamic-price" aria-label="Xóa"></button>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label fw-500">Áp dụng cho loại giá</label>
                <select class="form-select" data-field="option_label">
                    <!-- Options will be populated by JS -->
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Giá</label>
                <input type="number" class="form-control" data-field="price" placeholder="1500000" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Từ ngày</label>
                <input type="date" class="form-control" data-field="start_date">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Đến ngày</label>
                <input type="date" class="form-control" data-field="end_date">
            </div>
            <div class="col-12">
                <label class="form-label fw-500">Ghi chú (tùy chọn)</label>
                <textarea class="form-control" rows="1" data-field="notes" placeholder="Ví dụ: Áp dụng cho ngày lễ 30/4"></textarea>
            </div>
        </div>
    </div>
</template>

<template id="itinerary-item-template">
    <div class="itinerary-item border rounded p-3 position-relative bg-light-subtle">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-itinerary-item" aria-label="Xóa"></button>
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label fw-500">Ngày / Chặng</label>
                <input type="text" name="tour_itinerary_day" data-field="day" class="form-control" placeholder="Ngày 1">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-500">Giờ bắt đầu</label>
                <input type="time" name="tour_itinerary_time_start" data-field="time_start" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-500">Giờ kết thúc</label>
                <input type="time" name="tour_itinerary_time_end[]" data-field="time_end" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label fw-500">Tiêu đề hoạt động</label>
                <input type="text" name="tour_itinerary_title" data-field="title" class="form-control" placeholder="Khởi hành từ Hà Nội">
            </div>
            <div class="col-12">
                <label class="form-label fw-500">Chi tiết</label>
                <textarea name="tour_itinerary_description" data-field="description" class="form-control" rows="3" placeholder="Tham quan, ăn uống, trải nghiệm..."></textarea>
            </div>
        </div>
    </div>
</template>

<!-- Image Viewer Modal -->
<div id="image-viewer-modal" class="modal-viewer" style="display:none;">
    <span class="close-viewer">&times;</span>
    <img class="modal-viewer-content" id="modal-image">
</div>

<style>
    .image-preview-card {
        position: relative;
    }

    .image-preview-card .actions-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }

    .image-preview-card:hover .actions-overlay {
        opacity: 1;
    }

    .actions-overlay .action-btn {
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transition: background 0.2s;
    }

    .actions-overlay .action-btn:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    /* Modal Styles */
    .modal-viewer {
        position: fixed;
        z-index: 9999;
        padding-top: 50px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
    }

    .modal-viewer-content {
        margin: auto;
        display: block;
        width: auto;
        height: auto;
        max-width: 90%;
        max-height: 90%;
    }

    .close-viewer {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }
</style>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
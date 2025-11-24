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
                                <label for="name" class="form-label fw-500">Tên Tour</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên tour">
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-500">Danh Mục Tour</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">-- Chọn Danh Mục Tour --</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="supplier_id" class="form-label fw-500">Nhà Cung Cấp</label>
                                <select class="form-select" id="supplier_id" name="supplier_id">
                                    <option value="">-- Chọn Nhà Cung Cấp --</option>
                                    <?php if (!empty($suppliers)): ?>
                                        <?php foreach ($suppliers as $s): ?>
                                            <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars($s['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="base_price" class="form-label fw-500">Giá Cơ Bản</label>
                                <input type="number" class="form-control" id="base_price" name="base_price" placeholder="Nhập giá cơ bản" min="0" step="1">
                                <small class="text-muted">Đơn giá mặc định áp dụng khi không có gói riêng.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin khác -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-align-left"></i> Mô tả
                            </h5>
                        </div>
                        <div class="card-body">
                            <label for="description" class="form-label fw-500">Nhập mô tả</label>
                            <input type="hidden" id="input-description" name="description">
                            <div id="editor-description" class="quill-editor"></div>
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
                            <div id="pricing-tier-list" class="d-flex flex-column gap-3" data-initial="[]"></div>
                            <template id="pricing-tier-template">
                                <div class="pricing-tier-item border rounded p-3 bg-light-subtle position-relative">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-pricing-tier" aria-label="Xóa"></button>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label fw-500">Nhóm khách / Gói</label>
                                            <input type="text" class="form-control" name="tour_pricing_options_label" data-field="label" placeholder="Người lớn">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-500">Giá áp dụng</label>
                                            <input type="number" class="form-control" name="tour_pricing_options_price" data-field="price" placeholder="1500000" min="0">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-500">Ghi chú dịch vụ</label>
                                            <textarea class="form-control" rows="2" name="tour_pricing_options_description" data-field="description" placeholder="Bao gồm ăn sáng, xe đưa đón sân bay"></textarea>
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
                            <div id="itinerary-list" class="d-flex flex-column gap-3" data-initial="[]"></div>
                            <button type="button" class="btn btn-outline-primary w-100" id="add-itinerary-item">
                                <i class="fas fa-plus"></i> Thêm ngày / hoạt động
                            </button>
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
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-images"></i> Hình ảnh
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="image-drop-zone" class="p-4 bg-light rounded border-dashed text-center" style="cursor: pointer;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                                <p class="text-muted small mt-2 mb-0">Kéo và thả ảnh vào đây, hoặc nhấp để chọn</p>
                                <p class="text-muted small">Ảnh đầu tiên sẽ là ảnh đại diện. Tối đa 10 ảnh.</p>
                            </div>
                            <!-- Hidden file inputs to store files for submission -->
                            <input type="file" id="file-input-handler" class="d-none" multiple accept="image/*">
                            <input type="file" name="image_url[]" id="gallery-images-input" class="d-none" multiple>

                            <div id="image-preview-container" class="row g-2 mt-3"></div>
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
                            <div id="partner-list" class="d-flex flex-column gap-3" data-initial="[]"></div>
                            <template id="partner-item-template">
                                <div class="partner-item border rounded p-3 bg-light-subtle position-relative">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 text-danger remove-partner-item" aria-label="Xóa"></button>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Loại dịch vụ</label>
                                            <select class="form-select" name="service_type" data-field="service_type">
                                                <option value="hotel">Khách sạn</option>
                                                <option value="transport">Vận chuyển</option>
                                                <option value="restaurant">Nhà hàng</option>
                                                <option value="ticket">Vé tham quan</option>
                                                <option value="other">Khác</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Đối tác</label>
                                            <input type="text" class="form-control" name="tour_partners_name" data-field="name" placeholder="The Cliff Resort">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-500">Liên hệ</label>
                                            <input type="text" class="form-control" name="tour_partners_contract" data-field="contact" placeholder="Mr A - 098xxx">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-500">Ghi chú</label>
                                            <textarea class="form-control" rows="2" name="tour_partners_notes" data-field="notes" placeholder="Yêu cầu đặt trước 3 ngày..."></textarea>
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
                    <input type="hidden" id="input-policy" name="policy">
                    <div id="editor-policy" class="quill-editor"></div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-3 d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo mới
                </button>
                <a href="?action=tours" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Hủy
                </a>
            </div>
        </form>
</main>

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
    }

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
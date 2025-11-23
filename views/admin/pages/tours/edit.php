<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Chỉnh sửa Tour</h1>
            <p class="text-muted">Cập nhật thông tin cho tour "<?= htmlspecialchars($tour['name']) ?>"</p>
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
            <!-- Container for tracking deleted image URLs -->
            <div id="deleted-images-container"></div>
            <!-- Hidden input to track if an existing gallery image is promoted to primary -->
            <input type="hidden" name="new_primary_image_url" id="new-primary-image-url">

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
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên tour" value="<?= htmlspecialchars($tour['name']) ?>">
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-500">Danh Mục Tour</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">-- Chọn Danh Mục Tour --</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['id']) ?>" <?= ($tour['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
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
                                            <option value="<?= htmlspecialchars($s['id']) ?>" <?= $tour['supplier_id'] == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="base_price" class="form-label fw-500">Giá Cơ Bản</label>
                                <input type="number" class="form-control" id="base_price" name="base_price" placeholder="Nhập giá cơ bản" min="0" step="50000" value="<?= htmlspecialchars($tour['base_price']) ?>">
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
                            <input type="hidden" id="input-description" name="description" value='<?= htmlspecialchars($tour['description'] ?? '', ENT_QUOTES) ?>'>
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
                            <div id="pricing-tier-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($pricingOptions, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>'></div>
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
                            <div id="itinerary-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($itinerarySchedule, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>'></div>
                            <button type="button" class="btn btn-outline-primary w-100" id="add-itinerary-item">
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
                            <input type="file" name="image" id="main-image-input" class="d-none">
                            <input type="file" name="gallery_images[]" id="gallery-images-input" class="d-none" multiple>

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
                            <div id="partner-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($partnerServices, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>'></div>
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
                    <div id="editor-policy" class="quill-editor"></div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-3 d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="?action=tours" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Hủy
                </a>
            </div>
        </form>
    </div>
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
        transition: 0.3s;
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- DOM Elements ---
        const dropZone = document.getElementById('image-drop-zone');
        const fileInput = document.getElementById('file-input-handler');
        const previewContainer = document.getElementById('image-preview-container');
        const mainImageInput = document.getElementById('main-image-input');
        const galleryImagesInput = document.getElementById('gallery-images-input');
        const deletedContainer = document.getElementById('deleted-images-container');
        const newPrimaryInput = document.getElementById('new-primary-image-url');
        const modal = document.getElementById('image-viewer-modal');
        const modalImg = document.getElementById('modal-image');
        const closeModal = document.querySelector('.close-viewer');

        // --- State ---
        // `imageItems` holds the master list of images. Items can be a string (URL for existing) or a File object (for new).
        let imageItems = [];

        // --- Initial Data (from PHP) ---
        const existingImagesData = <?= json_encode($allImages, JSON_UNESCAPED_SLASHES) ?>;

        function initializeImages() {
            // The PHP code already ensures the primary image is first.
            imageItems = existingImagesData.map(img => img.url);
            updatePreviews();
        }

        // --- Event Listeners ---
        dropZone.addEventListener('click', () => fileInput.click());
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-primary');
        });
        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-primary');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-primary');
            const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
            handleFiles(files);
        });
        fileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files).filter(file => file.type.startsWith('image/'));
            handleFiles(files);
            fileInput.value = ''; // Reset for next selection
        });
        closeModal.addEventListener('click', () => modal.style.display = "none");
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });

        // --- Core Functions ---
        function handleFiles(files) {
            const newFiles = files.slice(0, 10 - imageItems.length);
            imageItems = [...imageItems, ...newFiles];
            updatePreviews();
            updateFormInputs();
        }

        function updatePreviews() {
            previewContainer.innerHTML = '';
            imageItems.forEach((item, index) => {
                const isExisting = typeof item === 'string';
                const imgSrc = isExisting ? item : URL.createObjectURL(item);

                const previewWrapper = document.createElement('div');
                previewWrapper.className = 'col-6 col-md-4 col-lg-3';
                const card = document.createElement('div');
                card.className = 'card h-100 image-preview-card';
                const img = document.createElement('img');
                img.src = imgSrc;
                img.className = 'card-img-top object-fit-cover';
                img.style.height = '120px';
                if (!isExisting) {
                    img.onload = () => URL.revokeObjectURL(img.src); // Clean up memory
                }

                const overlay = createOverlay(index, imgSrc);
                card.appendChild(img);
                card.appendChild(overlay);

                if (index === 0) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-primary position-absolute top-0 start-0 m-1';
                    badge.textContent = 'Ảnh đại diện';
                    card.appendChild(badge);
                }

                previewWrapper.appendChild(card);
                previewContainer.appendChild(previewWrapper);
            });
        }

        function createOverlay(index, imgSrc) {
            const overlay = document.createElement('div');
            overlay.className = 'actions-overlay';

            const viewBtn = document.createElement('i');
            viewBtn.className = 'fas fa-eye action-btn';
            viewBtn.title = 'Xem ảnh';
            viewBtn.onclick = () => {
                modalImg.src = imgSrc;
                modal.style.display = "block";
            };
            overlay.appendChild(viewBtn);

            if (index > 0) {
                const primaryBtn = document.createElement('i');
                primaryBtn.className = 'fas fa-star action-btn';
                primaryBtn.title = 'Chọn làm ảnh đại diện';
                primaryBtn.onclick = () => setAsPrimary(index);
                overlay.appendChild(primaryBtn);
            }

            const removeBtn = document.createElement('i');
            removeBtn.className = 'fas fa-trash-alt action-btn text-danger';
            removeBtn.title = 'Xóa ảnh';
            removeBtn.onclick = () => removeItem(index);
            overlay.appendChild(removeBtn);

            return overlay;
        }

        function removeItem(indexToRemove) {
            const item = imageItems[indexToRemove];
            if (typeof item === 'string') {
                // It's an existing image URL, mark for deletion on the backend
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_images[]';
                hiddenInput.value = item;
                deletedContainer.appendChild(hiddenInput);
            }
            imageItems.splice(indexToRemove, 1);
            updatePreviews();
            updateFormInputs();
        }

        function setAsPrimary(indexToMakePrimary) {
            if (indexToMakePrimary > 0 && indexToMakePrimary < imageItems.length) {
                const item = imageItems.splice(indexToMakePrimary, 1)[0];
                imageItems.unshift(item);

                // If the new primary is an existing image, record its URL for the backend.
                // Otherwise, clear it, as the new primary is a new file upload.
                newPrimaryInput.value = (typeof item === 'string') ? item : '';

                updatePreviews();
                updateFormInputs();
            }
        }

        function updateFormInputs() {
            const mainImageFiles = new DataTransfer();
            const galleryImageFiles = new DataTransfer();

            // Check if the primary image is a new file upload
            if (imageItems.length > 0 && imageItems[0] instanceof File) {
                mainImageFiles.items.add(imageItems[0]);
            }
            mainImageInput.files = mainImageFiles.files;

            // Gather all other new file uploads for the gallery
            const newGalleryFiles = imageItems.slice(1).filter(item => item instanceof File);
            newGalleryFiles.forEach(file => galleryImageFiles.items.add(file));
            galleryImagesInput.files = galleryImageFiles.files;
        }

        // --- Initialization ---
        initializeImages();
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>

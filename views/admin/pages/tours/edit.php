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

        <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=tours/update" enctype="multipart/form-data" class="tour-form">
            <input type="hidden" name="id" value="<?= htmlspecialchars($tour['id']) ?>">
            <?php
            // remember where the user came from so we can return them to the same list state after saving
            $referer = $_GET['return_to'] ?? ($_SERVER['HTTP_REFERER'] ?? BASE_URL_ADMIN . '&action=tours');
            ?>
            <input type="hidden" name="return_to" value="<?= htmlspecialchars($referer) ?>">
            <!-- Container for tracking deleted image URLs -->
            <div id="deleted-images-container"></div>
            <!-- Hidden input to track if an existing gallery image is promoted to primary -->
            <input type="hidden" name="new_primary_image_url" id="new-primary-image-url">

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
                                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($tour['name']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($tour['description']) ?></textarea>
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
                                <div id="pricing-options-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($pricingOptions, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>'></div>
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
                                <div id="dynamic-pricing-list" class="d-flex flex-column gap-3" data-initial='<?= json_encode($dynamicPricing, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>'></div>
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
                            <div id="itinerary-list" class="d-flex flex-column gap-3 mb-3" data-initial='<?= json_encode($itinerarySchedule, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>'></div>
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
                                                <input class="form-check-input" type="checkbox" name="policies[]" value="<?= $policy['id'] ?>" id="policy_<?= $policy['id'] ?>" <?= in_array($policy['id'], $assignedPolicyIds) ? 'checked' : '' ?>>
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
                                    <i class="fas fa-save"></i> Lưu thay đổi
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
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $tour['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Giá khởi điểm (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="base_price" class="form-control" required min="0" value="<?= $tour['base_price'] ?>">
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
                            
                            <input type="file" id="file-input-handler-edit" class="d-none" multiple accept="image/*">
                            <input type="file" name="image" id="main-image-input" class="d-none">
                            <input type="file" name="gallery_images[]" id="gallery-images-input-edit" class="d-none" multiple>

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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- DOM Elements ---
        const dropZone = document.getElementById('image-drop-zone');
        // Use page-specific input IDs to avoid collisions with global create-page script
        const fileInput = document.getElementById('file-input-handler-edit');
        const previewContainer = document.getElementById('image-preview-container');
        const mainImageInput = document.getElementById('main-image-input');
        const galleryImagesInput = document.getElementById('gallery-images-input-edit');
        const deletedContainer = document.getElementById('deleted-images-container');
        const newPrimaryInput = document.getElementById('new-primary-image-url');
        // Note: viewing handled by global lightbox (provided by assets/admin/js/tours.js)

        // --- State ---
        // `imageItems` holds the master list of images.
        // Existing images are stored as objects { id, url }, new uploads are File objects.
        let imageItems = [];

        // --- Initial Data (from PHP) ---
        const existingImagesData = <?= json_encode($allImages, JSON_UNESCAPED_SLASHES) ?> || [];

        function initializeImages() {
            // Normalize existing images into objects {id, url} so we keep IDs for deletes/primary selection.
            // Support different keys coming from backend: prefer `id` and `url`, fallback to `image_url` or `path`.
            imageItems = existingImagesData.map(img => ({
                id: (img && (img.id || img.image_id || img.imageId)) ? (img.id || img.image_id || img.imageId) : null,
                // public URL for preview
                url: (img && (img.url || img.image_url)) ? (img.url || img.image_url) : '',
                // relative DB path (e.g. 'tours/xxx.jpg') for backend operations
                path: (img && (img.path || img.image_url)) ? (img.path || img.image_url) : ''
            }));

            updatePreviews();
            updateFormInputs();
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
        // no-op: lightbox handled in shared JS

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
                // Determine source for preview:
                // - File objects (new uploads) -> createObjectURL
                // - Existing image objects -> item.url
                // - Plain string (edge cases) -> use string as src
                let imgSrc = '';
                const isFile = item instanceof File;
                if (isFile) {
                    imgSrc = URL.createObjectURL(item);
                } else if (typeof item === 'object' && item.url) {
                    imgSrc = item.url;
                } else if (typeof item === 'string') {
                    imgSrc = item;
                }

                const previewWrapper = document.createElement('div');
                previewWrapper.className = 'col-6 col-md-4 col-lg-3';
                const card = document.createElement('div');
                card.className = 'card h-100 image-preview-card';
                const img = document.createElement('img');
                img.src = imgSrc;
                img.className = 'card-img-top object-fit-cover';
                img.style.height = '120px';
                // revoke object URLs for File objects after load to free memory
                if (isFile) {
                    img.onload = () => URL.revokeObjectURL(img.src);
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
            viewBtn.onclick = (e) => {
                e.preventDefault();
                // Use global lightbox instance if available
                try {
                    const imgs = Array.from(previewContainer.querySelectorAll('img.card-img-top')).map(i => i.src);
                    if (window.tourLightbox) {
                        window.tourLightbox.open(imgs, index);
                        return;
                    }
                } catch (err) {
                    // fallback: do nothing
                }
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
            // If it's an existing image (object with id or url), send its id if available, otherwise send the url.
            if (typeof item === 'object') {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_images[]';
                // prefer numeric id, otherwise send DB-relative path so backend can match image_url
                hiddenInput.value = item.id ? item.id : (item.path || item.url);
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

                // If the new primary is an existing image, record its ID (prefer) or URL for the backend.
                if (typeof item === 'object') {
                    // prefer id; if not available send DB-relative path (not full public URL)
                    newPrimaryInput.value = item.id ? item.id : (item.path || item.url);
                } else {
                    // new file upload -> backend should detect uploaded main image from `image` input
                    newPrimaryInput.value = '';
                }

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
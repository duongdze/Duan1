<?php
$version = $version ?? null;
$isEdit = isset($version);
$formAction = $isEdit
    ? "?action=tours_versions/update&id={$version['id']}&tour_id={$tour['id']}"
    : "?action=tours_versions/store";
$title = $isEdit ? 'Chỉnh sửa phiên bản' : 'Thêm phiên bản mới';

// Get old input or use version data
$formData = $_SESSION['old_input'] ?? ($version ?? []);
$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['form_errors']);
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
            <a href="<?= BASE_URL_ADMIN ?>&action=tours/detail&id=<?= $tour['id'] ?>"><?= htmlspecialchars($tour['name']) ?></a>
            <span class="separator">/</span>
            <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions&tour_id=<?= $tour['id'] ?>">Phiên bản</a>
            <span class="separator">/</span>
            <span class="active"><?= $isEdit ? 'Chỉnh sửa' : 'Thêm mới' ?></span>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card-modern">
                    <div class="card-header">
                        <h2 class="card-title-modern"><?= $title ?></h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['error'] ?>
                                <?php unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= $formAction ?>" class="needs-validation" novalidate>
                            <?php if ($isEdit): ?>
                                <input type="hidden" name="id" value="<?= $version['id'] ?>">
                                <input type="hidden" name="_method" value="PUT">
                            <?php endif; ?>

                            <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Tên phiên bản <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control <?= isset($formErrors['name']) ? 'is-invalid' : '' ?>"
                                            id="name"
                                            name="name"
                                            value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                                            required>
                                        <?php if (isset($formErrors['name'])): ?>
                                            <div class="invalid-feedback"><?= $formErrors['name'] ?></div>
                                        <?php else: ?>
                                            <div class="form-text">Ví dụ: Mùa hè 2023, Giáng sinh 2023, v.v.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active" <?= ($formData['status'] ?? '') === 'active' ? 'selected' : '' ?>>Kích hoạt</option>
                                            <option value="inactive" <?= ($formData['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Tạm ẩn</option>
                                        </select>
                                        <div class="form-text">Chọn trạng thái cho phiên bản</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea class="form-control"
                                        id="description"
                                        name="description"
                                        rows="4"
                                        placeholder="Nhập mô tả chi tiết về phiên bản này..."><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                                    <div class="form-text">Mô tả các đặc điểm, thay đổi hoặc thông tin quan trọng về phiên bản này</div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="mb-4">
                                <div class="form-group">
                                    <label class="form-label">Xem trước</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <h5 class="mb-0 me-2" id="preview-name"><?= htmlspecialchars($formData['name'] ?? 'Tên phiên bản') ?></h5>
                                                <span class="badge bg-<?= ($formData['status'] ?? 'inactive') === 'active' ? 'success' : 'secondary' ?>" id="preview-status">
                                                    <?= ($formData['status'] ?? 'inactive') === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' ?>
                                                </span>
                                            </div>
                                            <p class="text-muted mb-0" id="preview-description">
                                                <?= !empty($formData['description']) ? nl2br(htmlspecialchars($formData['description'])) : 'Chưa có mô tả' ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions&tour_id=<?= $tour['id'] ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i> Lưu lại
                                    </button>
                                    <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions&tour_id=<?= $tour['id'] ?>" class="btn btn-outline-secondary">
                                        Hủy
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time preview
        const nameInput = document.getElementById('name');
        const statusSelect = document.getElementById('status');
        const descriptionTextarea = document.getElementById('description');

        const previewName = document.getElementById('preview-name');
        const previewStatus = document.getElementById('preview-status');
        const previewDescription = document.getElementById('preview-description');

        function updatePreview() {
            // Update name
            previewName.textContent = nameInput.value || 'Tên phiên bản';

            // Update status
            const status = statusSelect.value;
            previewStatus.textContent = status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn';
            previewStatus.className = 'badge bg-' + (status === 'active' ? 'success' : 'secondary');

            // Update description
            const description = descriptionTextarea.value;
            previewDescription.innerHTML = description ?
                description.replace(/\n/g, '<br>') : 'Chưa có mô tả';
        }

        nameInput.addEventListener('input', updatePreview);
        statusSelect.addEventListener('change', updatePreview);
        descriptionTextarea.addEventListener('input', updatePreview);

        // Form validation
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
    });
</script>
<?php
$category = $category ?? null;
$isEdit = isset($category);
$formAction = $isEdit
    ? "?action=tours_categories/update&id={$category['id']}"
    : "?action=tours_categories/store";
$title = $title ?? ($isEdit ? 'Chỉnh sửa Danh mục' : 'Thêm Danh mục Mới');

// Get old input or use category data
$formData = $_SESSION['old_input'] ?? ($category ?? []);
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
            <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories">Quản lý Danh mục Tour</a>
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
                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                <input type="hidden" name="_method" value="PUT">
                            <?php endif; ?>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control <?= isset($formErrors['name']) ? 'is-invalid' : '' ?>"
                                            id="name"
                                            name="name"
                                            value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                                            required>
                                        <?php if (isset($formErrors['name'])): ?>
                                            <div class="invalid-feedback"><?= $formErrors['name'] ?></div>
                                        <?php else: ?>
                                            <div class="form-text">Ví dụ: Tour Miền Bắc, Tour Châu Á, v.v.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug" class="form-label">Slug (URL)</label>
                                        <input type="text"
                                            class="form-control"
                                            id="slug"
                                            name="slug"
                                            value="<?= htmlspecialchars($formData['slug'] ?? '') ?>"
                                            placeholder="Để trống để tự động tạo">
                                        <div class="form-text">URL thân thiện cho danh mục. Ví dụ: tour-mien-bac</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="type" class="form-label">Loại hình</label>
                                        <select class="form-select" id="type" name="type">
                                            <option value="domestic" <?= ($formData['type'] ?? '') === 'domestic' ? 'selected' : '' ?>>Tour trong nước</option>
                                            <option value="international" <?= ($formData['type'] ?? '') === 'international' ? 'selected' : '' ?>>Tour quốc tế</option>
                                            <option value="customized" <?= ($formData['type'] ?? '') === 'customized' ? 'selected' : '' ?>>Tour theo yêu cầu</option>
                                        </select>
                                        <div class="form-text">Chọn loại hình tour cho danh mục này</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="parent_id" class="form-label">Danh mục cha</label>
                                        <select class="form-select" id="parent_id" name="parent_id">
                                            <option value="">-- Không có --</option>
                                            <?php if (!empty($parentCategories)): ?>
                                                <?php foreach ($parentCategories as $parent): ?>
                                                    <?php if ($isEdit && $parent['id'] == $category['id']) continue; ?>
                                                    <option value="<?= $parent['id'] ?>"
                                                        <?= ($formData['parent_id'] ?? '') == $parent['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($parent['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="form-text">Chọn danh mục cha nếu đây là danh mục con</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="sort_order" class="form-label">Thứ tự</label>
                                        <input type="number"
                                            class="form-control"
                                            id="sort_order"
                                            name="sort_order"
                                            value="<?= $formData['sort_order'] ?? 0 ?>"
                                            min="0">
                                        <div class="form-text">Sắp xếp theo thứ tự</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="is_active" class="form-label">Trạng thái</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="is_active"
                                                name="is_active"
                                                <?= ($formData['is_active'] ?? 1) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_active">
                                                <?= ($formData['is_active'] ?? 1) ? 'Đang hoạt động' : 'Tạm ẩn' ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="icon" class="form-label">Icon</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-icons"></i></span>
                                        <input type="text"
                                            class="form-control"
                                            id="icon"
                                            name="icon"
                                            value="<?= htmlspecialchars($formData['icon'] ?? '') ?>"
                                            placeholder="fas fa-mountain">
                                    </div>
                                    <div class="form-text">
                                        Lớp icon Font Awesome. Ví dụ: fas fa-mountain, fas fa-umbrella-beach, fas fa-city
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
                                        placeholder="Nhập mô tả chi tiết về danh mục này..."><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                                    <div class="form-text">Mô tả các đặc điểm, điểm nổi bật của loại hình tour trong danh mục này</div>
                                </div>
                            </div>

                            <!-- Preview Section -->
                            <div class="mb-4">
                                <div class="form-group">
                                    <label class="form-label">Xem trước</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-2">
                                                    <i id="preview-icon" class="<?= !empty($formData['icon']) ? htmlspecialchars($formData['icon']) : 'fas fa-folder' ?> text-primary"></i>
                                                </div>
                                                <h5 class="mb-0 me-2" id="preview-name"><?= htmlspecialchars($formData['name'] ?? 'Tên danh mục') ?></h5>
                                                <?php
                                                $typeLabels = [
                                                    'domestic' => 'Tour trong nước',
                                                    'international' => 'Tour quốc tế',
                                                    'customized' => 'Tour theo yêu cầu'
                                                ];
                                                $typeColors = [
                                                    'domestic' => 'primary',
                                                    'international' => 'info',
                                                    'customized' => 'warning'
                                                ];
                                                $type = $formData['type'] ?? 'domestic';
                                                ?>
                                                <span class="badge bg-<?= $typeColors[$type] ?>"><?= $typeLabels[$type] ?></span>
                                            </div>
                                            <p class="text-muted mb-0" id="preview-description">
                                                <?= !empty($formData['description']) ? nl2br(htmlspecialchars($formData['description'])) : 'Chưa có mô tả' ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i> Lưu lại
                                    </button>
                                    <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories" class="btn btn-outline-secondary">
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
        const slugInput = document.getElementById('slug');
        const typeSelect = document.getElementById('type');
        const iconInput = document.getElementById('icon');
        const descriptionTextarea = document.getElementById('description');

        const previewName = document.getElementById('preview-name');
        const previewIcon = document.getElementById('preview-icon');
        const previewDescription = document.getElementById('preview-description');

        function updatePreview() {
            // Update name
            previewName.textContent = nameInput.value || 'Tên danh mục';

            // Update icon
            const iconValue = iconInput.value.trim();
            if (iconValue) {
                previewIcon.className = iconValue + ' text-primary';
            } else {
                previewIcon.className = 'fas fa-folder text-primary';
            }

            // Update description
            const description = descriptionTextarea.value;
            previewDescription.innerHTML = description ?
                description.replace(/\n/g, '<br>') : 'Chưa có mô tả';
        }

        // Auto-generate slug from name
        function generateSlug(name) {
            return name.toLowerCase()
                .trim()
                .replace(/[áàảạãăắằẳẵặâấầẩẫậ]/g, 'a')
                .replace(/[éèẻẽẹêếềểễệ]/g, 'e')
                .replace(/[íìỉĩị]/g, 'i')
                .replace(/[óòỏõọôốồổỗộơớờởỡợ]/g, 'o')
                .replace(/[úùủũụưứừửữự]/g, 'u')
                .replace(/[ýỳỷỹỵ]/g, 'y')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
        }

        nameInput.addEventListener('input', function() {
            updatePreview();
            // Auto-generate slug if slug is empty
            if (!slugInput.value.trim()) {
                slugInput.value = generateSlug(this.value);
            }
        });

        iconInput.addEventListener('input', updatePreview);
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
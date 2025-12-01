<?php
$version = $version ?? null;
$isEdit = isset($version);
$formAction = $isEdit
    ? "?mode=admin&action=tours_versions/update&id={$version['id']}"
    : "?mode=admin&action=tours_versions/store";
$title = $isEdit ? 'Chỉnh sửa phiên bản' : 'Thêm phiên bản mới';

// Get old input or use version data
$formData = $_SESSION['old_input'] ?? ($version ?? []);
$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['form_errors']);
?>

<?php include_once PATH_VIEW_ADMIN . 'default/header.php'; ?>
<?php include_once PATH_VIEW_ADMIN . 'default/sidebar.php'; ?>

<main class="dashboard">
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
                        <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="breadcrumb-link">
                            <span>Phiên bản Tour</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current"><?= $isEdit ? 'Chỉnh sửa' : 'Thêm mới' ?></span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-<?= $isEdit ? 'edit' : 'plus-circle' ?> title-icon"></i>
                            <?= $title ?>
                        </h1>
                        <p class="page-subtitle">
                            <?= $isEdit ? 'Cập nhật thông tin phiên bản tour' : 'Tạo phiên bản tour mới cho hệ thống' ?>
                        </p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="btn btn-modern btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </header>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-modern alert-success alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span><?= $_SESSION['success'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-modern alert-danger alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <span><?= $_SESSION['error'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Form Section -->
        <section class="form-section">
            <div class="form-container-enhanced">
                <form method="POST" action="<?= $formAction ?>" class="form-modern" id="versionForm">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $version['id'] ?>">
                        <input type="hidden" name="_method" value="PUT">
                    <?php endif; ?>

                    <div class="form-grid">
                        <!-- Basic Information -->
                        <div class="form-section-group">
                            <div class="section-subtitle">
                                <h3 class="section-subtitle-text">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Thông tin cơ bản
                                </h3>
                                <div class="section-subtitle-description">
                                    Các thông tin chính của phiên bản tour
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group form-group-lg">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-tag me-1"></i>
                                        Tên phiên bản <span class="required">*</span>
                                    </label>
                                    <div class="form-input-wrapper">
                                        <input type="text"
                                            class="form-control form-control-modern <?= isset($formErrors['name']) ? 'is-invalid' : '' ?>"
                                            id="name"
                                            name="name"
                                            value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                                            placeholder="Ví dụ: Mùa hè 2023, Giáng sinh 2023, v.v."
                                            required>
                                        <div class="form-input-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    </div>
                                    <?php if (isset($formErrors['name'])): ?>
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <?= $formErrors['name'] ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="form-help">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            Đặt tên phiên bản dễ nhận biết, ví dụ: "Mùa hè 2023" hoặc "Giáng sinh đặc biệt"
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>
                                        Trạng thái
                                    </label>
                                    <div class="form-input-wrapper">
                                        <select class="form-select form-select-modern" id="status" name="status">
                                            <option value="active" <?= ($formData['status'] ?? '') === 'active' ? 'selected' : '' ?>>
                                                <i class="fas fa-check-circle me-1"></i>
                                                Đang hoạt động
                                            </option>
                                            <option value="inactive" <?= ($formData['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                                <i class="fas fa-pause-circle me-1"></i>
                                                Tạm ẩn
                                            </option>
                                        </select>
                                        <div class="form-input-icon">
                                            <i class="fas fa-toggle-on"></i>
                                        </div>
                                    </div>
                                    <div class="form-help">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Chọn trạng thái để hiển thị hoặc ẩn phiên bản
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-section-group">
                            <div class="section-subtitle">
                                <h3 class="section-subtitle-text">
                                    <i class="fas fa-align-left me-2"></i>
                                    Mô tả chi tiết
                                </h3>
                                <div class="section-subtitle-description">
                                    Mô tả các đặc điểm và thông tin quan trọng về phiên bản
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <i class="fas fa-file-alt me-1"></i>
                                    Mô tả phiên bản
                                </label>
                                <div class="form-textarea-wrapper">
                                    <textarea class="form-control form-control-modern"
                                        id="description"
                                        name="description"
                                        rows="5"
                                        placeholder="Nhập mô tả chi tiết về phiên bản này..."><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                                    <div class="form-textarea-counter">
                                        <span id="descCounter">0</span> / 1000 ký tự
                                    </div>
                                </div>
                                <div class="form-help">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Mô tả các thay đổi, đặc điểm nổi bật hoặc thông tin quan trọng của phiên bản
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="form-section-group">
                            <div class="section-subtitle">
                                <h3 class="section-subtitle-text">
                                    <i class="fas fa-eye me-2"></i>
                                    Xem trước
                                </h3>
                                <div class="section-subtitle-description">
                                    Xem trước thông tin phiên bản sẽ hiển thị
                                </div>
                            </div>

                            <div class="preview-card-modern">
                                <div class="preview-header">
                                    <div class="preview-info">
                                        <h4 class="preview-name" id="preview-name"><?= htmlspecialchars($formData['name'] ?? 'Tên phiên bản') ?></h4>
                                        <div class="preview-meta">
                                            <span class="preview-id">#<?= str_pad($version['id'] ?? '0000', 4, '0', STR_PAD_LEFT) ?></span>
                                            <span class="preview-date">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                <?= date('d/m/Y') ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="preview-status">
                                        <span class="status-badge status-<?= ($formData['status'] ?? 'inactive') ?>" id="preview-status">
                                            <i class="fas fa-<?= ($formData['status'] ?? 'inactive') === 'active' ? 'check' : 'pause' ?> me-1"></i>
                                            <?= ($formData['status'] ?? 'inactive') === 'active' ? 'Đang hoạt động' : 'Tạm ẩn' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="preview-body">
                                    <div class="preview-description">
                                        <p id="preview-description">
                                            <?= !empty($formData['description']) ? nl2br(htmlspecialchars($formData['description'])) : 'Chưa có mô tả' ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="form-actions-left">
                            <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="btn btn-modern btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Quay lại
                            </a>
                        </div>
                        <div class="form-actions-right">
                            <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions" class="btn btn-modern btn-outline-secondary me-2">
                                <i class="fas fa-times me-2"></i>
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-modern btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span id="submitText"><?= $isEdit ? 'Cập nhật' : 'Tạo mới' ?></span>
                            </button>
                        </div>
                    </div>

                    <!-- Auto-save Status -->
                    <div class="auto-save-status" id="autoSaveStatus">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        <span class="auto-save-text">Đã tự động lưu</span>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<style>
    /* Optimized Form Container */
    .form-container-enhanced {
        background: var(--tours-bg-primary, #ffffff);
        border: 1px solid var(--tours-border-light, #e9ecef);
        border-radius: var(--tours-radius-lg, 12px);
        box-shadow: var(--tours-shadow-sm, 0 2px 8px rgba(0, 0, 0, 0.04));
        overflow: hidden;
        position: relative;
    }

    .form-container-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
                var(--tours-primary, #0d6efd) 0%,
                var(--tours-success, #198754) 100%);
        opacity: 0.8;
    }

    .form-modern {
        padding: 32px;
    }

    /* Optimized Form Groups */
    .form-section-group {
        background: var(--tours-bg-secondary, #f8f9fa);
        border: 1px solid var(--tours-border-light, #e9ecef);
        border-radius: var(--tours-radius-md, 8px);
        padding: 24px;
        margin-bottom: 20px;
        position: relative;
    }

    .form-section-group::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--tours-primary, #0d6efd);
        opacity: 0.3;
    }

    .section-subtitle {
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--tours-border-light, #e9ecef);
    }

    .section-subtitle-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--tours-text-primary, #212529);
        margin: 0 0 6px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-subtitle-description {
        color: var(--tours-text-secondary, #6c757d);
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.4;
    }

    /* Optimized Form Controls */
    .form-control-modern,
    .form-select-modern {
        border: 2px solid var(--tours-border, #dee2e6);
        border-radius: var(--tours-radius-md, 8px);
        padding: 12px 16px 12px 44px;
        font-size: 0.95rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        background: var(--tours-bg-primary, #ffffff);
    }

    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: var(--tours-primary, #0d6efd);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        outline: none;
    }

    .form-control-modern.is-invalid {
        border-color: var(--tours-danger, #dc3545);
    }

    .form-control-modern.is-valid {
        border-color: var(--tours-success, #198754);
    }

    .form-input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--tours-text-secondary, #6c757d);
        font-size: 1rem;
        pointer-events: none;
    }

    .form-control-modern:focus+.form-input-icon,
    .form-select-modern:focus+.form-input-icon {
        color: var(--tours-primary, #0d6efd);
    }

    /* Optimized Textarea */
    .form-textarea-wrapper textarea {
        resize: vertical;
        min-height: 120px;
        border: 2px solid var(--tours-border, #dee2e6);
        border-radius: var(--tours-radius-md, 8px);
        padding: 16px;
        font-size: 0.95rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        background: var(--tours-bg-primary, #ffffff);
        font-family: inherit;
        line-height: 1.5;
    }

    .form-textarea-wrapper textarea:focus {
        border-color: var(--tours-primary, #0d6efd);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        outline: none;
    }

    /* Optimized Preview Card */
    .preview-card-modern {
        background: var(--tours-bg-secondary, #f8f9fa);
        border: 1px solid var(--tours-border-light, #e9ecef);
        border-radius: var(--tours-radius-md, 8px);
        overflow: hidden;
        transition: transform 0.15s ease;
    }

    .preview-card-modern:hover {
        transform: translateY(-2px);
    }

    .preview-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
                var(--tours-primary, #0d6efd) 0%,
                var(--tours-success, #198754) 100%);
        opacity: 0.6;
    }

    /* Optimized Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 32px;
        border-top: 1px solid var(--tours-border-light, #e9ecef);
        margin-top: 24px;
        background: var(--tours-bg-secondary, #f8f9fa);
    }

    /* Optimized Buttons */
    .btn-modern {
        padding: 10px 20px;
        border-radius: var(--tours-radius-md, 8px);
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-modern.btn-primary {
        background: var(--tours-primary, #0d6efd);
        border: none;
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: #0b5ed7;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    /* Remaining Form Elements */
    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group-lg {
        grid-column: span 2;
    }

    .form-label {
        font-weight: 600;
        color: var(--tours-text-primary, #212529);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-section-group::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--tours-primary, #0d6efd);
        opacity: 0.3;
    }

    .section-subtitle {
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--tours-border-light, #e9ecef);
    }

    .section-subtitle-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--tours-text-primary, #212529);
        margin: 0 0 6px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-subtitle-description {
        color: var(--tours-text-secondary, #6c757d);
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.4;
    }

    /* Optimized Form Controls */
    .form-control-modern,
    .form-select-modern {
        border: 2px solid var(--tours-border, #dee2e6);
        border-radius: var(--tours-radius-md, 8px);
        padding: 12px 16px 12px 44px;
        font-size: 0.95rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        background: var(--tours-bg-primary, #ffffff);
    }

    .form-control-modern:focus,
    .form-select-modern:focus {
        border-color: var(--tours-primary, #0d6efd);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        outline: none;
    }

    .form-control-modern.is-invalid {
        border-color: var(--tours-danger, #dc3545);
    }

    .form-control-modern.is-valid {
        border-color: var(--tours-success, #198754);
    }

    .form-input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--tours-text-secondary, #6c757d);
        font-size: 1rem;
        pointer-events: none;
    }

    .form-control-modern:focus+.form-input-icon,
    .form-select-modern:focus+.form-input-icon {
        color: var(--tours-primary, #0d6efd);
    }

    /* Optimized Textarea */
    .form-textarea-wrapper textarea {
        resize: vertical;
        min-height: 120px;
        border: 2px solid var(--tours-border, #dee2e6);
        border-radius: var(--tours-radius-md, 8px);
        padding: 16px;
        font-size: 0.95rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        background: var(--tours-bg-primary, #ffffff);
        font-family: inherit;
        line-height: 1.5;
    }

    .form-textarea-wrapper textarea:focus {
        border-color: var(--tours-primary, #0d6efd);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        outline: none;
    }

    /* Optimized Preview Card */
    .preview-card-modern {
        background: var(--tours-bg-secondary, #f8f9fa);
        border: 1px solid var(--tours-border-light, #e9ecef);
        border-radius: var(--tours-radius-md, 8px);
        overflow: hidden;
        transition: transform 0.15s ease;
    }

    .preview-card-modern:hover {
        transform: translateY(-2px);
    }

    .preview-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
                var(--tours-primary, #0d6efd) 0%,
                var(--tours-success, #198754) 100%);
        opacity: 0.6;
    }

    /* Optimized Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 32px;
        border-top: 1px solid var(--tours-border-light, #e9ecef);
        margin-top: 24px;
        background: var(--tours-bg-secondary, #f8f9fa);
    }

    /* Optimized Buttons */
    .btn-modern {
        padding: 10px 20px;
        border-radius: var(--tours-radius-md, 8px);
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-modern.btn-primary {
        background: var(--tours-primary, #0d6efd);
        border: none;
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: #0b5ed7;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    /* Remaining Form Elements */
    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group-lg {
        grid-column: span 2;
    }

    .form-label {
        font-weight: 600;
        color: var(--tours-text-primary, #212529);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .required {
        color: var(--tours-danger, #dc3545);
        margin-left: 4px;
    }

    .form-input-wrapper {
        position: relative;
    }

    .form-textarea-wrapper {
        position: relative;
    }

    .form-textarea-counter {
        position: absolute;
        bottom: 12px;
        right: 16px;
        font-size: 0.8rem;
        color: var(--tours-text-secondary, #6c757d);
        background: var(--tours-bg-primary, #ffffff);
        padding: 4px 8px;
        border-radius: 6px;
        transition: color 0.15s ease;
    }

    .form-textarea-counter.warning {
        color: var(--tours-warning, #ffc107);
    }

    .form-textarea-counter.danger {
        color: var(--tours-danger, #dc3545);
    }

    .form-help {
        font-size: 0.85rem;
        color: var(--tours-text-secondary, #6c757d);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .invalid-feedback {
        font-size: 0.85rem;
        color: var(--tours-danger, #dc3545);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Simple Preview Card Elements */
    .preview-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--tours-border-light, #e9ecef);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }

    .preview-info {
        flex: 1;
    }

    .preview-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--tours-text-primary, #212529);
        margin: 0 0 8px 0;
    }

    .preview-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .preview-id {
        font-family: 'Courier New', monospace;
        background: var(--tours-bg-primary, #ffffff);
        color: var(--tours-text-secondary, #6c757d);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        width: fit-content;
    }

    .preview-date {
        font-size: 0.85rem;
        color: var(--tours-text-secondary, #6c757d);
        display: flex;
        align-items: center;
    }

    .preview-status {
        flex-shrink: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: rgba(25, 135, 84, 0.1);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.2);
    }

    .status-inactive {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }

    .preview-body {
        padding: 20px 24px;
    }

    .preview-description p {
        color: var(--tours-text-secondary, #6c757d);
        line-height: 1.5;
        margin: 0;
    }

    /* Simple Form Actions Layout */
    .form-actions-left,
    .form-actions-right {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    /* Simple Auto-save Status */
    .auto-save-status {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--tours-bg-primary, #ffffff);
        border: 1px solid var(--tours-border-light, #e9ecef);
        border-radius: var(--tours-radius-md, 8px);
        padding: 8px 16px;
        box-shadow: var(--tours-shadow, 0 4px 12px rgba(0, 0, 0, 0.15));
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        z-index: 1000;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }

    .auto-save-status.show {
        opacity: 1;
        transform: translateY(0);
    }

    .auto-save-status.saving {
        color: var(--tours-warning, #ffc107);
    }

    .auto-save-status.error {
        color: var(--tours-danger, #dc3545);
    }

    .auto-save-status.success {
        color: var(--tours-success, #198754);
    }

    /* Simple Loading States */
    .btn.loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 16px;
        height: 16px;
        margin: -8px 0 0 -8px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Simple Keyboard Shortcuts */
    .keyboard-hint {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .keyboard-hint kbd {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 6px;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.8rem;
        margin: 0 2px;
    }

    .keyboard-hint.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Simple Progress Indicator */
    .form-progress {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--tours-border-light, #e9ecef);
        border-radius: var(--tours-radius-lg, 12px) var(--tours-radius-lg, 12px) 0 0;
        overflow: hidden;
    }

    .form-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--tours-primary, #0d6efd), var(--tours-success, #198754));
        width: 0%;
        transition: width 0.3s ease;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .form-modern {
            padding: 20px;
        }

        .form-section-group {
            padding: 20px;
            margin-bottom: 16px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .form-group-lg {
            grid-column: span 1;
        }

        .form-actions {
            flex-direction: column;
            gap: 12px;
            padding: 20px;
        }

        .form-actions-left,
        .form-actions-right {
            justify-content: center;
        }

        .preview-header {
            flex-direction: column;
            gap: 16px;
        }

        .auto-save-status {
            bottom: 16px;
            right: 16px;
            font-size: 0.8rem;
            padding: 10px 16px;
        }

        .keyboard-hint {
            display: none;
        }

        .badge-modern {
            padding: 8px 16px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .section-header-enhanced {
            padding: 20px 24px;
        }

        .form-modern {
            padding: 20px;
        }

        .form-section-group {
            padding: 20px;
        }

        .section-title-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .section-heading {
            font-size: 1.3rem;
        }

        .form-control-modern,
        .form-select-modern {
            padding: 14px 18px 14px 46px;
            font-size: 0.95rem;
        }

        .form-textarea-wrapper textarea {
            padding: 16px;
            min-height: 120px;
        }

        .btn-modern {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }

    /* Responsive Updates */
    @media (max-width: 768px) {
        .section-header-enhanced {
            padding: 24px 28px;
            margin-bottom: 24px;
        }

        .section-header-content {
            flex-direction: column;
            gap: 20px;
            align-items: stretch;
        }

        .section-title {
            gap: 16px;
        }

        .section-title-icon {
            width: 48px;
            height: 48px;
            font-size: 1.2rem;
        }

        .section-heading {
            font-size: 1.5rem;
        }

        .form-modern {
            padding: 28px;
        }

        .form-section-group {
            padding: 24px;
        }

        .form-actions {
            flex-direction: column;
            gap: 16px;
            padding: 24px 28px;
        }

        .form-actions-left,
        .form-actions-right {
            justify-content: center;
        }
    }

    /* Auto-save Status */
    .auto-save-status {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--tours-bg-primary, #ffffff);
        border: 1px solid var(--tours-border-light, #e9ecef);
        border-radius: var(--tours-radius-md, 8px);
        padding: 8px 16px;
        box-shadow: var(--tours-shadow, 0 4px 12px rgba(0, 0, 0, 0.15));
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        z-index: 1000;
        opacity: 0;
        transform: translateY(20px);
        transition: var(--tours-transition, all 0.3s ease);
    }

    .auto-save-status.show {
        opacity: 1;
        transform: translateY(0);
    }

    .auto-save-status.saving {
        color: var(--tours-warning, #ffc107);
    }

    .auto-save-status.error {
        color: var(--tours-danger, #dc3545);
    }

    .auto-save-status.success {
        color: var(--tours-success, #198754);
    }

    /* Loading States */
    .btn.loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 16px;
        height: 16px;
        margin: -8px 0 0 -8px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Keyboard Shortcuts */
    .keyboard-hint {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        opacity: 0;
        transform: translateY(20px);
        transition: var(--tours-transition, all 0.3s ease);
        z-index: 1000;
    }

    .keyboard-hint.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* Progress Indicator */
    .form-progress {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--tours-border-light, #e9ecef);
        border-radius: var(--tours-radius-lg, 12px) var(--tours-radius-lg, 12px) 0 0;
        overflow: hidden;
    }

    .form-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--tours-primary, #0d6efd), var(--tours-success, #198754));
        width: 0%;
        transition: width 0.5s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-modern {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .form-group-lg {
            grid-column: span 1;
        }

        .form-actions {
            flex-direction: column;
            gap: 16px;
            align-items: stretch;
        }

        .form-actions-left,
        .form-actions-right {
            justify-content: center;
        }

        .preview-header {
            flex-direction: column;
            gap: 12px;
        }

        .auto-save-status {
            bottom: 10px;
            right: 10px;
            font-size: 0.8rem;
        }

        .keyboard-hint {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form elements
        const form = document.getElementById('versionForm');
        const nameInput = document.getElementById('name');
        const statusSelect = document.getElementById('status');
        const descriptionTextarea = document.getElementById('description');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');

        // Preview elements
        const previewName = document.getElementById('preview-name');
        const previewStatus = document.getElementById('preview-status');
        const previewDescription = document.getElementById('preview-description');

        // Auto-save elements
        const autoSaveStatus = document.getElementById('autoSaveStatus');
        const autoSaveText = autoSaveStatus.querySelector('.auto-save-text');

        // Progress elements
        const formProgress = document.createElement('div');
        formProgress.className = 'form-progress';
        const progressBar = document.createElement('div');
        progressBar.className = 'form-progress-bar';
        formProgress.appendChild(progressBar);
        form.parentElement.insertBefore(formProgress, form);

        // Keyboard hint
        const keyboardHint = document.createElement('div');
        keyboardHint.className = 'keyboard-hint';
        keyboardHint.innerHTML = 'Press <kbd>Ctrl+S</kbd> to save • <kbd>Ctrl+Enter</kbd> to submit • <kbd>Esc</kbd> to cancel';
        document.body.appendChild(keyboardHint);

        // Auto-save functionality
        let autoSaveTimeout;
        let formData = {};
        const AUTO_SAVE_DELAY = 2000; // 2 seconds

        function saveFormData() {
            formData = {
                name: nameInput.value,
                status: statusSelect.value,
                description: descriptionTextarea.value
            };
            localStorage.setItem('versionFormDraft', JSON.stringify(formData));
        }

        function loadFormData() {
            const saved = localStorage.getItem('versionFormDraft');
            if (saved) {
                formData = JSON.parse(saved);
                if (!nameInput.value && formData.name) {
                    nameInput.value = formData.name;
                    updatePreview();
                }
                if (!statusSelect.value && formData.status) {
                    statusSelect.value = formData.status;
                    updatePreview();
                }
                if (!descriptionTextarea.value && formData.description) {
                    descriptionTextarea.value = formData.description;
                    updatePreview();
                    updateCounter();
                }
            }
        }

        function showAutoSaveStatus(type, message) {
            autoSaveStatus.className = `auto-save-status show ${type}`;
            autoSaveText.textContent = message;

            setTimeout(() => {
                autoSaveStatus.classList.remove('show');
            }, 3000);
        }

        function autoSave() {
            clearTimeout(autoSaveTimeout);
            autoSaveStatus.classList.add('saving');
            autoSaveText.textContent = 'Đang lưu...';

            autoSaveTimeout = setTimeout(() => {
                saveFormData();
                showAutoSaveStatus('success', 'Đã tự động lưu');
            }, AUTO_SAVE_DELAY);
        }

        // Progress calculation
        function updateProgress() {
            const fields = [nameInput, statusSelect, descriptionTextarea];
            let completed = 0;

            fields.forEach(field => {
                if (field.value.trim()) completed++;
            });

            const progress = (completed / fields.length) * 100;
            progressBar.style.width = progress + '%';
        }

        // Real-time preview
        function updatePreview() {
            // Update name
            previewName.textContent = nameInput.value || 'Tên phiên bản';

            // Update status
            const status = statusSelect.value;
            previewStatus.innerHTML = `
                <i class="fas fa-${status === 'active' ? 'check' : 'pause'} me-1"></i>
                ${status === 'active' ? 'Đang hoạt động' : 'Tạm ẩn'}
            `;
            previewStatus.className = 'status-badge status-' + status;

            // Update description
            const description = descriptionTextarea.value;
            previewDescription.innerHTML = description ?
                description.replace(/\n/g, '<br>') : 'Chưa có mô tả';

            updateProgress();
        }

        // Character counter
        const descCounter = document.getElementById('descCounter');

        function updateCounter() {
            const length = descriptionTextarea.value.length;
            descCounter.textContent = length;

            // Update counter color based on length
            descCounter.parentElement.classList.remove('warning', 'danger');
            if (length > 1000) {
                descCounter.parentElement.classList.add('danger');
            } else if (length > 800) {
                descCounter.parentElement.classList.add('warning');
            }
        }

        // Enhanced validation
        function validateField(field) {
            const wrapper = field.closest('.form-input-wrapper, .form-textarea-wrapper');
            const feedback = wrapper.parentElement.querySelector('.invalid-feedback');

            if (field.hasAttribute('required') && !field.value.trim()) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                return false;
            }

            if (field.id === 'name' && field.value.trim()) {
                if (field.value.trim().length < 2) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                    if (feedback) {
                        feedback.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Tên phiên bản phải có ít nhất 2 ký tự';
                    }
                    return false;
                }
            }

            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            return true;
        }

        function validateForm() {
            const fields = [nameInput, statusSelect, descriptionTextarea];
            let isValid = true;

            fields.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            return isValid;
        }

        // Form submission - NO VALIDATION BLOCKING
        form.addEventListener('submit', function(event) {
            // Show loading state only
            submitBtn.classList.add('loading');
            submitText.textContent = 'Đang xử lý...';

            // Clear draft on submission
            localStorage.removeItem('versionFormDraft');

            // Let form submit normally
        });

        // Event listeners
        nameInput.addEventListener('input', () => {
            updatePreview();
            validateField(nameInput);
            autoSave();
        });

        statusSelect.addEventListener('change', () => {
            updatePreview();
            validateField(statusSelect);
            autoSave();
        });

        descriptionTextarea.addEventListener('input', () => {
            updatePreview();
            updateCounter();
            autoSave();
        });

        // Field focus/blur events
        [nameInput, statusSelect, descriptionTextarea].forEach(field => {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('focus', () => {
                field.classList.remove('is-invalid');
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+S: Save draft
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                saveFormData();
                showAutoSaveStatus('success', 'Đã lưu nháp');
            }

            // Ctrl+Enter: Submit form
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }

            // Esc: Cancel
            if (e.key === 'Escape') {
                if (confirm('Bạn có chắc muốn hủy và quay lại?')) {
                    window.location.href = '<?= BASE_URL_ADMIN ?>&action=tours_versions';
                }
            }
        });

        // Show keyboard hint on focus
        let hintTimeout;
        [nameInput, descriptionTextarea].forEach(field => {
            field.addEventListener('focus', () => {
                keyboardHint.classList.add('show');
                clearTimeout(hintTimeout);
                hintTimeout = setTimeout(() => {
                    keyboardHint.classList.remove('show');
                }, 3000);
            });
        });

        // Character limit enforcement
        descriptionTextarea.addEventListener('input', function() {
            if (this.value.length > 1000) {
                this.value = this.value.substring(0, 1000);
                updateCounter();
            }
        });

        // Initialize
        loadFormData();
        updatePreview();
        updateCounter();
        updateProgress();

        // Show initial keyboard hint
        setTimeout(() => {
            keyboardHint.classList.add('show');
            setTimeout(() => {
                keyboardHint.classList.remove('show');
            }, 5000);
        }, 1000);

        // Warn before leaving if form has unsaved changes
        let hasChanges = false;
        form.addEventListener('change', () => {
            hasChanges = true;
        });

        window.addEventListener('beforeunload', function(e) {
            if (hasChanges && !submitBtn.classList.contains('loading')) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });

        // Clear changes flag after successful submission
        form.addEventListener('submit', () => {
            hasChanges = false;
        });
    });
</script>
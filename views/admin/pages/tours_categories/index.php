<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-modern mb-4" aria-label="breadcrumb">
            <a href="<?= BASE_URL_ADMIN ?>&action=dashboard">Dashboard</a>
            <span class="separator">/</span>
            <span class="active">Quản lý Danh mục Tour</span>
        </nav>

        <!-- Page Header -->
        <div class="page-header-modern mb-4">
            <div>
                <h1 class="h2 page-title">Quản lý Danh mục Tour</h1>
                <p class="text-muted lead">Phân loại tour theo loại hình và khu vực</p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories/create" class="btn-modern btn-primary">
                    <i class="fas fa-plus me-2"></i> Thêm danh mục
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Filter Tabs -->
        <div class="card-modern mb-4">
            <div class="card-body">
                <ul class="nav nav-pills" id="categoryTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= ($_GET['type'] ?? 'all') === 'all' ? 'active' : '' ?>"
                            id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                            <i class="fas fa-list me-2"></i>Tất cả danh mục
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= ($_GET['type'] ?? '') === 'domestic' ? 'active' : '' ?>"
                            id="domestic-tab" data-bs-toggle="tab" data-bs-target="#domestic" type="button" role="tab">
                            <i class="fas fa-flag me-2"></i>Tour trong nước
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= ($_GET['type'] ?? '') === 'international' ? 'active' : '' ?>"
                            id="international-tab" data-bs-toggle="tab" data-bs-target="#international" type="button" role="tab">
                            <i class="fas fa-globe me-2"></i>Tour quốc tế
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= ($_GET['type'] ?? '') === 'customized' ? 'active' : '' ?>"
                            id="customized-tab" data-bs-toggle="tab" data-bs-target="#customized" type="button" role="tab">
                            <i class="fas fa-cog me-2"></i>Tour theo yêu cầu
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-primary"><i class="fas fa-folder"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Tổng danh mục</div>
                        <div class="stat-value"><?= count($categories ?? []) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Đang hoạt động</div>
                        <div class="stat-value"><?= count(array_filter($categories ?? [], fn($c) => $c['is_active'] == 1)) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-info"><i class="fas fa-route"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Tổng tour</div>
                        <div class="stat-value"><?= array_sum(array_column($categories ?? [], 'tour_count')) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-warning"><i class="fas fa-layer-group"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Danh mục cha</div>
                        <div class="stat-value"><?= count(array_filter($categories ?? [], fn($c) => empty($c['parent_id']))) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="card-modern">
            <div class="card-body p-0">
                <?php if (!empty($categories)): ?>
                    <div class="table-responsive">
                        <table class="table table-modern table-hover">
                            <thead>
                                <tr>
                                    <th>Tên danh mục</th>
                                    <th>Loại hình</th>
                                    <th>Số tour</th>
                                    <th>Thứ tự</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($category['icon'])): ?>
                                                    <i class="<?= htmlspecialchars($category['icon']) ?> me-2 text-primary"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-folder me-2 text-muted"></i>
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?= htmlspecialchars($category['name']) ?></strong>
                                                    <?php if (!empty($category['description'])): ?>
                                                        <div class="text-muted small"><?= htmlspecialchars(substr($category['description'], 0, 50)) ?>...</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
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
                                            $type = $category['type'] ?? 'domestic';
                                            ?>
                                            <span class="badge bg-<?= $typeColors[$type] ?? 'secondary' ?>">
                                                <?= $typeLabels[$type] ?? 'Khác' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $category['tour_count'] > 0 ? 'success' : 'secondary' ?>">
                                                <?= $category['tour_count'] ?> tour
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?= $category['sort_order'] ?? 0 ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-status"
                                                    type="checkbox"
                                                    data-id="<?= $category['id'] ?>"
                                                    <?= $category['is_active'] ? 'checked' : '' ?>>
                                                <label class="form-check-label">
                                                    <?= $category['is_active'] ? 'Đang bật' : 'Đang tắt' ?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-nowrap"><?= date('d/m/Y', strtotime($category['created_at'])) ?></div>
                                            <div class="text-muted small"><?= date('H:i', strtotime($category['created_at'])) ?></div>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories/edit&id=<?= $category['id'] ?>"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip"
                                                    title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-category"
                                                    data-id="<?= $category['id'] ?>"
                                                    data-name="<?= htmlspecialchars($category['name']) ?>"
                                                    data-tour-count="<?= $category['tour_count'] ?>"
                                                    data-bs-toggle="tooltip"
                                                    title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Show child categories -->
                                    <?php if (isset($category['children']) && !empty($category['children'])): ?>
                                        <?php foreach ($category['children'] as $child): ?>
                                            <tr class="child-row">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-angle-right me-2 text-muted"></i>
                                                        <i class="fas fa-folder-open me-2 text-muted"></i>
                                                        <div>
                                                            <span class="text-muted">— </span>
                                                            <strong><?= htmlspecialchars($child['name']) ?></strong>
                                                            <?php if (!empty($child['description'])): ?>
                                                                <div class="text-muted small"><?= htmlspecialchars(substr($child['description'], 0, 50)) ?>...</div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $childType = $child['type'] ?? 'domestic';
                                                    ?>
                                                    <span class="badge bg-<?= $typeColors[$childType] ?? 'secondary' ?>">
                                                        <?= $typeLabels[$childType] ?? 'Khác' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $child['tour_count'] > 0 ? 'success' : 'secondary' ?>">
                                                        <?= $child['tour_count'] ?> tour
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?= $child['sort_order'] ?? 0 ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status"
                                                            type="checkbox"
                                                            data-id="<?= $child['id'] ?>"
                                                            <?= $child['is_active'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label">
                                                            <?= $child['is_active'] ? 'Đang bật' : 'Đang tắt' ?>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-nowrap"><?= date('d/m/Y', strtotime($child['created_at'])) ?></div>
                                                    <div class="text-muted small"><?= date('H:i', strtotime($child['created_at'])) ?></div>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories/edit&id=<?= $child['id'] ?>"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="tooltip"
                                                            title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger delete-category"
                                                            data-id="<?= $child['id'] ?>"
                                                            data-name="<?= htmlspecialchars($child['name']) ?>"
                                                            data-tour-count="<?= $child['tour_count'] ?>"
                                                            data-bs-toggle="tooltip"
                                                            title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state p-5 text-center">
                        <div class="empty-state-icon">
                            <i class="fas fa-folder-open fa-3x text-muted"></i>
                        </div>
                        <h4 class="empty-state-title mt-3">Chưa có danh mục nào</h4>
                        <p class="empty-state-description">
                            Bạn chưa tạo danh mục tour nào. Hãy bắt đầu bằng cách tạo danh mục đầu tiên.
                        </p>
                        <a href="<?= BASE_URL_ADMIN ?>&action=tours_categories/create" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i> Thêm danh mục mới
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa danh mục <strong id="categoryName"></strong>?</p>
                <p id="tourCountWarning" class="text-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Danh mục này đang có <span id="tourCount"></span> tour. Bạn cần chuyển các tour này sang danh mục khác trước khi xóa.
                </p>
                <p class="text-danger">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<style>
    .child-row {
        background-color: #f8f9fa;
    }

    .child-row td {
        padding-left: 2rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Delete category
        var deleteButtons = document.querySelectorAll('.delete-category');
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        var categoryName = document.getElementById('categoryName');
        var tourCount = document.getElementById('tourCount');
        var tourCountWarning = document.getElementById('tourCountWarning');
        var deleteForm = document.getElementById('deleteForm');
        var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var categoryId = this.getAttribute('data-id');
                var name = this.getAttribute('data-name');
                var tourCountValue = this.getAttribute('data-tour-count');

                categoryName.textContent = name;
                tourCount.textContent = tourCountValue;

                if (tourCountValue > 0) {
                    tourCountWarning.style.display = 'block';
                    confirmDeleteBtn.disabled = true;
                    confirmDeleteBtn.classList.add('disabled');
                } else {
                    tourCountWarning.style.display = 'none';
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.classList.remove('disabled');
                }

                deleteForm.action = '<?= BASE_URL_ADMIN ?>&action=tours_categories/delete&id=' + categoryId;
                deleteModal.show();
            });
        });

        // Toggle category status
        var toggleSwitches = document.querySelectorAll('.toggle-status');
        toggleSwitches.forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                var categoryId = this.getAttribute('data-id');
                var isActive = this.checked;
                var status = isActive ? 1 : 0;
                var label = this.nextElementSibling;

                // Show loading state
                var originalText = label.textContent;
                label.textContent = 'Đang xử lý...';

                // Disable the toggle while processing
                this.disabled = true;

                // Send AJAX request
                fetch('<?= BASE_URL_ADMIN ?>&action=tours_categories/toggle-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id=' + categoryId + '&status=' + status + '&_method=PATCH'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI on success
                            label.textContent = isActive ? 'Đang bật' : 'Đang tắt';
                            showToast('Cập nhật trạng thái thành công', 'success');
                        } else {
                            // Revert on error
                            this.checked = !isActive;
                            label.textContent = isActive ? 'Đang tắt' : 'Đang bật';
                            showToast('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.checked = !isActive;
                        label.textContent = isActive ? 'Đang tắt' : 'Đang bật';
                        showToast('Có lỗi xảy ra. Vui lòng thử lại', 'error');
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
            });
        });

        // Helper function to show toast notifications
        function showToast(message, type = 'success') {
            // Simple alert for now - you can replace with a proper toast library
            console.log(type.toUpperCase() + ': ' + message);
        }
    });
</script>
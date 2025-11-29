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
            <a href="<?= BASE_URL_ADMIN ?>&action=tours">Quản lý Tour</a>
            <span class="separator">/</span>
            <a href="<?= BASE_URL_ADMIN ?>&action=tours/detail&id=<?= $tour['id'] ?>"><?= htmlspecialchars($tour['name']) ?></a>
            <span class="separator">/</span>
            <span class="active">Phiên bản</span>
        </nav>

        <!-- Page Header -->
        <div class="page-header-modern mb-4">
            <div>
                <h1 class="h2 page-title">Quản lý phiên bản</h1>
                <p class="text-muted lead"><?= htmlspecialchars($tour['name']) ?></p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions/create&tour_id=<?= $tour['id'] ?>" class="btn-modern btn-primary">
                    <i class="fas fa-plus me-2"></i> Thêm phiên bản
                </a>
                <a href="<?= BASE_URL_ADMIN ?>&action=tours/detail&id=<?= $tour['id'] ?>" class="btn-modern btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại
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

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-primary"><i class="fas fa-layer-group"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Tổng phiên bản</div>
                        <div class="stat-value"><?= count($versions ?? []) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Đang hoạt động</div>
                        <div class="stat-value"><?= count(array_filter($versions ?? [], fn($v) => $v['status'] === 'active')) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-warning"><i class="fas fa-pause-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Tạm ẩn</div>
                        <div class="stat-value"><?= count(array_filter($versions ?? [], fn($v) => $v['status'] === 'inactive')) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <div class="stat-icon text-info"><i class="fas fa-calendar"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Ngày tạo</div>
                        <div class="stat-value"><?= date('d/m/Y') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Versions Table -->
        <div class="card-modern">
            <div class="card-body p-0">
                <?php if (!empty($versions)): ?>
                    <div class="table-responsive">
                        <table class="table table-modern table-hover">
                            <thead>
                                <tr>
                                    <th>Tên phiên bản</th>
                                    <th>Mô tả</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th>
                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($versions as $version): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <?php if ($version['status'] === 'active'): ?>
                                                        <span class="badge bg-success me-2">Đang hoạt động</span>
                                                    <?php endif; ?>
                                                    <strong><?= htmlspecialchars($version['name']) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $description = $version['description'] ?? '';
                                            if (strlen($description) > 100) {
                                                echo htmlspecialchars(substr($description, 0, 100)) . '...';
                                            } else {
                                                echo htmlspecialchars($description) ?: '<span class="text-muted">Không có mô tả</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-status"
                                                    type="checkbox"
                                                    data-id="<?= $version['id'] ?>"
                                                    <?= $version['status'] === 'active' ? 'checked' : '' ?>>
                                                <label class="form-check-label">
                                                    <?= $version['status'] === 'active' ? 'Đang bật' : 'Đang tắt' ?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-nowrap"><?= date('d/m/Y', strtotime($version['created_at'])) ?></div>
                                            <div class="text-muted small"><?= date('H:i', strtotime($version['created_at'])) ?></div>
                                        </td>
                                        <td>
                                            <?php if ($version['updated_at'] && $version['updated_at'] !== $version['created_at']): ?>
                                                <div class="text-nowrap"><?= date('d/m/Y', strtotime($version['updated_at'])) ?></div>
                                                <div class="text-muted small"><?= date('H:i', strtotime($version['updated_at'])) ?></div>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa cập nhật</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions/edit&id=<?= $version['id'] ?>&tour_id=<?= $tour['id'] ?>"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="tooltip"
                                                    title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-version"
                                                    data-id="<?= $version['id'] ?>"
                                                    data-name="<?= htmlspecialchars($version['name']) ?>"
                                                    data-bs-toggle="tooltip"
                                                    title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state p-5 text-center">
                        <div class="empty-state-icon">
                            <i class="fas fa-layer-group fa-3x text-muted"></i>
                        </div>
                        <h4 class="empty-state-title mt-3">Chưa có phiên bản nào</h4>
                        <p class="empty-state-description">
                            Bạn chưa tạo phiên bản nào cho tour này. Hãy bắt đầu bằng cách tạo phiên bản đầu tiên.
                        </p>
                        <a href="<?= BASE_URL_ADMIN ?>&action=tours_versions/create&tour_id=<?= $tour['id'] ?>" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i> Thêm phiên bản mới
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
                <p>Bạn có chắc chắn muốn xóa phiên bản <strong id="versionName"></strong>?</p>
                <p class="text-danger">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Delete version
        var deleteButtons = document.querySelectorAll('.delete-version');
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        var versionName = document.getElementById('versionName');
        var deleteForm = document.getElementById('deleteForm');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var versionId = this.getAttribute('data-id');
                var name = this.getAttribute('data-name');

                versionName.textContent = name;
                deleteForm.action = '<?= BASE_URL_ADMIN ?>&action=tours_versions/delete&id=' + versionId + '&tour_id=<?= $tour['id'] ?>';
                deleteModal.show();
            });
        });

        // Toggle version status
        var toggleSwitches = document.querySelectorAll('.toggle-status');
        toggleSwitches.forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                var versionId = this.getAttribute('data-id');
                var isActive = this.checked;
                var status = isActive ? 'active' : 'inactive';
                var label = this.nextElementSibling;

                // Show loading state
                var originalText = label.textContent;
                label.textContent = 'Đang xử lý...';

                // Disable the toggle while processing
                this.disabled = true;

                // Send AJAX request
                fetch('<?= BASE_URL_ADMIN ?>&action=tours_versions/toggle-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id=' + versionId + '&status=' + status + '&_method=PATCH'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI on success
                            if (isActive) {
                                label.textContent = 'Đang bật';
                            } else {
                                label.textContent = 'Đang tắt';
                            }

                            // Show success message
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
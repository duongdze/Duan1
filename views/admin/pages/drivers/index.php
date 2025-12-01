<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="dashboard">
    <div class="dashboard-container">
        <!-- Modern Header -->
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
                        <span class="breadcrumb-current">Quản lý Tài Xế</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-car title-icon"></i>
                            Quản lý Tài Xế
                        </h1>
                        <p class="page-subtitle">Quản lý toàn bộ tài xế và phương tiện trong hệ thống</p>
                    </div>
                </div>
                <div class="header-right">
                    <button class="btn btn-modern btn-primary btn-lg" onclick="window.location.href='<?= BASE_URL_ADMIN . '&action=drivers/create' ?>'">
                        <i class="fas fa-plus-circle me-2"></i>
                        Thêm Tài Xế Mới
                    </button>
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

        <!-- Statistics Cards -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= number_format($stats['total'] ?? 0) ?></div>
                        <div class="stat-label">Tổng Tài Xế</div>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+5%</span>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= number_format($stats['active'] ?? 0) ?></div>
                        <div class="stat-label">Sẵn Sàng</div>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+10%</span>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= number_format($stats['busy'] ?? 0) ?></div>
                        <div class="stat-label">Đang Bận</div>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-down"></i>
                        <span>-2%</span>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?= number_format($stats['avg_rating'] ?? 5, 1) ?></div>
                        <div class="stat-label">Đánh Giá TB</div>
                    </div>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+0.2</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Advanced Filters -->
        <section class="filters-section">
            <div class="filter-card">
                <div class="filter-header">
                    <h3 class="filter-title">
                        <i class="fas fa-filter"></i>
                        Bộ Lọc
                    </h3>
                </div>

                <form method="GET" action="<?= BASE_URL_ADMIN . '&action=drivers' ?>" class="filter-form">
                    <input type="hidden" name="action" value="drivers">

                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">Tìm kiếm</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" name="keyword"
                                    value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
                                    placeholder="Tên, SĐT, biển số...">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Trạng thái</label>
                            <select class="form-select" name="status">
                                <option value="">Tất cả</option>
                                <option value="active" <?= (($_GET['status'] ?? '') == 'active') ? 'selected' : '' ?>>Sẵn sàng</option>
                                <option value="busy" <?= (($_GET['status'] ?? '') == 'busy') ? 'selected' : '' ?>>Đang bận</option>
                                <option value="inactive" <?= (($_GET['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Nghỉ</option>
                            </select>
                        </div>

                        <div class="filter-group filter-actions-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Tìm kiếm
                            </button>
                            <a href="<?= BASE_URL_ADMIN . '&action=drivers' ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Xóa lọc
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Drivers Table Section -->
        <section class="tours-section">
            <div class="tours-header">
                <div class="tours-info">
                    <div class="select-all-wrapper">
                        <i class="fas fa-list"></i>
                        <label class="select-all-label">
                            Danh sách Tài Xế
                        </label>
                    </div>
                    <div class="tours-count">
                        <span class="count-info">
                            <?= count($drivers) ?> tài xế
                        </span>
                    </div>
                </div>
            </div>

            <div class="tours-container">
                <?php if (!empty($drivers)) : ?>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ Tên</th>
                                    <th>Liên Hệ</th>
                                    <th>Bằng Lái</th>
                                    <th>Phương Tiện</th>
                                    <th>Trạng Thái</th>
                                    <th>Đánh Giá</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($drivers as $index => $driver) : ?>
                                    <tr>
                                        <td><strong><?= $index + 1 ?></strong></td>
                                        <td>
                                            <div class="customer-info">
                                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                                <strong><?= htmlspecialchars($driver['full_name']) ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <div><i class="fas fa-phone me-2 text-success"></i><?= htmlspecialchars($driver['phone']) ?></div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($driver['license_number']) ?></td>
                                        <td>
                                            <div class="vehicle-info">
                                                <div><i class="fas fa-car me-2 text-info"></i><?= htmlspecialchars($driver['vehicle_type'] ?? '-') ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($driver['vehicle_plate'] ?? '-') ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'active' => 'success',
                                                'busy' => 'warning',
                                                'inactive' => 'secondary'
                                            ];
                                            $statusText = [
                                                'active' => 'Sẵn sàng',
                                                'busy' => 'Đang bận',
                                                'inactive' => 'Nghỉ'
                                            ];
                                            $statusIcon = [
                                                'active' => 'check-circle',
                                                'busy' => 'hourglass-half',
                                                'inactive' => 'pause-circle'
                                            ];
                                            ?>
                                            <span class="badge badge-modern badge-<?= $statusClass[$driver['status']] ?? 'secondary' ?>">
                                                <i class="fas fa-<?= $statusIcon[$driver['status']] ?? 'circle' ?> me-1"></i>
                                                <?= $statusText[$driver['status']] ?? $driver['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="rating-display">
                                                <div class="stars">
                                                    <?php
                                                    $rating = $driver['rating'] ?? 5;
                                                    $fullStars = floor($rating);
                                                    for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $fullStars ? 'filled' : 'empty' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="rating-value"><?= number_format($rating, 1) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="<?= BASE_URL_ADMIN . '&action=drivers/detail&id=' . $driver['id'] ?>"
                                                    class="btn-action btn-view"
                                                    data-bs-toggle="tooltip"
                                                    title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL_ADMIN . '&action=drivers/edit&id=' . $driver['id'] ?>"
                                                    class="btn-action btn-edit"
                                                    data-bs-toggle="tooltip"
                                                    title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn-action btn-delete"
                                                    onclick="deleteDriver(<?= $driver['id'] ?>, '<?= htmlspecialchars($driver['full_name']) ?>')"
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
                <?php else : ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <h3 class="empty-title">Chưa có tài xế nào</h3>
                        <p class="empty-description">
                            Bắt đầu thêm tài xế đầu tiên vào hệ thống.
                        </p>
                        <button class="btn btn-primary" onclick="window.location.href='<?= BASE_URL_ADMIN . '&action=drivers/create' ?>'">
                            <i class="fas fa-plus me-2"></i>
                            Thêm Tài Xế Mới
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<!-- Form xóa tài xế -->
<form id="deleteForm" method="POST" action="<?= BASE_URL_ADMIN ?>&action=drivers/delete" style="display: none;">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function deleteDriver(id, name) {
        if (confirm('Bạn có chắc muốn xóa tài xế "' + name + '"?\nLưu ý: Các booking đã phân công tài xế này sẽ bị ảnh hưởng.')) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
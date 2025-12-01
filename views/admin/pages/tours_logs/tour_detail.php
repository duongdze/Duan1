<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="dashboard tour-logs-detail-page">
    <div class="dashboard-container">
        <!-- Page Header -->
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
                        <a href="<?= BASE_URL_ADMIN ?>&action=tours_logs" class="breadcrumb-link">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Nhật ký Tour</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current"><?= htmlspecialchars($tour['name'] ?? '') ?></span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-history title-icon"></i>
                            Lịch sử Nhật ký
                        </h1>
                        <p class="page-subtitle">Danh sách nhật ký hoạt động của tour: <strong><?= htmlspecialchars($tour['name'] ?? '') ?></strong></p>
                    </div>
                </div>
                <div class="header-right">
                    <a href="<?= BASE_URL_ADMIN . '&action=tours_logs/create&tour_id=' . ($tour['id'] ?? '') ?>" class="btn btn-modern btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>
                        Thêm nhật ký ngày
                    </a>
                </div>
            </div>
        </header>

        <!-- Logs List Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Ngày</th>
                                <th>Hướng dẫn viên</th>
                                <th>Tóm tắt hoạt động</th>
                                <th>Thời tiết</th>
                                <th>Đánh giá</th>
                                <th class="text-center pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td class="ps-4 fw-medium text-nowrap">
                                            <?= date('d/m/Y', strtotime($log['date'])) ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <span><?= htmlspecialchars($log['guide_name'] ?? 'N/A') ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;">
                                                <?= htmlspecialchars($log['description'] ?? '') ?>
                                            </div>
                                            <?php if (!empty($log['issue'])): ?>
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Có vấn đề phát sinh
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($log['weather'])): ?>
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                    <i class="fas fa-cloud-sun me-1"></i>
                                                    <?= htmlspecialchars($log['weather']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (isset($log['guide_rating']) && $log['guide_rating'] > 0): ?>
                                                <div class="d-flex align-items-center text-warning">
                                                    <span class="fw-bold me-1"><?= $log['guide_rating'] ?></span>
                                                    <i class="fas fa-star small"></i>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">Chưa đánh giá</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL_ADMIN . '&action=tours_logs/detail&id=' . urlencode($log['id']) ?>" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL_ADMIN . '&action=tours_logs/edit&id=' . urlencode($log['id']) ?>" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="post" action="<?= BASE_URL_ADMIN . '&action=tours_logs/delete' ?>" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa nhật ký này?')" 
                                                      class="d-inline">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($log['id']) ?>">
                                                    <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tour['id'] ?? '') ?>">
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Chưa có nhật ký nào cho tour này</h5>
                                            <p class="text-muted mb-3">Bắt đầu ghi nhận hoạt động của tour ngay hôm nay.</p>
                                            <a href="<?= BASE_URL_ADMIN . '&action=tours_logs/create&tour_id=' . ($tour['id'] ?? '') ?>" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Thêm nhật ký đầu tiên
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>

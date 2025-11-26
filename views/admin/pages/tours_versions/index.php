<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2">Phiên bản Tour</h1>
                <p class="text-muted">
                    <a href="?action=tours" class="text-decoration-none">Tours</a> 
                    <i class="fas fa-chevron-right mx-2" style="font-size: 0.75rem;"></i>
                    <?= htmlspecialchars($tour['name'] ?? '') ?>
                </p>
            </div>
            <a href="?action=tours_versions/create&tour_id=<?= htmlspecialchars($_GET['tour_id'] ?? '') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Phiên Bản
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Statistics Card -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card kpi-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="kpi-icon" style="background-color: #e0f2fe;">
                            <i class="fas fa-layer-group" style="color: #0ea5e9;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng Phiên Bản</div>
                            <div class="kpi-value"><?= count($versions ?? []) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Versions Table -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách Phiên Bản</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($versions)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Tên Phiên Bản</th>
                                    <th style="width: 15%;">Ngày Bắt Đầu</th>
                                    <th style="width: 15%;">Ngày Kết Thúc</th>
                                    <th style="width: 15%;">Giá (VNĐ)</th>
                                    <th style="width: 15%;">Ghi Chú</th>
                                    <th style="width: 10%;" class="text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($versions as $index => $version): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($version['name']) ?></strong>
                                        </td>
                                        <td>
                                            <?php if (!empty($version['start_date'])): ?>
                                                <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                <?= date('d/m/Y', strtotime($version['start_date'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($version['end_date'])): ?>
                                                <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                <?= date('d/m/Y', strtotime($version['end_date'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($version['price'] > 0): ?>
                                                <span class="badge bg-success">
                                                    <?= number_format($version['price'], 0, ',', '.') ?> đ
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $notes = $version['notes'] ?? '';
                                            if (strlen($notes) > 50) {
                                                echo htmlspecialchars(substr($notes, 0, 50)) . '...';
                                            } else {
                                                echo htmlspecialchars($notes) ?: '-';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="?action=tours_versions/edit&id=<?= $version['id'] ?>&tour_id=<?= htmlspecialchars($_GET['tour_id'] ?? '') ?>" 
                                                   class="btn btn-outline-warning" 
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=tours_versions/delete&id=<?= $version['id'] ?>&tour_id=<?= htmlspecialchars($_GET['tour_id'] ?? '') ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('Bạn có chắc muốn xóa phiên bản này?')"
                                                   title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có phiên bản nào</h5>
                        <p class="text-muted">Bắt đầu bằng cách thêm phiên bản đầu tiên cho tour này.</p>
                        <a href="?action=tours_versions/create&tour_id=<?= htmlspecialchars($_GET['tour_id'] ?? '') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm Phiên Bản
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

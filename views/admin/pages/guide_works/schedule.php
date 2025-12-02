<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Lịch làm việc của tôi</h1>
                <p class="text-muted small">Danh sách tour được phân công cho bạn</p>
            </div>
            <div>
                <a href="<?= BASE_URL_ADMIN ?>&action=guide/schedule_all" class="btn btn-outline-secondary">Xem tất cả HDV</a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-2 align-items-end" action="<?= BASE_URL_ADMIN ?>">
                    <input type="hidden" name="action" value="guide/schedule">
                    <div class="col-md-4">
                        <label class="form-label">Tour</label>
                        <select name="tour_id" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($tours as $t): ?>
                                <option value="<?= htmlspecialchars($t['id']) ?>" <?= (!empty($filters['tour_id']) && $filters['tour_id'] == $t['id']) ? 'selected' : '' ?>><?= htmlspecialchars($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="assigned" <?= (!empty($filters['status']) && $filters['status'] == 'assigned') ? 'selected' : '' ?>>Assigned</option>
                            <option value="in_progress" <?= (!empty($filters['status']) && $filters['status'] == 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= (!empty($filters['status']) && $filters['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" class="form-control" placeholder="tên tour, tài xế...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Lọc</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Danh sách phân công</div>
            <div class="card-body">
                <?php if (empty($assignments)): ?>
                    <p class="text-muted">Bạn chưa có tour nào được phân công.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tour</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Tài xế / Liên hệ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignments as $a): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($a['tour_name'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($a['start_date'] ?? '') ?> - <?= htmlspecialchars($a['end_date'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($a['status'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($a['driver_name'] ?? '') ?></td>
                                        <td>
                                            <a href="<?= BASE_URL_ADMIN ?>&action=guide/tourDetail&id=<?= $a['tour_id'] ?>&guide_id=<?= $_SESSION['guide_id'] ?? $a['guide_id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Chi tiết</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

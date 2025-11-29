<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>
<main class="wrapper">
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Sửa nhật ký Tour</h1>
                    <p class="text-muted small">Cập nhật diễn biến, xử lý sự cố và phản hồi khách</p>
                </div>
                <a href="<?= BASE_URL_ADMIN . '&action=tours_logs'  ?>" class="btn btn-secondary">Quay lại</a>
            </div>

            <div class="card mx-auto" style="max-width:1000px;">
                <div class="card-body">
                    <form method="post" action="<?= BASE_URL_ADMIN . '&action=tours_logs/update' ?>">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($log['id']) ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Chọn tour</label>
                                <select name="tour_id" class="form-select" required>
                                    <option value="">-- Chọn tour --</option>
                                    <?php foreach ($tours as $t): ?>
                                        <option value="<?= htmlspecialchars($t['id']) ?>" <?= ($t['id'] == $log['tour_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($t['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Chọn HDV</label>
                                <select name="guide_id" class="form-select" required>
                                    <option value="">-- Chọn HDV --</option>
                                    <?php foreach ($guides as $g): ?>
                                        <option value="<?= htmlspecialchars($g['id']) ?>" <?= ($g['id'] == $log['guide_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($g['full_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Ngày</label>
                                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars(substr($log['date'] ?? '', 0, 10)) ?>" required>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Thời tiết</label>
                                <input type="text" name="weather" class="form-control" value="<?= htmlspecialchars($log['weather'] ?? '') ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Mô tả diễn biến</label>
                                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($log['description'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sự cố (issue)</label>
                                <textarea name="issue" class="form-control" rows="2"><?= htmlspecialchars($log['issue'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Cách xử lý (solution)</label>
                                <textarea name="solution" class="form-control" rows="2"><?= htmlspecialchars($log['solution'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Chi tiết sự cố (incident)</label>
                                <textarea name="incident" class="form-control" rows="2"><?= htmlspecialchars($log['incident'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ghi chú xử lý (handling_notes)</label>
                                <textarea name="handling_notes" class="form-control" rows="2"><?= htmlspecialchars($log['handling_notes'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tình trạng sức khỏe khách</label>
                                <input type="text" name="health_status" class="form-control" value="<?= htmlspecialchars($log['health_status'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hoạt động đặc biệt</label>
                                <input type="text" name="special_activity" class="form-control" value="<?= htmlspecialchars($log['special_activity'] ?? '') ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Phản hồi khách</label>
                                <textarea name="customer_feedback" class="form-control" rows="2"><?= htmlspecialchars($log['customer_feedback'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Đánh giá HDV (1-5)</label>
                                <input type="number" name="guide_rating" min="1" max="5" class="form-control" value="<?= htmlspecialchars($log['guide_rating'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="<?= BASE_URL_ADMIN . '&action=tours_logs' ?>" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>
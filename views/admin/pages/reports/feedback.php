<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Báo cáo Phản hồi</h1>
            <p class="text-muted">Phân tích đánh giá của khách hàng và nhà cung cấp</p>
        </div>

        <!-- Bộ lọc -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="action" value="reports/feedback">

                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="date_from" name="date_from"
                            value="<?= $_GET['date_from'] ?? date('Y-m-01') ?>">
                    </div>

                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="date_to" name="date_to"
                            value="<?= $_GET['date_to'] ?? date('Y-m-d') ?>">
                    </div>

                    <div class="col-md-2">
                        <label for="tour_id" class="form-label">Tour</label>
                        <select class="form-select" id="tour_id" name="tour_id">
                            <option value="">Tất cả tours</option>
                            <?php if (isset($tours)): ?>
                                <?php foreach ($tours as $tour): ?>
                                    <option value="<?= $tour['id'] ?>"
                                        <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tour['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="feedback_type" class="form-label">Loại phản hồi</label>
                        <select class="form-select" id="feedback_type" name="feedback_type">
                            <option value="">Tất cả</option>
                            <option value="tour" <?= (($_GET['feedback_type'] ?? '') == 'tour') ? 'selected' : '' ?>>Tour</option>
                            <option value="supplier" <?= (($_GET['feedback_type'] ?? '') == 'supplier') ? 'selected' : '' ?>>Nhà cung cấp</option>
                            <option value="guide" <?= (($_GET['feedback_type'] ?? '') == 'guide') ? 'selected' : '' ?>>Hướng dẫn viên</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="rating" class="form-label">Đánh giá</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="">Tất cả</option>
                            <option value="5" <?= (($_GET['rating'] ?? '') == '5') ? 'selected' : '' ?>>5 sao</option>
                            <option value="4" <?= (($_GET['rating'] ?? '') == '4') ? 'selected' : '' ?>>4 sao</option>
                            <option value="3" <?= (($_GET['rating'] ?? '') == '3') ? 'selected' : '' ?>>3 sao</option>
                            <option value="2" <?= (($_GET['rating'] ?? '') == '2') ? 'selected' : '' ?>>2 sao</option>
                            <option value="1" <?= (($_GET['rating'] ?? '') == '1') ? 'selected' : '' ?>>1 sao</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="sentiment" class="form-label">Tình cảm</label>
                        <select class="form-select" id="sentiment" name="sentiment">
                            <option value="">Tất cả</option>
                            <option value="positive" <?= (($_GET['sentiment'] ?? '') == 'positive') ? 'selected' : '' ?>>Tích cực</option>
                            <option value="neutral" <?= (($_GET['sentiment'] ?? '') == 'neutral') ? 'selected' : '' ?>>Trung lập</option>
                            <option value="negative" <?= (($_GET['sentiment'] ?? '') == 'negative') ? 'selected' : '' ?>>Tiêu cực</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Lọc báo cáo
                        </button>
                        <a href="<?= BASE_URL_ADMIN ?>&action=reports/feedback" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                        <button type="button" class="btn btn-success float-end" onclick="exportFeedbackReport()">
                            <i class="fas fa-download me-2"></i>Xuất Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Thẻ KPI Phản hồi -->
        <div class="row mb-4">
            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #fef3c7;">
                            <i class="fas fa-star" style="color: #f59e0b;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Đánh giá trung bình</div>
                            <div class="kpi-value"><?= number_format($feedbackStats['avg_rating'] ?? 0, 1) ?>/5.0</div>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i>
                                +<?= number_format($feedbackStats['rating_growth'] ?? 0, 1) ?>% so với kỳ trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #dcfce7;">
                            <i class="fas fa-comment-dots" style="color: #22c55e;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Tổng phản hồi</div>
                            <div class="kpi-value"><?= number_format($feedbackStats['total_feedbacks'] ?? 0) ?></div>
                            <small class="text-muted">
                                <?= number_format($feedbackStats['feedback_rate'] ?? 0, 1) ?>% tỷ lệ phản hồi
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #dbeafe;">
                            <i class="fas fa-smile" style="color: #3b82f6;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Phản hồi tích cực</div>
                            <div class="kpi-value"><?= number_format($feedbackStats['positive_feedbacks'] ?? 0) ?></div>
                            <small class="text-success">
                                <?= number_format($feedbackStats['positive_rate'] ?? 0, 1) ?>% tỷ lệ tích cực
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="kpi-icon" style="background-color: #fee2e2;">
                            <i class="fas fa-frown" style="color: #ef4444;"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Phản hồi tiêu cực</div>
                            <div class="kpi-value"><?= number_format($feedbackStats['negative_feedbacks'] ?? 0) ?></div>
                            <small class="text-danger">
                                <?= number_format($feedbackStats['negative_rate'] ?? 0, 1) ?>% cần cải thiện
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ và Phân tích -->
        <div class="row">
            <!-- Biểu đồ Rating Distribution -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Phân bố Đánh giá</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ratingChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ tròn Phân loại Phản hồi -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Phân loại Phản hồi</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="feedbackTypeChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng chi tiết Phản hồi -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Chi tiết Phản hồi</h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary active" onclick="showFeedbackTable('all')">Tất cả</button>
                    <button class="btn btn-outline-success" onclick="showFeedbackTable('positive')">Tích cực</button>
                    <button class="btn btn-outline-warning" onclick="showFeedbackTable('neutral')">Trung lập</button>
                    <button class="btn btn-outline-danger" onclick="showFeedbackTable('negative')">Tiêu cực</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="feedbackTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Loại</th>
                                <th>Tour/Đối tượng</th>
                                <th>Khách hàng</th>
                                <th>Đánh giá</th>
                                <th>Nội dung</th>
                                <th>Ngày</th>
                                <th>Tình cảm</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($feedbacks)): ?>
                                <?php foreach ($feedbacks as $feedback): ?>
                                    <tr data-sentiment="<?= $feedback['sentiment'] ?? 'neutral' ?>">
                                        <td>
                                            <span class="badge bg-primary">#<?= str_pad($feedback['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $typeLabels = [
                                                'tour' => 'Tour',
                                                'supplier' => 'Nhà cung cấp',
                                                'guide' => 'HDV'
                                            ];
                                            $typeColors = [
                                                'tour' => 'info',
                                                'supplier' => 'warning',
                                                'guide' => 'success'
                                            ];
                                            $type = $feedback['feedback_type'] ?? 'tour';
                                            $label = $typeLabels[$type] ?? $type;
                                            $color = $typeColors[$type] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-medium"><?= htmlspecialchars($feedback['target_name'] ?? 'N/A') ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($feedback['category_name'] ?? '') ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium"><?= htmlspecialchars($feedback['customer_name'] ?? 'N/A') ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($feedback['customer_email'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="rating-stars">
                                                <?php
                                                $rating = $feedback['rating'] ?? 0;
                                                for ($i = 1; $i <= 5; $i++):
                                                    $starClass = $i <= $rating ? 'fas fa-star text-warning' : 'far fa-star text-muted';
                                                ?>
                                                    <i class="<?= $starClass ?>"></i>
                                                <?php endfor; ?>
                                                <span class="ms-1 fw-medium"><?= $rating ?>/5</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="feedback-content" style="max-width: 300px;">
                                                <p class="mb-0 text-truncate" title="<?= htmlspecialchars($feedback['comment'] ?? '') ?>">
                                                    <?= htmlspecialchars($feedback['comment'] ?? 'Không có nội dung') ?>
                                                </p>
                                                <?php if (strlen($feedback['comment'] ?? '') > 50): ?>
                                                    <button class="btn btn-link btn-sm p-0" onclick="toggleContent(this)">
                                                        Xem thêm
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($feedback['created_at'])) ?>
                                        </td>
                                        <td>
                                            <?php
                                            $sentimentIcons = [
                                                'positive' => 'fa-smile text-success',
                                                'neutral' => 'fa-meh text-warning',
                                                'negative' => 'fa-frown text-danger'
                                            ];
                                            $sentimentLabels = [
                                                'positive' => 'Tích cực',
                                                'neutral' => 'Trung lập',
                                                'negative' => 'Tiêu cực'
                                            ];
                                            $sentiment = $feedback['sentiment'] ?? 'neutral';
                                            $icon = $sentimentIcons[$sentiment] ?? 'fa-meh text-secondary';
                                            $label = $sentimentLabels[$sentiment] ?? $sentiment;
                                            ?>
                                            <i class="fas <?= $icon ?>"></i>
                                            <small class="ms-1"><?= $label ?></small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewFeedbackDetail(<?= $feedback['id'] ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="replyFeedback(<?= $feedback['id'] ?>)">
                                                <i class="fas fa-reply"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Phân tích Chi tiết -->
        <div class="row mt-4">
            <!-- Top Tours được đánh giá cao nhất -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Tours được Đánh giá cao nhất</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php if (isset($topRatedTours)): ?>
                                <?php foreach ($topRatedTours as $index => $tour): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rating-stars small">
                                                    <?php
                                                    $rating = $tour['avg_rating'] ?? 0;
                                                    for ($i = 1; $i <= 5; $i++):
                                                        $starClass = $i <= $rating ? 'fas fa-star text-warning' : 'far fa-star text-muted';
                                                    ?>
                                                        <i class="<?= $starClass ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium"><?= $tour['tour_name'] ?></div>
                                                <small class="text-muted"><?= $tour['feedback_count'] ?> đánh giá</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-medium"><?= number_format($tour['avg_rating'], 1) ?>/5.0</div>
                                            <small class="text-muted">trung bình</small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phân tích theo Từ khóa -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Từ khóa Phổ biến</h5>
                    </div>
                    <div class="card-body">
                        <div class="tag-cloud">
                            <?php if (isset($keywordAnalysis)): ?>
                                <?php foreach ($keywordAnalysis as $keyword): ?>
                                    <span class="badge bg-<?= $keyword['sentiment'] === 'positive' ? 'success' : ($keyword['sentiment'] === 'negative' ? 'danger' : 'secondary') ?> me-2 mb-2"
                                        style="font-size: <?= 12 + ($keyword['count'] * 2) ?>px;">
                                        <?= htmlspecialchars($keyword['keyword']) ?> (<?= $keyword['count'] ?>)
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ Phân bố Đánh giá
    const ratingCtx = document.getElementById('ratingChart');
    if (ratingCtx) {
        new Chart(ratingCtx, {
            type: 'bar',
            data: {
                labels: ['1 sao', '2 sao', '3 sao', '4 sao', '5 sao'],
                datasets: [{
                    label: 'Số lượng đánh giá',
                    data: <?= json_encode($ratingDistribution ?? [5, 12, 25, 45, 68]) ?>,
                    backgroundColor: [
                        '#ef4444',
                        '#f59e0b',
                        '#eab308',
                        '#22c55e',
                        '#3b82f6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10
                        }
                    }
                }
            }
        });
    }

    // Biểu đồ tròn Phân loại Phản hồi
    const feedbackTypeCtx = document.getElementById('feedbackTypeChart');
    if (feedbackTypeCtx) {
        new Chart(feedbackTypeCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($feedbackTypeLabels ?? ['Tour', 'Nhà cung cấp', 'HDV']) ?>,
                datasets: [{
                    data: <?= json_encode($feedbackTypeCounts ?? [120, 45, 30]) ?>,
                    backgroundColor: [
                        '#3b82f6',
                        '#f59e0b',
                        '#22c55e'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Functions
    function showFeedbackTable(type) {
        const rows = document.querySelectorAll('#feedbackTable tbody tr');
        rows.forEach(row => {
            if (type === 'all') {
                row.style.display = '';
            } else if (type === 'positive' && row.dataset.sentiment === 'positive') {
                row.style.display = '';
            } else if (type === 'neutral' && row.dataset.sentiment === 'neutral') {
                row.style.display = '';
            } else if (type === 'negative' && row.dataset.sentiment === 'negative') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Update button states
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    function toggleContent(button) {
        const content = button.previousElementSibling;
        if (content.classList.contains('text-truncate')) {
            content.classList.remove('text-truncate');
            button.textContent = 'Thu gọn';
        } else {
            content.classList.add('text-truncate');
            button.textContent = 'Xem thêm';
        }
    }

    function viewFeedbackDetail(feedbackId) {
        window.open(`<?= BASE_URL_ADMIN ?>&action=feedbacks/detail&id=${feedbackId}`, '_blank');
    }

    function replyFeedback(feedbackId) {
        // Mở modal reply hoặc chuyển đến trang reply
        window.open(`<?= BASE_URL_ADMIN ?>&action=feedbacks/reply&id=${feedbackId}`, '_blank');
    }

    function exportFeedbackReport() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'excel');
        window.open(window.location.pathname + '?' + params.toString(), '_blank');
    }
</script>

<style>
    .kpi-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s ease-in-out;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 1rem;
    }

    .kpi-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .kpi-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .table th {
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #e5e7eb;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .rating-stars {
        display: flex;
        align-items: center;
    }

    .rating-stars .fa-star {
        margin-right: 2px;
    }

    .rating-stars.small .fa-star {
        font-size: 12px;
    }

    .feedback-content {
        max-width: 300px;
    }

    .tag-cloud .badge {
        display: inline-block;
        margin: 2px;
        padding: 4px 8px;
        border-radius: 12px;
    }
</style>
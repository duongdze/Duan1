<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$feedbackStats = $data['feedbackStats'] ?? [];
$feedbacks = $data['feedbacks'] ?? [];
$topRatedTours = $data['topRatedTours'] ?? [];
$filters = $data['filters'] ?? [];
$tours = $data['tours'] ?? [];
?>

<main class="dashboard">
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="breadcrumb-modern">
                        <a href="<?= BASE_URL_ADMIN ?>&action=/" class="breadcrumb-link">
                            <i class="fas fa-home"></i><span>Dashboard</span>
                        </a>
                        <span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
                        <a href="<?= BASE_URL_ADMIN ?>&action=reports" class="breadcrumb-link">
                            <span>Báo Cáo</span>
                        </a>
                        <span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>
                        <span class="breadcrumb-current">Phản Hồi</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-star title-icon"></i>
                            Báo Cáo Phản Hồi & Đánh Giá
                        </h1>
                        <p class="page-subtitle">Đánh giá và phản hồi từ khách hàng</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Feedback Stats -->
        <section class="kpi-section">
            <div class="kpi-grid">
                <div class="kpi-card kpi-warning">
                    <div class="kpi-icon"><i class="fas fa-star"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Đánh Giá Trung Bình</div>
                        <div class="kpi-value"><?= number_format($feedbackStats['avg_rating'] ?? 0, 1) ?>/5.0</div>
                        <?php if (isset($feedbackStats['rating_growth'])): ?>
                            <div class="kpi-trend <?= $feedbackStats['rating_growth'] >= 0 ? 'trend-up' : 'trend-down' ?>">
                                <i class="fas fa-arrow-<?= $feedbackStats['rating_growth'] >= 0 ? 'up' : 'down' ?>"></i>
                                <span><?= number_format(abs($feedbackStats['rating_growth']), 1) ?>%</span>
                                <small>so với kỳ trước</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="kpi-card kpi-info">
                    <div class="kpi-icon"><i class="fas fa-comments"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Tổng Phản Hồi</div>
                        <div class="kpi-value"><?= number_format($feedbackStats['total_feedbacks'] ?? 0) ?></div>
                        <div class="kpi-trend">
                            <span><?= number_format($feedbackStats['feedback_rate'] ?? 0, 1) ?>%</span>
                            <small>tỷ lệ phản hồi</small>
                        </div>
                    </div>
                </div>
                <div class="kpi-card kpi-success">
                    <div class="kpi-icon"><i class="fas fa-smile"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Phản Hồi Tích Cực</div>
                        <div class="kpi-value"><?= number_format($feedbackStats['positive_feedbacks'] ?? 0) ?></div>
                        <div class="kpi-trend trend-up">
                            <span><?= number_format($feedbackStats['positive_rate'] ?? 0, 1) ?>%</span>
                        </div>
                    </div>
                </div>
                <div class="kpi-card kpi-danger">
                    <div class="kpi-icon"><i class="fas fa-frown"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Phản Hồi Tiêu Cực</div>
                        <div class="kpi-value"><?= number_format($feedbackStats['negative_feedbacks'] ?? 0) ?></div>
                        <div class="kpi-trend">
                            <span><?= number_format($feedbackStats['negative_rate'] ?? 0, 1) ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts -->
        <section class="charts-section">
            <div class="charts-row">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title"><i class="fas fa-chart-bar"></i>Phân Bổ Đánh Giá</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="ratingDistChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title"><i class="fas fa-chart-pie"></i>Loại Phản Hồi</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="feedbackTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Top Rated Tours -->
        <section class="tours-section">
            <div class="tours-header">
                <h3 class="tours-title"><i class="fas fa-trophy"></i>Top Tours Được Đánh Giá Cao Nhất</h3>
            </div>
            <div class="tours-container">
                <?php if (!empty($topRatedTours)): ?>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Tour</th>
                                    <th>Số Đánh Giá</th>
                                    <th>Rating TB</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topRatedTours as $index => $tour): ?>
                                    <tr>
                                        <td><strong><?= $index + 1 ?></strong></td>
                                        <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                                        <td><?= number_format($tour['feedback_count']) ?></td>
                                        <td>
                                            <div class="rating-display">
                                                <?php
                                                $rating = $tour['avg_rating'] ?? 0;
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= floor($rating)) {
                                                        echo '<i class="fas fa-star text-warning"></i>';
                                                    } elseif ($i - 0.5 <= $rating) {
                                                        echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                                    } else {
                                                        echo '<i class="far fa-star text-warning"></i>';
                                                    }
                                                }
                                                ?>
                                                <span class="ms-2"><strong><?= number_format($rating, 1) ?></strong></span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state-mini">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có dữ liệu</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($data['ratingDistribution'])): ?>
    new Chart(document.getElementById('ratingDistChart'), {
        type: 'bar',
        data: {
            labels: ['5 Sao', '4 Sao', '3 Sao', '2 Sao', '1 Sao'],
            datasets: [{
                label: 'Số lượng',
                data: <?= json_encode($data['ratingDistribution'] ?? [0,0,0,0,0]) ?>,
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#991b1b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    <?php endif; ?>

    <?php if (isset($data['feedbackTypeLabels'])): ?>
    new Chart(document.getElementById('feedbackTypeChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($data['feedbackTypeLabels'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($data['feedbackTypeCounts'] ?? []) ?>,
                backgroundColor: ['#667eea', '#10b981', '#f59e0b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
    <?php endif; ?>
});
</script>
<style>
.rating-display {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}
.rating-display i {
    font-size: 1rem;
}
</style>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin/reports.css">
<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>

<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';
include_once PATH_VIEW_ADMIN . 'components/advanced_filters.php';
?>

<main class="wrapper">
    <div class="main-content">
        <div class="page-header">
            <h1 class="h2">Dashboard Tổng quan</h1>
            <p class="text-muted">Tổng quan tất cả các chỉ số KPI và hiệu suất kinh doanh</p>
        </div>

        <!-- Quick Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Khoảng thời gian</label>
                        <select class="form-select" id="dashboard_period" onchange="updateDashboard()">
                            <option value="7">7 ngày qua</option>
                            <option value="30" selected>30 ngày qua</option>
                            <option value="90">90 ngày qua</option>
                            <option value="this_month">Tháng này</option>
                            <option value="last_month">Tháng trước</option>
                            <option value="this_quarter">Quý này</option>
                            <option value="this_year">Năm nay</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Refresh</label>
                        <button type="button" class="btn btn-primary w-100" onclick="updateDashboard()">
                            <i class="fas fa-sync-alt me-2"></i>Làm mới dữ liệu
                        </button>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Export</label>
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-outline-success" onclick="exportDashboard('excel')">
                                <i class="fas fa-file-excel me-2"></i>Excel
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="exportDashboard('pdf')">
                                <i class="fas fa-file-pdf me-2"></i>PDF
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="printDashboard()">
                                <i class="fas fa-print me-2"></i>In
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main KPI Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card kpi-card border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label text-primary">Doanh thu</div>
                                <div class="kpi-value h3"><?= number_format($dashboardData['financial']['total_revenue'] ?? 0, 0, ',', '.') ?> VNĐ</div>
                                <small class="<?= ($dashboardData['financial']['revenue_growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas fa-arrow-<?= ($dashboardData['financial']['revenue_growth'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                    <?= number_format($dashboardData['financial']['revenue_growth'] ?? 0, 1) ?>%
                                </small>
                            </div>
                            <div class="kpi-icon bg-primary bg-opacity-10">
                                <i class="fas fa-dollar-sign text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card kpi-card border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label text-success">Bookings</div>
                                <div class="kpi-value h3"><?= number_format($dashboardData['bookings']['total_bookings'] ?? 0) ?></div>
                                <small class="<?= ($dashboardData['bookings']['booking_growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas fa-arrow-<?= ($dashboardData['bookings']['booking_growth'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                    <?= number_format($dashboardData['bookings']['booking_growth'] ?? 0, 1) ?>%
                                </small>
                            </div>
                            <div class="kpi-icon bg-success bg-opacity-10">
                                <i class="fas fa-calendar-check text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card kpi-card border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label text-warning">Tỷ lệ Chuyển đổi</div>
                                <div class="kpi-value h3"><?= number_format($dashboardData['conversion']['booking_to_payment'] ?? 0, 1) ?>%</div>
                                <small class="<?= ($dashboardData['conversion']['conversion_growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas fa-arrow-<?= ($dashboardData['conversion']['conversion_growth'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                    <?= number_format($dashboardData['conversion']['conversion_growth'] ?? 0, 1) ?>%
                                </small>
                            </div>
                            <div class="kpi-icon bg-warning bg-opacity-10">
                                <i class="fas fa-percentage text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card kpi-card border-start border-info border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label text-info">Đánh giá TB</div>
                                <div class="kpi-value h3"><?= number_format($dashboardData['feedback']['avg_rating'] ?? 0, 1) ?></div>
                                <small class="<?= ($dashboardData['feedback']['rating_growth'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <i class="fas fa-arrow-<?= ($dashboardData['feedback']['rating_growth'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                    <?= number_format($dashboardData['feedback']['rating_growth'] ?? 0, 1) ?>%
                                </small>
                            </div>
                            <div class="kpi-icon bg-info bg-opacity-10">
                                <i class="fas fa-star text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary KPI Row -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label text-muted">Lợi nhuận</div>
                                <div class="kpi-value h4 <?= ($dashboardData['financial']['profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= number_format($dashboardData['financial']['profit'] ?? 0, 0, ',', '.') ?>
                                </div>
                                <small class="text-muted">
                                    <?= number_format($dashboardData['financial']['profit_margin'] ?? 0, 1) ?>% margin
                                </small>
                            </div>
                            <div class="kpi-icon bg-light">
                                <i class="fas fa-chart-line text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label text-muted">Khách hàng</div>
                                <div class="kpi-value h4"><?= number_format($dashboardData['bookings']['total_customers'] ?? 0) ?></div>
                                <small class="text-muted">
                                    <?= number_format($dashboardData['bookings']['avg_customers_per_booking'] ?? 0, 1) ?>/booking
                                </small>
                            </div>
                            <div class="kpi-icon bg-light">
                                <i class="fas fa-users text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label text-muted">Tours hoạt động</div>
                                <div class="kpi-value h4"><?= number_format($dashboardData['tours']['active_tours'] ?? 0) ?></div>
                                <small class="text-muted">
                                    <?= number_format($dashboardData['tours']['total_tours'] ?? 0) ?> tổng
                                </small>
                            </div>
                            <div class="kpi-icon bg-light">
                                <i class="fas fa-map-marked-alt text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card kpi-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="kpi-label text-muted">Phản hồi</div>
                                <div class="kpi-value h4"><?= number_format($dashboardData['feedback']['total_feedbacks'] ?? 0) ?></div>
                                <small class="text-muted">
                                    <?= number_format($dashboardData['feedback']['feedback_rate'] ?? 0, 1) ?>% rate
                                </small>
                            </div>
                            <div class="kpi-icon bg-light">
                                <i class="fas fa-comments text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Xu hướng Doanh thu & Bookings</h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary active" onclick="updateTrendChart('revenue')">Doanh thu</button>
                            <button type="button" class="btn btn-outline-primary" onclick="updateTrendChart('bookings')">Bookings</button>
                            <button type="button" class="btn btn-outline-primary" onclick="updateTrendChart('profit')">Lợi nhuận</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="trendChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Phân bố Doanh thu</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueDistributionChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tỷ lệ Chuyển đổi theo Nguồn</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="conversionBySourceChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Phân phối Đánh giá</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ratingDistributionChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers Tables -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Tours theo Doanh thu</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Tour</th>
                                        <th class="text-right">Bookings</th>
                                        <th class="text-right">Doanh thu</th>
                                        <th class="text-right">Lợi nhuận</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($dashboardData['top_revenue_tours'])): ?>
                                        <?php foreach (array_slice($dashboardData['top_revenue_tours'], 0, 5) as $tour): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded-circle me-2">
                                                            <i class="fas fa-map-marked-alt text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                                            <small class="text-muted"><?= htmlspecialchars($tour['category_name'] ?? '') ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <span class="badge bg-info"><?= $tour['booking_count'] ?></span>
                                                </td>
                                                <td class="text-right">
                                                    <span class="text-success fw-medium">
                                                        <?= number_format($tour['revenue'], 0, ',', '.') ?>
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <span class="<?= ($tour['profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?> fw-medium">
                                                        <?= number_format($tour['profit'] ?? 0, 0, ',', '.') ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Tours theo Đánh giá</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Tour</th>
                                        <th class="text-right">Đánh giá</th>
                                        <th class="text-right">Phản hồi</th>
                                        <th class="text-right">Tỷ lệ chuyển đổi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($dashboardData['top_rated_tours'])): ?>
                                        <?php foreach (array_slice($dashboardData['top_rated_tours'], 0, 5) as $tour): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded-circle me-2">
                                                            <i class="fas fa-star text-warning"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium"><?= htmlspecialchars($tour['tour_name']) ?></div>
                                                            <small class="text-muted"><?= htmlspecialchars($tour['category_name'] ?? '') ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <span class="me-1"><?= number_format($tour['avg_rating'], 1) ?></span>
                                                        <div class="star-rating">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?= $i <= round($tour['avg_rating']) ? 'text-warning' : 'text-muted' ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <span class="badge bg-info"><?= $tour['feedback_count'] ?></span>
                                                </td>
                                                <td class="text-right">
                                                    <span class="badge bg-success"><?= number_format($tour['conversion_rate'] ?? 0, 1) ?>%</span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Alerts -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Hoạt động Gần đây</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            <?php if (isset($dashboardData['recent_activities'])): ?>
                                <?php foreach (array_slice($dashboardData['recent_activities'], 0, 5) as $activity): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon bg-<?= $activity['color'] ?? 'primary' ?> bg-opacity-10">
                                            <i class="fas fa-<?= $activity['icon'] ?? 'circle' ?> text-<?= $activity['color'] ?? 'primary' ?>"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?= $activity['title'] ?></div>
                                            <div class="activity-description"><?= $activity['description'] ?></div>
                                            <small class="text-muted"><?= $activity['time'] ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cảnh báo & Thông báo</h5>
                    </div>
                    <div class="card-body">
                        <div class="alerts-feed">
                            <?php if (isset($dashboardData['alerts'])): ?>
                                <?php foreach ($dashboardData['alerts'] as $alert): ?>
                                    <div class="alert alert-<?= $alert['type'] ?? 'info' ?> alert-sm d-flex align-items-center">
                                        <i class="fas fa-<?= $alert['icon'] ?? 'info-circle' ?> me-2"></i>
                                        <div class="flex-grow-1">
                                            <strong><?= $alert['title'] ?></strong>
                                            <div class="small"><?= $alert['message'] ?></div>
                                        </div>
                                        <small class="text-muted"><?= $alert['time'] ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .kpi-card {
        transition: transform 0.2s ease-in-out;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .kpi-label {
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .kpi-value {
        font-weight: 700;
        line-height: 1.2;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .activity-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex-grow: 1;
    }

    .activity-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .activity-description {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .alert-sm {
        padding: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .star-rating {
        font-size: 0.75rem;
    }
</style>

<script>
    // Initialize dashboard charts
    let trendChart, revenueDistributionChart, conversionBySourceChart, ratingDistributionChart;

    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        updateDashboard();
    });

    function initializeCharts() {
        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Doanh thu',
                    data: [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
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
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' VNĐ';
                            }
                        }
                    }
                }
            }
        });

        // Revenue Distribution Chart
        const revenueDistCtx = document.getElementById('revenueDistributionChart').getContext('2d');
        revenueDistributionChart = new Chart(revenueDistCtx, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Conversion by Source Chart
        const conversionCtx = document.getElementById('conversionBySourceChart').getContext('2d');
        conversionBySourceChart = new Chart(conversionCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Tỷ lệ chuyển đổi (%)',
                    data: [],
                    backgroundColor: '#3b82f6',
                    borderRadius: 5
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
                        title: {
                            display: true,
                            text: 'Tỷ lệ chuyển đổi (%)'
                        }
                    }
                }
            }
        });

        // Rating Distribution Chart
        const ratingCtx = document.getElementById('ratingDistributionChart').getContext('2d');
        ratingDistributionChart = new Chart(ratingCtx, {
            type: 'bar',
            data: {
                labels: ['5 sao', '4 sao', '3 sao', '2 sao', '1 sao'],
                datasets: [{
                    label: 'Số lượng',
                    data: [],
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444',
                        '#6b7280'
                    ],
                    borderRadius: 5
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
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function updateDashboard() {
        const period = document.getElementById('dashboard_period').value;

        // Fetch dashboard data via AJAX
        fetch(`<?= BASE_URL_ADMIN ?>&action=reports/dashboard_data&period=${period}`)
            .then(response => response.json())
            .then(data => {
                updateCharts(data);
                updateKPICards(data);
            })
            .catch(error => {
                console.error('Error updating dashboard:', error);
            });
    }

    function updateCharts(data) {
        // Update trend chart
        if (data.trend_data) {
            trendChart.data.labels = data.trend_data.labels;
            trendChart.data.datasets[0].data = data.trend_data.revenue;
            trendChart.update();
        }

        // Update revenue distribution
        if (data.revenue_distribution) {
            revenueDistributionChart.data.labels = data.revenue_distribution.labels;
            revenueDistributionChart.data.datasets[0].data = data.revenue_distribution.data;
            revenueDistributionChart.update();
        }

        // Update conversion by source
        if (data.conversion_by_source) {
            conversionBySourceChart.data.labels = data.conversion_by_source.labels;
            conversionBySourceChart.data.datasets[0].data = data.conversion_by_source.data;
            conversionBySourceChart.update();
        }

        // Update rating distribution
        if (data.rating_distribution) {
            ratingDistributionChart.data.datasets[0].data = data.rating_distribution;
            ratingDistributionChart.update();
        }
    }

    function updateTrendChart(type) {
        // Update button states
        document.querySelectorAll('.btn-group button').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        // Update chart data based on type
        const period = document.getElementById('dashboard_period').value;

        fetch(`<?= BASE_URL_ADMIN ?>&action=reports/dashboard_data&period=${period}&trend_type=${type}`)
            .then(response => response.json())
            .then(data => {
                if (data.trend_data) {
                    const labels = {
                        'revenue': 'Doanh thu',
                        'bookings': 'Bookings',
                        'profit': 'Lợi nhuận'
                    };

                    const datasets = {
                        'revenue': data.trend_data.revenue,
                        'bookings': data.trend_data.bookings,
                        'profit': data.trend_data.profit
                    };

                    trendChart.data.datasets[0].label = labels[type];
                    trendChart.data.datasets[0].data = datasets[type];
                    trendChart.update();
                }
            })
            .catch(error => {
                console.error('Error updating trend chart:', error);
            });
    }

    function updateKPICards(data) {
        // Update KPI cards with new data
        // This would update the DOM elements with new values
        console.log('Updating KPI cards with data:', data);
    }

    function exportDashboard(format) {
        const period = document.getElementById('dashboard_period').value;
        window.open(`<?= BASE_URL_ADMIN ?>&action=reports/export_dashboard&format=${format}&period=${period}`, '_blank');
    }

    function printDashboard() {
        window.print();
    }
</script>

<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
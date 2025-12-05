<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

// Get data from controller
$dashboardData = $data['dashboardData'] ?? [];
$period = $data['period'] ?? '30';
$dateRange = $data['dateRange'] ?? [];

$financial = $dashboardData['financial'] ?? [];
$bookings = $dashboardData['bookings'] ?? [];
$conversion = $dashboardData['conversion'] ?? [];
$feedback = $dashboardData['feedback'] ?? [];
?>

<main class="dashboard">
    <div class="dashboard-container">
        <!-- Header -->
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
                        <a href="<?= BASE_URL_ADMIN ?>&action=reports" class="breadcrumb-link">
                            <span>Báo Cáo</span>
                        </a>
                        <span class="breadcrumb-separator">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="breadcrumb-current">Tổng Quan</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-chart-pie title-icon"></i>
                            Dashboard Tổng Quan
                        </h1>
                        <p class="page-subtitle">Theo dõi các chỉ số kinh doanh quan trọng</p>
                    </div>
                </div>
                <div class="header-right">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>
                        In Báo Cáo
                    </button>
                </div>
            </div>
        </header>

        <!-- Period Filter -->
        <section class="period-filter-section">
            <div class="filter-card">
                <form method="GET" action="<?= BASE_URL_ADMIN . '&action=reports/dashboard' ?>" class="period-filter-form">
                    <input type="hidden" name="action" value="reports/dashboard">
                    <div class="period-buttons">
                        <button type="submit" name="period" value="7" class="period-btn <?= $period == '7' ? 'active' : '' ?>">
                            7 Ngày
                        </button>
                        <button type="submit" name="period" value="30" class="period-btn <?= $period == '30' ? 'active' : '' ?>">
                            30 Ngày
                        </button>
                        <button type="submit" name="period" value="90" class="period-btn <?= $period == '90' ? 'active' : '' ?>">
                            90 Ngày
                        </button>
                        <button type="submit" name="period" value="this_month" class="period-btn <?= $period == 'this_month' ? 'active' : '' ?>">
                            Tháng Này
                        </button>
                        <button type="submit" name="period" value="this_quarter" class="period-btn <?= $period == 'this_quarter' ? 'active' : '' ?>">
                            Quý Này
                        </button>
                        <button type="submit" name="period" value="this_year" class="period-btn <?= $period == 'this_year' ? 'active' : '' ?>">
                            Năm Này
                        </button>
                    </div>
                    <?php if (!empty($dateRange)): ?>
                        <div class="date-range-display">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?= date('d/m/Y', strtotime($dateRange['from'])) ?> - <?= date('d/m/Y', strtotime($dateRange['to'])) ?></span>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </section>

        <!-- KPI Cards -->
        <section class="kpi-section">
            <div class="kpi-grid">
                <!-- Revenue Card -->
                <div class="kpi-card kpi-success">
                    <div class="kpi-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="kpi-content">
                        <div class="kpi-label">Tổng Doanh Thu</div>
                        <div class="kpi-value"><?= number_format($financial['total_revenue'] ?? 0, 0, ',', '.') ?> ₫</div>
                        <?php if (isset($financial['revenue_growth'])): ?>
                            <div class="kpi-trend <?= $financial['revenue_growth'] >= 0 ? 'trend-up' : 'trend-down' ?>">
                                <i class="fas fa-arrow-<?= $financial['revenue_growth'] >= 0 ? 'up' : 'down' ?>"></i>
                                <span><?= number_format(abs($financial['revenue_growth']), 1) ?>%</span>
                                <small>so với kỳ trước</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Expense Card -->
                <div class="kpi-card kpi-danger">
                    <div class="kpi-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="kpi-content">
                        <div class="kpi-label">Tổng Chi Phí</div>
                        <div class="kpi-value"><?= number_format($financial['total_expense'] ?? 0, 0, ',', '.') ?> ₫</div>
                        <?php if (isset($financial['expense_growth'])): ?>
                            <div class="kpi-trend <?= $financial['expense_growth'] <= 0 ? 'trend-up' : 'trend-down' ?>">
                                <i class="fas fa-arrow-<?= $financial['expense_growth'] >= 0 ? 'up' : 'down' ?>"></i>
                                <span><?= number_format(abs($financial['expense_growth']), 1) ?>%</span>
                                <small>so với kỳ trước</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Profit Card -->
                <div class="kpi-card kpi-primary">
                    <div class="kpi-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="kpi-content">
                        <div class="kpi-label">Lợi Nhuận</div>
                        <div class="kpi-value"><?= number_format($financial['profit'] ?? 0, 0, ',', '.') ?> ₫</div>
                        <?php if (isset($financial['profit_growth'])): ?>
                            <div class="kpi-trend <?= $financial['profit_growth'] >= 0 ? 'trend-up' : 'trend-down' ?>">
                                <i class="fas fa-arrow-<?= $financial['profit_growth'] >= 0 ? 'up' : 'down' ?>"></i>
                                <span><?= number_format(abs($financial['profit_growth']), 1) ?>%</span>
                                <small>so với kỳ trước</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Bookings Card -->
                <div class="kpi-card kpi-info">
                    <div class="kpi-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="kpi-content">
                        <div class="kpi-label">Tổng Booking</div>
                        <div class="kpi-value"><?= number_format($bookings['total_bookings'] ?? 0) ?></div>
                        <?php if (isset($bookings['booking_growth'])): ?>
                            <div class="kpi-trend <?= $bookings['booking_growth'] >= 0 ? 'trend-up' : 'trend-down' ?>">
                                <i class="fas fa-arrow-<?= $bookings['booking_growth'] >= 0 ? 'up' : 'down' ?>"></i>
                                <span><?= number_format(abs($bookings['booking_growth']), 1) ?>%</span>
                                <small>so với kỳ trước</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts Row 1 -->
        <section class="charts-section">
            <div class="charts-row">
                <!-- Trend Chart -->
                <div class="chart-card chart-card-large">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-area"></i>
                            Xu Hướng Doanh Thu & Lợi Nhuận
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Revenue Distribution -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-chart-pie"></i>
                            Doanh Thu Theo Danh Mục
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="revenueDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts Row 2 -->
        <section class="charts-section">
            <div class="charts-row">
                <!-- Conversion by Source -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-funnel-dollar"></i>
                            Chuyển Đổi Theo Nguồn
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="conversionSourceChart"></canvas>
                    </div>
                </div>

                <!-- Rating Distribution -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title">
                            <i class="fas fa-star"></i>
                            Phân Bổ Đánh Giá
                        </h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="ratingChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Top Tours & Activities Row -->
        <section class="data-section">
            <div class="data-row">
                <!-- Top Tours Table -->
                <div class="data-card">
                    <div class="data-header">
                        <h3 class="data-title">
                            <i class="fas fa-trophy"></i>
                            Top Tours Theo Doanh Thu
                        </h3>
                    </div>
                    <div class="data-body">
                        <?php if (!empty($dashboardData['top_revenue_tours'])): ?>
                            <div class="table-responsive">
                                <table class="table table-modern">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên Tour</th>
                                            <th>Doanh Thu</th>
                                            <th>Lợi Nhuận</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($dashboardData['top_revenue_tours'], 0, 5) as $index => $tour): ?>
                                            <tr>
                                                <td><strong><?= $index + 1 ?></strong></td>
                                                <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                                                <td class="text-success"><strong><?= number_format($tour['revenue'] ?? 0, 0, ',', '.') ?> ₫</strong></td>
                                                <td class="<?= ($tour['profit'] ?? 0) >= 0 ? 'text-primary' : 'text-danger' ?>">
                                                    <?= number_format($tour['profit'] ?? 0, 0, ',', '.') ?> ₫
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
                </div>

                <!-- Recent Activities -->
                <div class="data-card">
                    <div class="data-header">
                        <h3 class="data-title">
                            <i class="fas fa-history"></i>
                            Hoạt Động Gần Đây
                        </h3>
                    </div>
                    <div class="data-body">
                        <?php if (!empty($dashboardData['recent_activities'])): ?>
                            <div class="activities-list">
                                <?php foreach ($dashboardData['recent_activities'] as $activity): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon activity-<?= $activity['color'] ?? 'info' ?>">
                                            <i class="fas <?= $activity['icon'] ?? 'fa-circle' ?>"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?= htmlspecialchars($activity['title'] ?? '') ?></div>
                                            <div class="activity-description"><?= htmlspecialchars($activity['description'] ?? '') ?></div>
                                            <div class="activity-time"><?= htmlspecialchars($activity['time'] ?? '') ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state-mini">
                                <i class="fas fa-clock"></i>
                                <p>Chưa có hoạt động</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alerts Section -->
        <?php if (!empty($dashboardData['alerts'])): ?>
            <section class="alerts-section">
                <h3 class="section-title">
                    <i class="fas fa-bell"></i>
                    Cảnh Báo & Thông Báo
                </h3>
                <div class="alerts-grid">
                    <?php foreach ($dashboardData['alerts'] as $alert): ?>
                        <div class="alert-card alert-<?= $alert['type'] ?? 'info' ?>">
                            <div class="alert-icon">
                                <i class="fas fa-<?= $alert['icon'] ?? 'info-circle' ?>"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title"><?= htmlspecialchars($alert['title'] ?? '') ?></div>
                                <div class="alert-message"><?= htmlspecialchars($alert['message'] ?? '') ?></div>
                                <div class="alert-time"><?= htmlspecialchars($alert['time'] ?? '') ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trend Chart
    <?php if (isset($dashboardData['trend_data'])): ?>
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($dashboardData['trend_data']['labels'] ?? []) ?>,
                    datasets: [{
                        label: 'Doanh Thu',
                        data: <?= json_encode($dashboardData['trend_data']['revenue'] ?? []) ?>,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Lợi Nhuận',
                        data: <?= json_encode($dashboardData['trend_data']['profit'] ?? []) ?>,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' ₫';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    <?php endif; ?>

    // Revenue Distribution Chart
    <?php if (isset($dashboardData['revenue_distribution'])): ?>
        const revenueDistCtx = document.getElementById('revenueDistributionChart');
        if (revenueDistCtx) {
            new Chart(revenueDistCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($dashboardData['revenue_distribution']['labels'] ?? []) ?>,
                    datasets: [{
                        data: <?= json_encode($dashboardData['revenue_distribution']['data'] ?? []) ?>,
                        backgroundColor: ['#667eea', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    <?php endif; ?>

    // Conversion by Source Chart
    <?php if (isset($dashboardData['conversion_by_source'])): ?>
        const conversionCtx = document.getElementById('conversionSourceChart');
        if (conversionCtx) {
            new Chart(conversionCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($dashboardData['conversion_by_source'], 'source') ?? []) ?>,
                    datasets: [{
                        label: 'Tỷ lệ chuyển đổi (%)',
                        data: <?= json_encode(array_column($dashboardData['conversion_by_source'], 'conversion_rate') ?? []) ?>,
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }
    <?php endif; ?>

    // Rating Distribution Chart
    <?php if (isset($dashboardData['rating_distribution'])): ?>
        const ratingCtx = document.getElementById('ratingChart');
        if (ratingCtx) {
            new Chart(ratingCtx, {
                type: 'bar',
                data: {
                    labels: ['5 Sao', '4 Sao', '3 Sao', '2 Sao', '1 Sao'],
                    datasets: [{
                        label: 'Số lượng',
                        data: <?= json_encode($dashboardData['rating_distribution'] ?? [0,0,0,0,0]) ?>,
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#991b1b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    <?php endif; ?>
});
</script>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin/reports.css">

<?php
include_once PATH_VIEW_ADMIN . 'default/footer.php';
?>

<?php
include_once PATH_VIEW_ADMIN . 'default/header.php';
include_once PATH_VIEW_ADMIN . 'default/sidebar.php';

$bookingStats = $data['bookingStats'] ?? [];
$bookings = $data['bookings'] ?? [];
$topTours = $data['topTours'] ?? [];
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
                        <span class="breadcrumb-current">Booking</span>
                    </div>
                    <div class="page-title-section">
                        <h1 class="page-title">
                            <i class="fas fa-calendar-check title-icon"></i>
                            Báo Cáo Booking
                        </h1>
                        <p class="page-subtitle">Thống kê và phân tích booking</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Filters -->
        <section class="filters-section">
            <div class="filter-card">
                <form method="GET" action="<?= BASE_URL_ADMIN . '&action=reports/bookings' ?>">
                    <input type="hidden" name="action" value="reports/bookings">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label>Từ ngày</label>
                            <input type="date" class="form-control" name="date_from" value="<?= $filters['date_from'] ?? date('Y-m-01') ?>">
                        </div>
                        <div class="filter-group">
                            <label>Đến ngày</label>
                            <input type="date" class="form-control" name="date_to" value="<?= $filters['date_to'] ?? date('Y-m-d') ?>">
                        </div>
                        <div class="filter-group">
                            <label>Tour</label>
                            <select class="form-select" name="tour_id">
                                <option value="">Tất cả</option>
                                <?php foreach ($tours as $tour): ?>
                                    <option value="<?= $tour['id'] ?>" <?= ($filters['tour_id'] ?? '') == $tour['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tour['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Trạng thái</label>
                            <select class="form-select" name="status">
                                <option value="">Tất cả</option>
                                <option value="pending" <?= ($filters['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                <option value="confirmed" <?= ($filters['status'] ?? '') == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                <option value="deposited" <?= ($filters['status'] ?? '') == 'deposited' ? 'selected' : '' ?>>Đã cọc</option>
                                <option value="paid" <?= ($filters['status'] ?? '') == 'paid' ? 'selected' : '' ?>>Đã thanh toán</option>
                                <option value="completed" <?= ($filters['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Hoàn tất</option>
                                <option value="cancelled" <?= ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                        </div>
                        <div class="filter-group filter-actions-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search me-2"></i>Lọc</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Stats Cards -->
        <section class="kpi-section">
            <div class="kpi-grid">
                <div class="kpi-card kpi-primary">
                    <div class="kpi-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Tổng Booking</div>
                        <div class="kpi-value"><?= number_format($bookingStats['total_bookings'] ?? 0) ?></div>
                    </div>
                </div>
                <div class="kpi-card kpi-success">
                    <div class="kpi-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Thành Công</div>
                        <div class="kpi-value"><?= number_format($bookingStats['successful_bookings'] ?? 0) ?></div>
                        <div class="kpi-trend trend-up">
                            <span><?= number_format($bookingStats['success_rate'] ?? 0, 1) ?>%</span>
                            <small>tỷ lệ thành công</small>
                        </div>
                    </div>
                </div>
                <div class="kpi-card kpi-danger">
                    <div class="kpi-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Đã Hủy</div>
                        <div class="kpi-value"><?= number_format($bookingStats['cancelled_bookings'] ?? 0) ?></div>
                        <div class="kpi-trend">
                            <span><?= number_format($bookingStats['cancellation_rate'] ?? 0, 1) ?>%</span>
                            <small>tỷ lệ hủy</small>
                        </div>
                    </div>
                </div>
                <div class="kpi-card kpi-info">
                    <div class="kpi-icon"><i class="fas fa-users"></i></div>
                    <div class="kpi-content">
                        <div class="kpi-label">Khách Hàng</div>
                        <div class="kpi-value"><?= number_format($bookingStats['total_customers'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts -->
        <section class="charts-section">
            <div class="charts-row">
                <div class="chart-card chart-card-large">
                    <div class="chart-header">
                        <h3 class="chart-title"><i class="fas fa-chart-line"></i>Booking Theo Tháng</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="monthlyBookingsChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h3 class="chart-title"><i class="fas fa-chart-pie"></i>Booking Theo Nguồn</h3>
                    </div>
                    <div class="chart-body">
                        <canvas id="sourceChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Top Tours -->
        <section class="tours-section">
            <div class="tours-header">
                <h3 class="tours-title"><i class="fas fa-trophy"></i>Top Tours Được Đặt Nhiều Nhất</h3>
            </div>
            <div class="tours-container">
                <?php if (!empty($topTours)): ?>
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên Tour</th>
                                    <th>Số Booking</th>
                                    <th>Tổng Khách</th>
                                    <th>Doanh Thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topTours as $index => $tour): ?>
                                    <tr>
                                        <td><strong><?= $index + 1 ?></strong></td>
                                        <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                                        <td><?= number_format($tour['booking_count']) ?></td>
                                        <td><?= number_format($tour['total_passengers'] ?? 0) ?></td>
                                        <td class="text-success"><strong><?= number_format($tour['total_revenue'] ?? 0, 0, ',', '.') ?> ₫</strong></td>
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
    <?php if (isset($data['monthlyBookings'])): ?>
    new Chart(document.getElementById('monthlyBookingsChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($data['monthlyLabels'] ?? []) ?>,
            datasets: [{
                label: 'Tổng Booking',
                data: <?= json_encode($data['monthlyBookings'] ?? []) ?>,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Thành Công',
                data: <?= json_encode($data['monthlySuccessfulBookings'] ?? []) ?>,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });
    <?php endif; ?>

    <?php if (isset($data['sourceNames'])): ?>
    new Chart(document.getElementById('sourceChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($data['sourceNames'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($data['sourceCounts'] ?? []) ?>,
                backgroundColor: ['#667eea', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
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
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin/reports.css">
<?php include_once PATH_VIEW_ADMIN . 'default/footer.php'; ?>
